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
        }

    }
}
