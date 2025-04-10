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
 public function index(){
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
        'category_id'=>'required|exists:categories,id',
        'available'=>'boolean',
        'featured'=>'boolean',  
    ]);
    if($validator->fails()){
        return response()->json([
            'success' =>false,
            'message'=>'Validation failed',
            'errors' =>$validator->errors(),
        ],422)
    }

    try{
        DB:beginTransaction();
        $slug =Str::slug($request->name);

        $product =Produit::create([
            'nom' =>$request->name,
            'slug' =>$slug,
            'description' =>$request->description,
            'ingredients' =>$request->ingredients,
            'prix' =>$request->price,
            'prix_promo' =>$request->promotional_price,
            'stock' =>$request->stock,
            'categorie_id' =>$request->category_id,
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
                'category_id' =>$product->categorie_id,
                'available' =>(bool) $product->disponible,
                'featured' =>(bool) $product->featured,
                'created_at' =>$product->created_at->format('Y-m-d H:i:s'),
                ]
            ],201)
    }catch (\Exception $e){
        DB::rollBack();
        return response()->json([
            'success' =>false,
            'message' =>'Failed to create product',
            'error' =>$e->getMessage(),
        ],500);
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
            'category_id' =>'required|exists:categories,id',
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
     }
    