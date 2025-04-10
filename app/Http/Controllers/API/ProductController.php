<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
        $query= Produit::where('disponible',true)
        ->with(['categorie','imagePrincipale']);
   
        //filtrage by category

        if($request->has('category') && !empty($request->category)){
            $query->whereHas('categorie',function($q) use ($request){
                $q->where('slug', $request->category);
            });
        }

        //order avec les produits

        if($request->has('sort')){
            switch($request->sort){
                case 'price_asc':
                    $query->orderByRaw('COALESCE(prix_promo,prix) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('COALESCE(prix_promo,prix) DESC');
                    break;
                case 'name_asc':
                    $query->orderBy('nom','asc');   
                    break;
                case 'name_desc':
                    $query->orderBy('nom','desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at','desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at','asc');
                    break;
                case 'popular':
                    $query->withCount(['avis']=> function($q){
                        $q->where('approuve',true);
                    })
                    ->orderByDesc('avis_count','desc');
                    break;
                    default:
                    $query->orderBy('created_at','desc');
            }
            else{
                $query->orderBy('created_at','desc');
            }
            //recherche par nom ou description
            if($request->has('search') && !empty($request->search)){
                $query->where(function($q) use ($search){
                    $q->where('nom','like',"%{$search}%")
                    ->orWhere('description','like',"%{$search}%")
                    ->orWhere('ingredients','like',"%{$search}%");
                });
            }
            //pagination
            $perPage =$request->get('per_page',10);
            $products= $query->paginate($perPage);

            //pour transformer data à api
            $result =$products->map(function($product){
                return[
                    'id' =>$product->id,
                    'name' =>$producct->nom,
                    'slug' =>$product->slug,
                    'description' =>$product->description,
                    'price' =>$product->prix,
                    'promotional_price' =>$product->prix_promo,
                    'main_image' =>$product->imagePrincipale ? asset('storage/' .$product->imagePrincipale->chemin) :null,
                    'category' =>[
                        'id' =>$product->categorie->id,
                        'name' =>$product->categorie->nom,
                        'slug' =>$product->categorie->slug,
                    ],
                    'average_rating' =>$product->noteMoyenne(),
                    'review_count' =>$product->avis()->where('approuve',true)->count(),
                    'stock' =>$product->stock,
                    'available'=>(bool) $product->disponible
                    'featured'=>(bool) $product->featured,
                ];
            });
            return response()->json([
                'success' =>true,
                'data' =>$result,
                'pagination' =>[
                    'total' =>$products->total(),
                    'per_page' =>$products->perPage(),
                    'current_page' =>$products->currentPage(),
                    'last_page' =>$products->lastPage(),
                    'from' =>$products->firstItem(),
                    'to' =>$products->lastItem(),
                ]
                ]);
        }

    }
}
