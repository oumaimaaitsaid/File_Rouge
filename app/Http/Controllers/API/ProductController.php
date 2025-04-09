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
        

    }
}
