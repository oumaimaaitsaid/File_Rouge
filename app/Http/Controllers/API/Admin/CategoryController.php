<?php
// app/Http/Controllers/API/Admin/CategoryController.php
namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Categorie::query();
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('active') && $request->active !== null) {
            $query->where('active', $request->active === 'true' || $request->active === '1');
        }
        
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $perPage = $request->get('per_page', 10);
        $categories = $query->paginate($perPage);
        
        $result = $categories->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->nom,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $category->image ? asset('storage/' . $category->image) : null,
                'active' => (bool) $category->active,
                'product_count' => $category->produits()->count(),
                'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $result,
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
            ]
        ]);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,nom',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $slug = Str::slug($request->name);
            
            $category = new Categorie();
            $category->nom = $request->name;
            $category->slug = $slug;
            $category->description = $request->description;
            $category->active = $request->has('active') ? $request->active : true;
            
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('categories', 'public');
                $category->image = $path;
            }
            
            $category->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Catégorie créée avec succès',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->nom,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image' => $category->image ? asset('storage/' . $category->image) : null,
                    'active' => (bool) $category->active,
                ]
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la catégorie',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $category = Categorie::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $category->id,
                    'name' => $category->nom,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image' => $category->image ? asset('storage/' . $category->image) : null,
                    'active' => (bool) $category->active,
                    'product_count' => $category->produits()->count(),
                    'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    public function update(Request $request, $id)
    {
        $category = Categorie::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'nom')->ignore($category->id)],
            'description' => 'nullable|string',
            'image' => Rule::in([true,false,'true','false',1,0,'1','0']),
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            if ($category->nom != $request->name) {
                $category->slug = Str::slug($request->name);
            }
            
            $category->nom = $request->name;
            $category->description = $request->description;
            $category->active = $request->has('active') ? $request->active : $category->active;
            
            if ($request->hasFile('image')) {
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                
                $path = $request->file('image')->store('categories', 'public');
                $category->image = $path;
            }
            
            $category->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Catégorie mise à jour avec succès',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->nom,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image' => $category->image ? asset('storage/' . $category->image) : null,
                    'active' => (bool) $category->active,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la catégorie',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $category = Categorie::findOrFail($id);
            
            if ($category->produits()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer la catégorie car elle contient des produits'
                ], 400);
            }
            
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $category->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la catégorie',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}