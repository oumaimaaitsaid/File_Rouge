<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    
    
    public function index()
    {
        $categories = Categorie::withCount('produits')->get();
        return view('admin.categories.index', compact('categories'));
    }
    
   
    
    
    
    
    
   
    
  
}