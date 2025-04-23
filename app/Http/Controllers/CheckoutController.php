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

    
   
   

    
  

    
}