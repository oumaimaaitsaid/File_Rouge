<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Commande::query()->with('user');
        
        // Filtrage par statut
        if ($request->has('status') && !empty($request->status)) {
            $query->where('statut', $request->status);
        }
        
        // Filtrage par paiement
        if ($request->has('payment_confirmed') && $request->payment_confirmed !== null) {
            $query->where('paiement_confirme', $request->payment_confirmed === 'true' || $request->payment_confirmed === '1');
        }
        
        // Recherche par numéro de commande ou email/nom client
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_commande', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('email', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%")
                        ->orWhere('prenom', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Filtrage par date
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Tri
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        // Pagination
        $perPage = $request->get('per_page', 10);
        $orders = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->numero_commande,
                    'customer' => [
                        'id' => $order->user->id,
                        'name' => $order->user->name.' '.$order->user->prenom,
                        'email' => $order->user->email
                    ],
                    'total' => $order->montant_total,
                    'status' => $order->statut,
                    'payment_method' => $order->methode_paiement,
                    'payment_confirmed' => $order->paiement_confirme,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s')
                ];
            }),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
            ]
        ]);
    }
    
    
    public function show($id)
    {
        try {
            $order = Commande::with(['user', 'ligneCommandes.produit'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $order->id,
                    'order_number' => $order->numero_commande,
                    'customer' => [
                        'id' => $order->user->id,
                        'name' => $order->user->name.' '.$order->user->prenom,
                        'email' => $order->user->email,
                        'phone' => $order->user->telephone
                    ],
                    'subtotal' => $order->montant_total - $order->frais_livraison + $order->remise,
                    'shipping_fee' => $order->frais_livraison,
                    'discount' => $order->remise,
                    'total' => $order->montant_total,
                    'status' => $order->statut,
                    'payment_method' => $order->methode_paiement,
                    'payment_confirmed' => $order->paiement_confirme,
                    'payment_date' => $order->date_paiement ? $order->date_paiement->format('Y-m-d H:i:s') : null,
                    'payment_reference' => $order->reference_paiement,
                    'shipping_address' => [
                        'address' => $order->adresse_livraison,
                        'city' => $order->ville_livraison,
                        'postal_code' => $order->code_postal_livraison,
                        'country' => $order->pays_livraison,
                        'phone' => $order->telephone_livraison
                    ],
                    'notes' => $order->notes,
                    'admin_notes' => $order->notes_admin,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'items' => $order->ligneCommandes->map(function($item) {
                        return [
                            'id' => $item->id,
                            'product_id' => $item->produit_id,
                            'product_name' => $item->nom_produit,
                            'quantity' => $item->quantite,
                            'unit_price' => $item->prix_unitaire,
                            'subtotal' => $item->quantite * $item->prix_unitaire,
                            'product' => $item->produit ? [
                                'id' => $item->produit->id,
                                'name' => $item->produit->nom,
                                'slug' => $item->produit->slug,
                                'image' => $item->produit->imagePrincipale ? asset('storage/' . $item->produit->imagePrincipale->chemin) : null
                            ] : null
                        ];
                    })
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Commande non trouvée',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
   
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:en_attente,confirmee,en_preparation,en_livraison,livree,annulee',
            'admin_notes' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $order = Commande::findOrFail($id);
            
            // Si la commande passe de non-annulée à annulée, remettre les produits en stock
            if ($order->statut != 'annulee' && $request->status == 'annulee') {
                DB::beginTransaction();
                
                // Restaurer les stocks
                foreach ($order->ligneCommandes as $ligne) {
                    $produit = $ligne->produit;
                    if ($produit) {
                        $produit->update([
                            'stock' => $produit->stock + $ligne->quantite
                        ]);
                    }
                }
                
                // Mettre à jour la commande
                $order->update([
                    'statut' => $request->status,
                    'notes_admin' => $request->has('admin_notes') ? $request->admin_notes : $order->notes_admin
                ]);
                
                DB::commit();
            } else {
                // Mise à jour normale
                $order->update([
                    'statut' => $request->status,
                    'notes_admin' => $request->has('admin_notes') ? $request->admin_notes : $order->notes_admin
                ]);
            }
            
            // TODO: Envoyer une notification au client sur le changement de statut
            
            return response()->json([
                'success' => true,
                'message' => 'Statut de la commande mis à jour avec succès',
                'data' => [
                    'id' => $order->id,
                    'order_number' => $order->numero_commande,
                    'status' => $order->statut,
                    'admin_notes' => $order->notes_admin
                ]
            ]);
            
        } catch (\Exception $e) {
            if (isset($db) && DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
   
     
    
   
     
}