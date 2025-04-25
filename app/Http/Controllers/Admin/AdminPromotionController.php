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
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:promotions',
            'description' => 'required|string|max:255',
            'type' => 'required|in:pourcentage,montant,livraison_gratuite',
            'valeur' => 'required_if:type,pourcentage,montant|nullable|numeric|min:0',
            'commande_minimum' => 'nullable|numeric|min:0',
            'usage_maximum' => 'nullable|integer|min:1',
            'usage_par_utilisateur' => 'nullable|integer|min:1',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $data = [
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'type' => $request->type,
                'valeur' => $request->valeur,
                'commande_minimum' => $request->commande_minimum,
                'usage_maximum' => $request->usage_maximum,
                'usage_par_utilisateur' => $request->usage_par_utilisateur,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
            ];
            
            Promotion::create($data);
            
            return redirect()->route('admin.promotions.index')
                ->with('success', 'Promotion créée avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la promotion: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.promotions.edit', compact('promotion'));
    }
    
   
    
   
}