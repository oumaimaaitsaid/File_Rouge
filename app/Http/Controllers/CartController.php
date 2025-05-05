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
    public function getCartInfo(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        
        $cartItems = $cart->items()->with('produit.imagePrincipale')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->produit_id,
                'name' => $item->produit->nom,
                'quantity' => $item->quantite,
                'price' => $item->prix_unitaire,
                'subtotal' => $item->sousTotal(),
                'image' => $item->produit->imagePrincipale ? asset('storage/' . $item->produit->imagePrincipale->chemin) : asset('images/placeholder.jpg'),
            ];
        });
        
        return response()->json([
            'items' => $cartItems,
            'total' => $cart->total(),
            'itemCount' => $cart->itemCount()
        ]);
    }
    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        
        $cartItems = $cart->items()->with('produit.imagePrincipale')->get();
        
        return view('cart.index', [
            'cartItems' => $cartItems,
            'total' => $cart->total(),
            'itemCount' => $cart->itemCount()
        ]);
    }

    public function addItem(Request $request)
{
    $validator = Validator::make($request->all(), [
        'product_id' => 'required|exists:produits,id',
        'quantity' => 'required|integer|min:1'
    ]);
    
    if ($validator->fails()) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }
    
    try {
        $cart = $this->getOrCreateCart($request);
        $product = Produit::findOrFail($request->product_id);
        
        if (!$product->disponible || $product->stock < $request->quantity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le produit n\'est pas disponible dans la quantité demandée'
                ], 400);
            }
            
            return redirect()->back()
                ->with('error', 'Le produit n\'est pas disponible dans la quantité demandée');
        }
        
        $cartItem = $cart->items()->where('produit_id', $product->id)->first();
        
        if ($cartItem) {
            $cartItem->update([
                'quantite' => $cartItem->quantite + $request->quantity,
                'prix_unitaire' => $product->getPrixActuel() 
            ]);
        } else {
            $cartItem = $cart->items()->create([
                'produit_id' => $product->id,
                'quantite' => $request->quantity,
                'prix_unitaire' => $product->getPrixActuel()
            ]);
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier',
                'data' => [
                    'itemCount' => $cart->itemCount(),
                    'total' => $cart->total()
                ]
            ]);
        }
        
        return redirect()->route('cart.index')
            ->with('success', 'Produit ajouté au panier');
        
    } catch (\Exception $e) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du produit au panier',
                'error' => $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'Erreur lors de l\'ajout du produit au panier: ' . $e->getMessage());
    }
}

     
public function updateItem(Request $request)
{
    $validator = Validator::make($request->all(), [
        'cart_item_id' => 'required|exists:cart_items,id',
        'quantity' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        $cartItem = CartItem::findOrFail($request->cart_item_id);
        
        if (Auth::check() && Auth::id() !== $cartItem->cart->user_id) {
            throw new \Exception('Unauthorized');
        }
        
        $product = Produit::findOrFail($cartItem->produit_id);
        
        if ($request->quantity > $product->stock) {
            $message = "Quantité non disponible. Stock disponible: {$product->stock}";
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ]);
            }
            
            return redirect()->back()->with('error', $message);
        }
        
        $cartItem->update([
            'quantite' => $request->quantity
        ]);
        
        $cart = $cartItem->cart;
        $cartItems = $cart->items()->with('produit')->get();
        $subtotal = $cart->total();
        
        $cartData = [
            'subtotal' => $subtotal,
            'total' => $subtotal, 
            'items' => $cartItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->produit_id,
                    'name' => $item->produit->nom,
                    'price' => (float)$item->prix_unitaire,
                    'quantity' => $item->quantite,
                    'total' => (float)($item->prix_unitaire * $item->quantite),
                    'image' => $item->produit->imagePrincipale ? 
                            asset('storage/' . $item->produit->imagePrincipale->chemin) : 
                            asset('images/placeholder.jpg')
                ];
            })
        ];
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Panier mis à jour avec succès',
                'cart' => $cartData
            ]);
        }
        
        return redirect()->back()->with('success', 'Panier mis à jour avec succès');
        
    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du panier: ' . $e->getMessage()
            ]);
        }
        
        return redirect()->back()->with('error', 'Erreur lors de la mise à jour du panier: ' . $e->getMessage());
    }
}


    public function removeItem(Request $request, $id)
    {
        try {
            $cart = $this->getOrCreateCart($request);
            $cartItem = $cart->items()->findOrFail($id);
            $cartItem->delete();
            
            return redirect()->route('cart.index')
                ->with('success', 'Article supprimé du panier');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'article: ' . $e->getMessage());
        }
    }

    public function clear(Request $request)
    {
        try {
            $cart = $this->getOrCreateCart($request);
            $cart->items()->delete();
            
            return redirect()->route('cart.index')
                ->with('success', 'Panier vidé avec succès');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du panier: ' . $e->getMessage());
        }
    }
}