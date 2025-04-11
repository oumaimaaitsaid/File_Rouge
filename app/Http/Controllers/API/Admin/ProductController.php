<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\ImageProduit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
 public function index(Request $request){
    $query =Produit::with(['categorie','imagePrincipale']);
    if($request->has('category_id') && !empty($request->category_id)){
        $query->where('category_id',$request->category_id);
    }
    if($request->has('available') && $request->available !== null){
        $query->where('disponible' ,$request->available === 'true' || $request->available === '1');
    }

    if($request->has('stock') && $request->stock === 'out'){
        $query->where('stock',0);
    }
    elseif($request->has('stock') && $request->stock === 'low'){
        $query->where('stock','>',0)->where('stock','<=' ,5);
    }
    if($request->has('search' )&& !empty($request->search)){
        $search =$request->search;
        $query->where(function($q) use ($search){
            $q->where('nom','LIKE', "%{$search}%")
            ->orWhere('description','LIKE',"%{$search}%");
        });
    }
    $sortField =$request->get('sort_field','created_at');
    $sortDirection =$request->get('sort_direction','desc');
    $query->orderBy($sortField,$sortDirection);

    $perPage =$request->get('per_page',10);
    $products=$query->paginate($perPage);

    $result =$products->map(function($product){
        return [
            'id' =>$product->id,
            'name' =>$product->nom,
            'slug' =>$product->slug,
            'price' =>$product->prix,
            'promotional_price' =>$product->prix_promo,
            'stock' =>$product->stock,
            'available' =>(bool) $product->disponible,
            'featured' =>(bool) $product->featured,
            'category' =>[
                'id' =>$product->categorie->id,
                'name' =>$product->categorie->nom,
            ],
            'main_image' =>$product->imagePrincipale ? asset('storage/' .$product->imagePrincipale->chemin) :null,
            'created_at' =>$product->created_at->format('Y-m-d H:i:s'),
            'updated_at' =>$product->updated_at->format('Y-m-d H:i:s'),

        ];
    });
    return response()->json([
        'success' =>true,
        'data' =>$result,
        'pagination' =>[
            'current_page' =>$products->currentPage(),
            'last_page' =>$products->lastPage(),
            'per_page' =>$products->perPage(),
            'total' =>$products->total(),
            'from' =>$products->firstItem(),
            'to' =>$products->lastItem(),
        ]
        ]);

 }

 //create new product
 public function store(Request $request){
    $validator =Validator::make($request->all(),
    [
        'name'=>'required|string|max:255',
        'description'=>'required|string',
        'ingredients'=>'required|string',
        'price'=>'required|numeric|min:0',
        'promotional_price'=>'nullable|numeric|min:0|lt:price',
        'stock'=>'required|integer|min:0',
        'category_id'=>'required|exists:category,id',
        'available'=>'boolean',
        'featured'=>'boolean',  
    ]);
    if($validator->fails()){
        return response()->json([
            'success' =>false,
            'message'=>'Validation failed',
            'errors' =>$validator->errors(),
        ],422);
    }

    try{
        DB::beginTransaction();
        $slug =Str::slug($request->name);

        $product =Produit::create([
            'nom' =>$request->name,
            'slug' =>$slug,
            'description' =>$request->description,
            'ingredients' =>$request->ingredients,
            'prix' =>$request->price,
            'prix_promo' =>$request->promotional_price,
            'stock' =>$request->stock,
            'category_id' =>$request->category_id,
            'disponible' => $request->has('available') ? $request->available : true,
            'featured' => $request->has('featured') ? $request->featured : false,
    
        ]);
        DB::commit();
        return response()->json([
            'success'=>true,
            'message'=>'Product created successfully',
            'data' =>[
                'id' =>$product->id,
                'name' =>$product->nom,
                'slug' =>$product->slug,
                'description' =>$product->description,
                'ingredients' =>$product->ingredients,
                'price' =>$product->prix,
                'promotional_price' =>$product->prix_promo,
                'stock' =>$product->stock,
                'category_id' =>$product->category_id,
                'available' =>(bool) $product->disponible,
                'featured' =>(bool) $product->featured,
                'created_at' =>$product->created_at->format('Y-m-d H:i:s'),
                ]
            ],201);
    }
    catch (\Exception $e){
        DB::rollBack();
        return response()->json([
            'success' =>false,
            'message' =>'Failed to create product',
            'error' =>$e->getMessage(),
        ],500);
    }
}
     //modifier un produit
     public function update(Request $request ,$id)
     {
        $product =Produit::findOrFail($id);
        $validator =Validator::make($request->all(),[
            'name' =>['required','string','max:255' ,Rule::unique('produits','nom')->ignore($product->id)],
            'description' =>'required|string',
            'ingredients' =>'required|string',
            'price' =>'required|numeric|min:0',
            'promotional_price' =>'nullable|numeric|min:0|lt:price',
            'stock' =>'required|integer|min:0',
            'category_id' =>'required|exists:category,id',
            'available' =>'boolean',
            'featured' =>'boolean',

        ]);
        if($validator->fails()){
            return response()->json([
                'success' =>false,
                'message' =>'Validation failed',
                'errors' =>$validator->errors(),
            ],422);
        }
            try{
                DB::beginTransaction();
                //changer le slug si le nom changed
                if($product->nom !=$request->name){
                    $slug = Str::slug($request->name);
                    $product->slug =$slug;
                }

                $product->update([
                    'nom' =>$request->name,
                    'description' =>$request->description,
                    'ingredients' =>$request->ingredients,
                    'prix' =>$request->price,
                    'prix_promo' =>$request->promotional_price,
                    'stock' =>$request->stock,
                    'category_id' =>$request->category_id,
                    'disponible' =>$request->has('available') ? $request->available : $product->disponible,
                    'featured' =>$request->has('featured') ? $request->featured : $product->featured,
                ]);
                DB::commit();
                return response()->json([
                    'success' =>true,
                    'message' =>'Product updated successfully',
                    'data' =>[
                        'id' =>$product->id,
                        'name' =>$product->nom,
                        'slug' =>$product->slug,
                        'description' =>$product->description,
                        'ingredients' =>$product->ingredients,
                        'price' =>$product->prix,
                        'promotional_price' =>$product->prix_promo,
                        'stock' =>$product->stock,
                        'category_id' =>$product->category_id,
                        'available' =>(bool) $product->disponible,
                        'featured' =>(bool) $product->featured,
                        'updated_at' =>$product->updated_at->format('Y-m-d H:i:s'),
                    ]
                    ]);
            }
            catch(\Exception $e){
                DB::rollBack();
                return response()->json([
                    'success' =>false,
                    'message' =>'Failed to update product',
                    'error' =>$e->getMessage(),
                ],500);
            }
        }

     
     //supprimer un produit
     public function destroy($id){
       try{ $product =Produit::findOrFail($id);
        if($product->lignesCommandes()->count() > 0){
            return response()->json([
                'success' =>false,
                'message' =>'Product cannot be deleted because it is associated with one or more orders',
            ],400);
            
        }
        //suprimer l'image de le produit
        foreach($product->images as $image){
            Storage::disk('public')->delete($image->chemin);
            $image->delete();
        }
        //supprimer le produit
        $product->delete();
        return response()->json([
            'success' =>true,
            'message' =>'Product deleted successfully',
        ]);
     }
 
 
 catch(\Exception $e){
    return response()->json([
        'success' =>false,
        'message' =>'Failed to delete product',
        'error' =>$e->getMessage(),
    ],500);

 }
    }
    //télècharger les images d'un produit
  
    public function uploadImages(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'main_image_index' => 'nullable|integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $product = Produit::findOrFail($id);
            
            if (!$request->hasFile('images')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No images uploaded'
                ], 400);
            }
            
            DB::beginTransaction();
            
            $images = $request->file('images');
            $mainImageIndex = $request->input('main_image_index', 0);
            $ordre = $product->images()->max('ordre') + 1;
            $uploadedImages = [];
            
            foreach ($images as $index => $image) {
                $path = $image->store('produits', 'public');
                
                $productImage = ImageProduit::create([
                    'produit_id' => $product->id,
                    'chemin' => $path,
                    'principale' => ($index == $mainImageIndex),
                    'ordre' => $ordre++,
                ]);
                
                $uploadedImages[] = [
                    'id' => $productImage->id,
                    'url' => asset('storage/' . $productImage->chemin),
                    'is_main' => (bool) $productImage->principale,
                    'order' => $productImage->ordre,
                ];
            }
            
            if (isset($images[$mainImageIndex])) {
                $product->images()
                    ->where('id', '!=', $uploadedImages[$mainImageIndex]['id'])
                    ->update(['principale' => false]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Images uploaded successfully',
                'data' => $uploadedImages
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading images',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //supprimer une image d'un produit

    public function deleteImage($id, $imageId)
    {
        try {
            $product = Produit::findOrFail($id);
            $image = ImageProduit::where('produit_id', $id)->where('id', $imageId)->firstOrFail();
            
            if ($image->principale && $product->images()->count() > 1) {
                $newMainImage = $product->images()->where('id', '!=', $imageId)->first();
                $newMainImage->update(['principale' => true]);
            } 
            elseif ($image->principale && $product->images()->count() === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the only main image of the product'
                ], 400);
            }
            
            Storage::disk('public')->delete($image->chemin);
            
            $image->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the image',
                'error' => $e->getMessage()
            ], 500);
        }
   }
 }


