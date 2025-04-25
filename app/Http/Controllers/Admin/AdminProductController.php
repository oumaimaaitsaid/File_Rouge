<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\ImageProduit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
   
    
    public function index(Request $request)
    {
        $query = Produit::with('categorie');
        
        // Filtrage
        if ($request->has('search')) {
            $query->where('nom', 'like', "%{$request->search}%");
        }
        
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('status')) {
            $query->where('disponible', $request->status === 'available');
        }
        
        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $products = $query->paginate(15);
        $categories = Categorie::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }
    
   
   
   
    
   
    
}