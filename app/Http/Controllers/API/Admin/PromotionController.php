<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PromotionController extends Controller
{
   
    public function index(Request $request)
    {
        $query = Promotion::query();

        // Filtrer par statut actif
        if ($request->has('active') && $request->active !== null) {
            $query->where('active', $request->active === 'true' || $request->active === '1');
        }

        // Filtrer par type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Recherche par code ou description
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filtrer par validité
        if ($request->has('validity')) {
            $now = Carbon::now();
            
            if ($request->validity === 'current') {
                $query->where('date_debut', '<=', $now)
                      ->where('date_fin', '>=', $now);
            } elseif ($request->validity === 'future') {
                $query->where('date_debut', '>', $now);
            } elseif ($request->validity === 'expired') {
                $query->where('date_fin', '<', $now);
            }
        }

        // Tri
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $promotions = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $promotions,
            'pagination' => [
                'current_page' => $promotions->currentPage(),
                'last_page' => $promotions->lastPage(),
                'per_page' => $promotions->perPage(),
                'total' => $promotions->total()
            ]
        ]);
    }

    
    

    

    

   
   
    
}