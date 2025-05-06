<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    
    
    public function index()
    {
        $categories = Categorie::withCount('produits')->get();
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $data = [
                'nom' => $request->nom,
                'slug' => Str::slug($request->nom),
                'description' => $request->description,
                'active' => $request->has('active')
            ];
            
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('categories', 'public');
            }
            
            Categorie::create($data);
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie créée avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la catégorie: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function edit($id)
    {
        $category = Categorie::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }
    
    public function update(Request $request, $id)
    {
        $category = Categorie::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:categories,nom,'.$id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $data = [
                'nom' => $request->nom,
                'slug' => Str::slug($request->nom),
                'description' => $request->description,
                'active' => $request->has('active')
            ];
            
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                
                $data['image'] = $request->file('image')->store('categories', 'public');
            }
            
            $category->update($data);
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie mise à jour avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de la catégorie: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $category = Categorie::findOrFail($id);
            
            // Vérifier si la catégorie a des produits
            if ($category->produits()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits');
            }
            
            // Supprimer l'image si elle existe
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $category->delete();
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie supprimée avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la catégorie: ' . $e->getMessage());
        }
    }
}