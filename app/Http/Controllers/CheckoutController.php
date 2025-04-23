<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Promotion;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour passer une commande');
        }

        $cart = Cart::where('user_id', Auth::id())->with('items.produit')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide');
        }

        $invalidItems = [];
        foreach ($cart->items as $item) {
            $product = $item->produit;
            
            if (!$product->disponible) {
                $invalidItems[] = [
                    'id' => $product->id,
                    'name' => $product->nom,
                    'reason' => 'Ce produit n\'est plus disponible'
                ];
                continue;
            }
            
            if ($product->stock < $item->quantite) {
                $invalidItems[] = [
                    'id' => $product->id,
                    'name' => $product->nom,
                    'reason' => "Stock insuffisant. Disponible: {$product->stock}, Demandé: {$item->quantite}"
                ];
                continue;
            }
        }

        if (!empty($invalidItems)) {
            return redirect()->route('cart.index')
                ->with('error', 'Certains articles de votre panier ne sont pas disponibles')
                ->with('invalidItems', $invalidItems);
        }

        $sousTotal = $cart->total();
        $fraisLivraison = 30.00;
        $total = $sousTotal + $fraisLivraison;
        
        $promoCode = $request->session()->get('promo_code');
        $reduction = 0;
        
        if ($promoCode) {
            $promotion = Promotion::where('code', $promoCode)->first();
            if ($promotion) {
                $validation = $promotion->estValide($sousTotal, Auth::id());
                
                if ($validation['valide']) {
                    if ($promotion->type === 'livraison_gratuite') {
                        $fraisLivraison = 0;
                    } else {
                        $reduction = $promotion->calculerReduction($sousTotal);
                    }
                    
                    $total = $sousTotal + $fraisLivraison - $reduction;
                }
            }
        }

        return view('checkout.index', [
            'cartItems' => $cart->items,
            'sousTotal' => $sousTotal,
            'fraisLivraison' => $fraisLivraison,
            'reduction' => $reduction,
            'total' => $total,
            'promoCode' => $promoCode
        ]);
    }

    public function store(Request $request)
    {
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour passer une commande');
        }

        try {
            DB::beginTransaction();

            $cart = Cart::where('user_id', Auth::id())->with('items.produit')->first();

            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Votre panier est vide');
            }

            foreach ($cart->items as $item) {
                $product = $item->produit;
                
                if (!$product->disponible) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', "Le produit '{$product->nom}' n'est plus disponible");
                }
                
                if ($product->stock < $item->quantite) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', "Stock insuffisant pour '{$product->nom}'. Disponible: {$product->stock}, Demandé: {$item->quantite}");
                }
                
                $product->update([
                    'stock' => $product->stock - $item->quantite
                ]);
            }

            $sousTotal = $cart->total();
            $fraisLivraison = 30.00; 
            $total = $sousTotal + $fraisLivraison;
            $reduction = 0;
            $promoCode = $request->session()->get('promo_code');

            $numeroCommande = 'TS-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $commande = Commande::create([
                'numero_commande' => $numeroCommande,
                'user_id' => Auth::id(),
                'montant_total' => $total,
                'frais_livraison' => $fraisLivraison,
                'remise' => $reduction,
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

            if ($promoCode) {
                $promotion = Promotion::where('code', $promoCode)->first();
                
                if ($promotion && $promotion->estValide($sousTotal, Auth::id())['valide']) {
                    if ($promotion->type === 'livraison_gratuite') {
                        $fraisLivraison = 0;
                    } else {
                        $reduction = $promotion->calculerReduction($sousTotal);
                    }
                    
                    $total = $sousTotal + $fraisLivraison - $reduction;
                    
                    $commande->remise = $reduction;
                    $commande->code_promo = $promotion->code;
                    $commande->montant_total = $total;
                    $commande->frais_livraison = $fraisLivraison;
                    $commande->save();
                    
                    $promotion->enregistrerUtilisation(Auth::id(), $commande->id);
                    
                    $request->session()->forget('promo_code');
                }
            }

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

    if ($request->methode_paiement === 'carte') {
    return redirect()->route('payment.card', ['orderId' => $commande->id]);
   }

    return redirect()->route('checkout.success', ['orderId' => $commande->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la commande: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function success(Request $request, $orderId)
    {
        try {
            $commande = Commande::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->with('ligneCommandes.produit')
                ->firstOrFail();
                
            return view('checkout.success', compact('commande'));
                
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Commande non trouvée');
        }
    }

    public function confirmPayment(Request $request, $orderId)
    {
        try {
            $commande = Commande::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($commande->paiement_confirme) {
                return redirect()->back()
                    ->with('error', 'Le paiement de cette commande est déjà confirmé');
            }

            $commande->update([
                'paiement_confirme' => true,
                'date_paiement' => now(),
                'reference_paiement' => 'PAY-' . strtoupper(Str::random(10)),
                'statut' => 'confirmee'
            ]);


            return redirect()->route('orders.show', $commande->id)
                ->with('success', 'Paiement confirmé avec succès');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la confirmation du paiement: ' . $e->getMessage());
        }
    }

    public function userOrders()
    {
        $commandes = Commande::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('commandes'));
    }

    public function orderDetails($orderId)
    {
        try {
            $commande = Commande::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->with('ligneCommandes.produit')
                ->firstOrFail();

            return view('orders.show', compact('commande'));

        } catch (\Exception $e) {
            return redirect()->route('orders.index')
                ->with('error', 'Commande non trouvée');
        }
    }

    
}