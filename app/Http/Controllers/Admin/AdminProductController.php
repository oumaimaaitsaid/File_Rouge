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
    
    public function create()
    {
        $categories = Categorie::where('active', true)->get();
        return view('admin.products.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'prix' => 'required|numeric|min:0',
            'prix_promo' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'ingredients' => 'nullable|string',
            'poids' => 'nullable|numeric|min:0',
            'disponible' => 'boolean',
            'featured' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_principale' => 'nullable|integer'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $product = Produit::create([
                'nom' => $request->nom,
                'slug' => Str::slug($request->nom),
                'description' => $request->description,
                'category_id' => $request->category_id,
                'prix' => $request->prix,
                'prix_promo' => $request->prix_promo,
                'stock' => $request->stock,
                'ingredients' => $request->ingredients,
                'poids' => $request->poids,
                'disponible' => $request->has('disponible'),
                'featured' => $request->has('featured')
            ]);
            
            // Traitement des images
            if ($request->hasFile('images')) {
                $isPrincipalSet = false;
                
                foreach ($request->file('images') as $key => $image) {
                    $path = $image->store('produits', 'public');
                    
                    $isPrincipal = ($request->image_principale == $key);
                    if (!$isPrincipalSet && $isPrincipal) {
                        $isPrincipalSet = true;
                    }
                    
                    $product->images()->create([
                        'chemin' => $path,
                        'alt' => $request->nom,
                        'est_principale' => $isPrincipal
                    ]);
                }
                
                // Si aucune image principale n'a été définie, on définit la première
                if (!$isPrincipalSet && $product->images->count() > 0) {
                    $product->images()->first()->update(['est_principale' => true]);
                }
            }
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Produit créé avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du produit: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function edit($id)
    {
        $product = Produit::with('images', 'categorie')->findOrFail($id);
        $categories = Categorie::where('active', true)->get();
        
        return view('admin.products.edit', compact('product', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'prix' => 'required|numeric|min:0',
            'prix_promo' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'ingredients' => 'nullable|string',
            'poids' => 'nullable|numeric|min:0',
            'disponible' => 'boolean',
            'featured' => 'boolean',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_principale' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $product = Produit::findOrFail($id);
            
            $product->update([
                'nom' => $request->nom,
                'slug' => Str::slug($request->nom),
                'description' => $request->description,
                'category_id' => $request->category_id,
                'prix' => $request->prix,
                'prix_promo' => $request->prix_promo,
                'stock' => $request->stock,
                'ingredients' => $request->ingredients,
                'poids' => $request->poids,
                'disponible' => $request->has('disponible'),
                'featured' => $request->has('featured')
            ]);
            
            // Traitement des nouvelles images
            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $image) {
                    $path = $image->store('produits', 'public');
                    
                    $product->images()->create([
                        'chemin' => $path,
                        'alt' => $request->nom,
                        'est_principale' => false
                    ]);
                }
            }
            
            // Définir l'image principale
            if ($request->has('image_principale')) {
                // Réinitialiser toutes les images
                $product->images()->update(['est_principale' => false]);
                
                // Définir la nouvelle image principale
                $product->images()->where('id', $request->image_principale)->update(['est_principale' => true]);
            }
            
            // Supprimer les images sélectionnées
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $image = ImageProduit::find($imageId);
                    if ($image) {
                        Storage::disk('public')->delete($image->chemin);
                        $image->delete();
                    }
                }
            }
            
            return redirect()->route('admin.products.edit', $product->id)
                ->with('success', 'Produit mis à jour avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du produit: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $product = Produit::findOrFail($id);
            
            // Supprimer les images associées
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->chemin);
            }
            
            $product->images()->delete();
            $product->delete();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Produit supprimé avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du produit: ' . $e->getMessage());
        }
    }
}