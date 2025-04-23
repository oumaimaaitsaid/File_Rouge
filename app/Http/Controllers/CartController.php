<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getOrCreateCart(Request $request)
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => null]
            );
            
            if ($request->cookie('cart_session_id')) {
                $sessionCart = Cart::where('session_id', $request->cookie('cart_session_id'))->first();
                
                if ($sessionCart && $sessionCart->id != $cart->id) {
                    foreach ($sessionCart->items as $item) {
                        $existingItem = $cart->items()->where('produit_id', $item->produit_id)->first();
                        
                        if ($existingItem) {
                            $existingItem->update([
                                'quantite' => $existingItem->quantite + $item->quantite
                            ]);
                        } else {
                            $cart->items()->create([
                                'produit_id' => $item->produit_id,
                                'quantite' => $item->quantite,
                                'prix_unitaire' => $item->prix_unitaire
                            ]);
                        }
                    }
                    
                    $sessionCart->delete();
                }
            }
            
            return $cart;
        }
        
        $sessionId = $request->cookie('cart_session_id');
        
        if (!$sessionId) {
            $sessionId = Str::uuid();
            $request->attributes->set('set_cart_cookie', $sessionId);
        }
        
        return Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['user_id' => null]
        );
    }
  
 

   
   

   
}