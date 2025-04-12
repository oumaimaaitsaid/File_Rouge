<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;


class CategoryController extends Controller
{
    //pour afficher tout les categories qui sont active
    public function index(){
        $categories =Categorie::where('active',true)
        ->orderBy('nom')
        ->get()
        ->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->nom,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $category->image ? asset('storage/' . $category->image) : null,
                'product_count' => $category->produits()->where('disponible', true)->count()
            ];
        });
         return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    
    }
    //pour afficher une categorie

    public function show($slug)
    {
        $category = Categorie::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();
            
        $products = $category->produits()
            ->where('disponible', true)
            ->with('imagePrincipale')
            ->paginate(12);
            
        $productList = $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->nom,
                'slug' => $product->slug,
                'price' => $product->prix,
                'promotional_price' => $product->prix_promo,
                'main_image' => $product->imagePrincipale ? asset('storage/' . $product->imagePrincipale->chemin) : null,
                'average_rating' => $product->noteMoyenne(),
                'review_count' => $product->avis()->where('approuve', true)->count(),
            ];
        });
            
        $result = [
            'id' => $category->id,
            'name' => $category->nom,
            'slug' => $category->slug,
            'description' => $category->description,
            'image' => $category->image ? asset('storage/' . $category->image) : null,
            'products' => $productList,
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ]
        ];
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

}
