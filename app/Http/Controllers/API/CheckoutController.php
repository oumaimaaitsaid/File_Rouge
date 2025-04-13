<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
  
    public function validateCart(Request $request)
    {
        // L'utilisateur doit être connecté
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour passer une commande'
            ], 401);
        }

        // Récupérer le panier
        $cart = Cart::where('user_id', Auth::id())->with('items.produit')->first();

        // Vérifier si le panier existe et n'est pas vide
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Votre panier est vide'
            ], 400);
        }

        // Vérifier chaque article du panier
        $invalidItems = [];
        foreach ($cart->items as $item) {
            $product = $item->produit;
            
            // Vérifier si le produit est disponible
            if (!$product->disponible) {
                $invalidItems[] = [
                    'id' => $product->id,
                    'name' => $product->nom,
                    'reason' => 'Ce produit n\'est plus disponible'
                ];
                continue;
            }
            
            // Vérifier le stock
            if ($product->stock < $item->quantite) {
                $invalidItems[] = [
                    'id' => $product->id,
                    'name' => $product->nom,
                    'reason' => "Stock insuffisant. Disponible: {$product->stock}, Demandé: {$item->quantite}"
                ];
                continue;
            }
        }

        // Si des articles ne sont pas valides
        if (!empty($invalidItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Certains articles de votre panier ne sont pas disponibles',
                'invalid_items' => $invalidItems
            ], 400);
        }

        // Calculer les totaux
        $sousTotal = $cart->total();
        $fraisLivraison = 30.00; // À adapter selon votre logique de frais de livraison
        $total = $sousTotal + $fraisLivraison;

        return response()->json([
            'success' => true,
            'message' => 'Panier validé avec succès',
            'data' => [
                'cart_items' => $cart->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'product_name' => $item->produit->nom,
                        'quantity' => $item->quantite,
                        'unit_price' => $item->prix_unitaire,
                        'subtotal' => $item->sousTotal()
                    ];
                }),
                'subtotal' => $sousTotal,
                'shipping_fee' => $fraisLivraison,
                'total' => $total
            ]
        ]);
    }

   
    public function createOrder(Request $request)
    {
        // Valider les données
        $validator = Validator::make($request->all(), [
            'adresse_livraison' => 'required|string',
            'ville_livraison' => 'required|string',
            'code_postal_livraison' => 'required|string',
            'pays_livraison' => 'required|string',
            'telephone_livraison' => 'required|string',
            'methode_paiement' => 'required|in:carte,paypal,virement,a_la_livraison',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], 422);
        }

        // L'utilisateur doit être connecté
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour passer une commande'
            ], 401);
        }

        try {
            DB::beginTransaction();

            // Récupérer le panier
            $cart = Cart::where('user_id', Auth::id())->with('items.produit')->first();

            // Vérifier si le panier existe et n'est pas vide
            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre panier est vide'
                ], 400);
            }

            // Vérifier chaque article du panier et ajuster les stocks
            foreach ($cart->items as $item) {
                $product = $item->produit;
                
                // Vérifier si le produit est disponible
                if (!$product->disponible) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Le produit '{$product->nom}' n'est plus disponible"
                    ], 400);
                }
                
                // Vérifier le stock
                if ($product->stock < $item->quantite) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuffisant pour '{$product->nom}'. Disponible: {$product->stock}, Demandé: {$item->quantite}"
                    ], 400);
                }
                
                // Mettre à jour le stock
                $product->update([
                    'stock' => $product->stock - $item->quantite
                ]);
            }

            // Calculer les totaux
            $sousTotal = $cart->total();
            $fraisLivraison = 30.00; // À adapter selon votre logique
            $total = $sousTotal + $fraisLivraison;

            // Générer un numéro de commande unique
            $numeroCommande = 'TS-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Créer la commande
            $commande = Commande::create([
                'numero_commande' => $numeroCommande,
                'user_id' => Auth::id(),
                'montant_total' => $total,
                'frais_livraison' => $fraisLivraison,
                'remise' => 0, // À adapter si vous avez des réductions
                'statut' => 'en_attente',
                'adresse_livraison' => $request->adresse_livraison,
                'ville_livraison' => $request->ville_livraison,
                'code_postal_livraison' => $request->code_postal_livraison,
                'pays_livraison' => $request->pays_livraison,
                'telephone_livraison' => $request->telephone_livraison,
                'notes' => $request->notes,
                'methode_paiement' => $request->methode_paiement,
                'paiement_confirme' => $request->methode_paiement === 'a_la_livraison',
            ]);

            foreach ($cart->items as $item) {
                LigneCommande::create([
                    'commande_id' => $commande->id,
                    'produit_id' => $item->produit_id,
                    'nom_produit' => $item->produit->nom,
                    'quantite' => $item->quantite,
                    'prix_unitaire' => $item->prix_unitaire,
                    'total' => $item->quantite * $item->prix_unitaire 
                ]);
            }

            $cart->items()->delete();

            DB::commit();


            return response()->json([
                'success' => true,
                'message' => 'Commande créée avec succès',
                'data' => [
                    'order_id' => $commande->id,
                    'order_number' => $commande->numero_commande,
                    'total' => $commande->montant_total,
                    'status' => $commande->statut,
                    'payment_method' => $commande->methode_paiement,
                    'payment_confirmed' => $commande->paiement_confirme
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la commande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function confirmPayment(Request $request, $orderId)
    {
        try {
            $commande = Commande::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Vérifier si le paiement est déjà confirmé
            if ($commande->paiement_confirme) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le paiement de cette commande est déjà confirmé'
                ], 400);
            }

            // Simuler la confirmation du paiement
            $commande->update([
                'paiement_confirme' => true,
                'date_paiement' => now(),
                'reference_paiement' => 'PAY-' . strtoupper(Str::random(10)),
                'statut' => 'confirmee'
            ]);

            // TODO: Envoyer un email de confirmation de paiement

            return response()->json([
                'success' => true,
                'message' => 'Paiement confirmé avec succès',
                'data' => [
                    'order_number' => $commande->numero_commande,
                    'status' => $commande->statut,
                    'payment_reference' => $commande->reference_paiement,
                    'payment_date' => $commande->date_paiement
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la confirmation du paiement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
    public function userOrders()
    {
        $commandes = Commande::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $commandes->map(function($commande) {
                return [
                    'id' => $commande->id,
                    'order_number' => $commande->numero_commande,
                    'date' => $commande->created_at->format('Y-m-d H:i:s'),
                    'status' => $commande->statut,
                    'total' => $commande->montant_total,
                    'payment_method' => $commande->methode_paiement,
                    'payment_confirmed' => $commande->paiement_confirme
                ];
            })
        ]);
    }

    
    public function orderDetails($orderId)
    {
        try {
            $commande = Commande::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->with('ligneCommandes.produit')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $commande->id,
                    'order_number' => $commande->numero_commande,
                    'date' => $commande->created_at->format('Y-m-d H:i:s'),
                    'status' => $commande->statut,
                    'subtotal' => $commande->montant_total - $commande->frais_livraison,
                    'shipping_fee' => $commande->frais_livraison,
                    'discount' => $commande->remise,
                    'total' => $commande->montant_total,
                    'payment_method' => $commande->methode_paiement,
                    'payment_confirmed' => $commande->paiement_confirme,
                    'payment_date' => $commande->date_paiement ? $commande->date_paiement->format('Y-m-d H:i:s') : null,
                    'payment_reference' => $commande->reference_paiement,
                    'shipping_address' => [
                        'address' => $commande->adresse_livraison,
                        'city' => $commande->ville_livraison,
                        'postal_code' => $commande->code_postal_livraison,
                        'country' => $commande->pays_livraison,
                        'phone' => $commande->telephone_livraison
                    ],
                    'notes' => $commande->notes,
                    'items' => $commande->ligneCommandes->map(function($ligne) {
                        return [
                            'id' => $ligne->id,
                            'product_id' => $ligne->produit_id,
                            'product_name' => $ligne->nom_produit,
                            'quantity' => $ligne->quantite,
                            'unit_price' => $ligne->prix_unitaire,
                            'subtotal' => $ligne->quantite * $ligne->prix_unitaire
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

    
}