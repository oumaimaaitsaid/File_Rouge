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

   
    

   

    
    
    
   

    
}