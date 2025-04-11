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
                    $query->withCount(['avis'=> function($q){
                        $q->where('approuve',true);
                    }])
                    ->orderByDesc('avis_count','desc');
                    break;
            }
             }     else{
                $query->orderBy('created_at','desc');
            }
            //recherche par nom ou description
            if($request->has('search') && !empty($request->search)){
                $query->where(function($q) use ($request){
                    $q->where('nom','like',"%{$request->search}%")
                    ->orWhere('description','like',"%{$request->search}%")
                    ->orWhere('ingredients','like',"%{$request->search}%");
                });
            }
            //pagination
            $perPage =$request->get('per_page',10);
            $products= $query->paginate($perPage);

            //pour transformer data à api
            $result =$products->map(function($product){
                return[
                    'id' =>$product->id,
                    'name' =>$product->nom,
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
                    'available'=>(bool) $product->disponible,
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
         //display spefic produit
         public function show($slug){
             $product  = Produit::where('slug',$slug)->with(['images','categorie','avis' => function($query)
         {
             $query->where('approuve',true)->with('user');}])->firstOrFail();
        
             $result =[
                'id' =>$product->id,
                'name' =>$product->nom,
                'slug'=>$product->slug,
                'description' =>$product->description,
                'ingredients' =>$product->ingredients,
                'price' =>$product->prix,
                'promotional_price' =>$product->prix_promo,
                'image' => $product->images->map(function($image){
                    return[
                        'id' => $image->id,
                        'url' =>asset('storage/'.$image->chemin),
                        'is_main' =>(bool) $image->principale,
                    ];
                }),
                'category' =>[
                    'id' =>$product->categorie->id,
                    'name' =>$product->categorie->nom,
                    'slug' =>$product->categorie->slug,
                ],
                'average_rating' =>$product->noteMoyenne(),
                'reviews' =>$product->avis->map(function($review){
                    return [
                        'id' =>$review->id,
                        'user' =>$review->user->name .' '.$review->user->prenom,
                        'rating' =>$review->note,
                        'comment' =>$review->commentaire,
                        'date' =>$review->created_at->format('Y-m-d'),
                    ];
                }),
                'stock' =>$product->stock,
                'available'=>(bool) $product->disponible,
                'featured'=>(bool) $product->featured,
            ];
            //prend des produit (en méme category)
            $relatedProducts=Produit::where('category_id',$product->categorie_id)
            ->where('id','!=',$product->id)
            ->where('disponible',true)
            ->with(['imagePrincipale'])
            ->take(4)
            ->get()
            ->map(function($product){
                return[
                    'id' =>$product->id,
                    'name' =>$product->nom,
                    'slug' =>$product->slug,
                    'price' =>$product->prix,
                    'promotional_price' =>$product->prix_promo,
                    'main_image' =>$product->imagePrincipale ? asset('storage/'.$product->imagePrincipale->chemin) :null,
                     'average_rating'=>$product->noteMoyenne(),
                ];
            });
            $result['related_products']=$relatedProducts;
            return response()->json([
                'success' =>true,
                'data' =>$result,
            ]);
         }
      //featured produit
      public function featured(){
        $featuredProducts =Produit::where('featured', true)
        ->where('disponible',true)
        ->with(['imagePrincipale'])
        ->take(4)
        ->get()
        ->map(function($product){
            return[
                'id'=> $product->id,
                'name' =>$product->nom,
                'slug' =>$product->slug,
                'price' =>$product->prix,
                'description' =>$product->description,
                'main_image' =>$product->imagePrincipale ? asset('storage/'.$product->imagePrincipale->chemin) :null,
                'promotional_price' =>$product->prix_promo,
                'category' =>[
                'id' =>$product->categorie->id,
                'name' =>$product->categorie->nom,
                'slug' =>$product->categorie->slug,
            ],
          
            'average_rating' =>$product->noteMoyenne(),
            'review_count' =>$product->avis()->where('approuve',true)->count(), 
                ];
        });
        return response()->json([
            'success' =>true,
            'data' =>$featuredProducts,
        ]);

      }
      //recent produit
      public function recent(){
        $recentProducts =Produit::where('disponible',true)
        ->with(['imagePrincipale' ,'categorie'])
        ->orderBy('created_at','desc')
        ->take(8)
        ->get()
        ->map(function($product){
            return[
                'id'=> $product->id,
                'name' =>$product->nom,
                'slug' =>$product->slug,
                'price' =>$product->prix,
                'description' =>$product->description,
                'main_image' =>$product->imagePrincipale ? asset('storage/'.$product->imagePrincipale->chemin) :null,
                'promotional_price' =>$product->prix_promo,
                'category' =>[
                'id' =>$product->categorie->id,
                'name' =>$product->categorie->nom,
                'slug' =>$product->categorie->slug,
            ],

            'average_rating' =>$product->noteMoyenne(),
            'review_count' =>$product->avis()->where('approuve',true)->count(),
                ];
        });
        return response()->json([
            'success'=>true,
            'data' =>$recentProducts
        ]);
      }
      //recherche produit

      public function search($query)
      {
        $products = Produit::where('disponible',true)
        ->where(function($q) use ($query){
            $q->where('nom','LIKE',"%{$query}%")
            ->orWhere('description','LIKE',"%{$query}%")
            ->orWhere('ingredients','LIKE',"%{$query}%");
        })
        ->with(['imagePrincipale','categorie'])
        ->take(10)
        ->get()
        ->map(function($product){
            return[
                'id'=> $product->id,
                'name' =>$product->nom,
                'slug' =>$product->slug,
                'price' =>$product->prix,
                'main_image' =>$product->imagePrincipale ? asset('storage/'.$product->imagePrincipale->chemin) :null,
                'promotional_price' =>$product->prix_promo,
                'category' =>$product->categorie->nom,
            ];
            
        });
        return response()->json([
            'success'=>true,
            'data' =>$products
        ]);
      }
    }
