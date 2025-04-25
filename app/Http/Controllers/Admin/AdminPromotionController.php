<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminPromotionController extends Controller
{
   
    
    public function index(Request $request)
    {
        $query = Promotion::query();
        
        // Filtrage
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }
        
        if ($request->has('active')) {
            $now = now();
            if ($request->active == 'yes') {
                $query->where(function($q) use ($now) {
                    $q->where('date_debut', '<=', $now)
                      ->where(function($q) use ($now) {
                          $q->where('date_fin', '>=', $now)
                            ->orWhereNull('date_fin');
                      });
                });
            } else {
                $query->where(function($q) use ($now) {
                    $q->where('date_debut', '>', $now)
                      ->orWhere('date_fin', '<', $now);
                });
            }
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('code', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }
        
        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $promotions = $query->paginate(15);
        
        return view('admin.promotions.index', compact('promotions'));
    }
    
    public function create()
    {
        return view('admin.promotions.create');
    }
    
    
    
   
   
    
   
}