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

}
