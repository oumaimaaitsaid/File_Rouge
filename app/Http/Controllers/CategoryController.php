<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;

class CategoryController extends Controller
{
    // Pour afficher toutes les catégories qui sont actives
    public function index()
    {
        $categories = Categorie::where('active', true)
            ->orderBy('nom')
            ->get();

        return view('categories.index', compact('categories'));
    }

    // Pour afficher une catégorie spécifique et ses produits
    public function show($slug)
    {
        $category = Categorie::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();
            
        $products = $category->produits()
            ->where('disponible', true)
            ->with('imagePrincipale')
            ->paginate(12);
            
        return view('categories.show', compact('category', 'products'));
    }
}