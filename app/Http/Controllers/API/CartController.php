<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        
        $cartItems = $cart->items()->with('produit.imagePrincipale')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'product' => [
                    'id' => $item->produit->id,
                    'name' => $item->produit->nom,
                    'slug' => $item->produit->slug,
                    'price' => $item->prix_unitaire,
                    'image' => $item->produit->imagePrincipale ? asset('storage/' . $item->produit->imagePrincipale->chemin) : null
                ],
                'quantity' => $item->quantite,
                'unit_price' => $item->prix_unitaire,
                'subtotal' => $item->sousTotal()
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cartItems,
                'total' => $cart->total(),
                'item_count' => $cart->itemCount()
            ]
        ]);
    }

    public function addItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:produits,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $cart = $this->getOrCreateCart($request);
            $product = Produit::findOrFail($request->product_id);
            
            if (!$product->disponible || $product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le produit n\'est pas disponible dans la quantité demandée'
                ], 400);
            }
            
            $cartItem = $cart->items()->where('produit_id', $product->id)->first();
            
            if ($cartItem) {
                $cartItem->update([
                    'quantite' => $cartItem->quantite + $request->quantity,
                    'prix_unitaire' => $product->getPrixActuel() // Utiliser le prix actuel (normal ou promo)
                ]);
            } else {
                $cartItem = $cart->items()->create([
                    'produit_id' => $product->id,
                    'quantite' => $request->quantity,
                    'prix_unitaire' => $product->getPrixActuel()
                ]);
            }
            
            return $this->index($request);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du produit au panier',
                'error' => $e->getMessage()
            ], 500);
        }
    }

public function updateItem(Request $request)
{
    $validator = Validator::make($request->all(), [
        'cart_item_id' => 'required|exists:cart_items,id',
        'quantity' => 'required|integer|min:1'
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }
    
    try {
        $cartItem = CartItem::findOrFail($request->cart_item_id);
        
        $cart = $this->getOrCreateCart($request);
        
        if ($cartItem->cart_id != $cart->id) {
            if (!Auth::check() || !Cart::where('id', $cartItem->cart_id)->where('user_id', Auth::id())->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à modifier cet élément'
                ], 403);
            }
        }
        
        if ($cartItem->produit->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuffisant'
            ], 400);
        }
        
        $cartItem->update([
            'quantite' => $request->quantity
        ]);
        
        return $this->index($request);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour de l\'article',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function removeItem(Request $request, $id)
    {
        try {
            $cart = $this->getOrCreateCart($request);
            $cartItem = $cart->items()->findOrFail($id);
            $cartItem->delete();
            
            return $this->index($request);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function clear(Request $request)
    {
        try {
            $cart = $this->getOrCreateCart($request);
            $cart->items()->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Panier vidé avec succès',
                'data' => [
                    'items' => [],
                    'total' => 0,
                    'item_count' => 0
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du panier',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}