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

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:promotions,code',
            'description' => 'nullable|string',
            'type' => 'required|in:pourcentage,montant_fixe,livraison_gratuite',
            'valeur' => 'required_if:type,pourcentage,montant_fixe|nullable|numeric',
            'montant_minimum' => 'nullable|numeric',
            'utilisation_max' => 'nullable|integer|min:1',
            'usage_unique_par_client' => 'boolean',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validation supplémentaire pour le type pourcentage
        if ($request->type === 'pourcentage' && ($request->valeur < 0 || $request->valeur > 100)) {
            return response()->json([
                'success' => false,
                'message' => 'Le pourcentage doit être compris entre 0 et 100.'
            ], 422);
        }

        try {
            $promotion = Promotion::create([
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'type' => $request->type,
                'valeur' => $request->valeur,
                'montant_minimum' => $request->montant_minimum,
                'utilisation_max' => $request->utilisation_max,
                'usage_unique_par_client' => $request->has('usage_unique_par_client') ? $request->usage_unique_par_client : false,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'active' => $request->has('active') ? $request->active : true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Promotion créée avec succès',
                'data' => $promotion
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la promotion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $promotion = Promotion::with('utilisations.user')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $promotion
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Promotion non trouvée',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    c function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|required|string|unique:promotions,code,' . $id,
            'description' => 'nullable|string',
            'type' => 'sometimes|required|in:pourcentage,montant_fixe,livraison_gratuite',
            'valeur' => 'required_if:type,pourcentage,montant_fixe|nullable|numeric',
            'montant_minimum' => 'nullable|numeric',
            'utilisation_max' => 'nullable|integer|min:1',
            'usage_unique_par_client' => 'boolean',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'sometimes|required|date|after:date_debut',
            'active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validation supplémentaire pour le type pourcentage
        if ($request->has('type') && $request->type === 'pourcentage' && $request->has('valeur') && ($request->valeur < 0 || $request->valeur > 100)) {
            return response()->json([
                'success' => false,
                'message' => 'Le pourcentage doit être compris entre 0 et 100.'
            ], 422);
        }

        try {
            $fieldsToUpdate = [
                'description',
                'valeur',
                'montant_minimum',
                'utilisation_max',
                'usage_unique_par_client',
                'active'
            ];

            foreach ($fieldsToUpdate as $field) {
                if ($request->has($field)) {
                    $promotion->$field = $request->$field;
                }
            }

            // Champs nécessitant un traitement spécial
            if ($request->has('code')) {
                $promotion->code = strtoupper($request->code);
            }

            if ($request->has('type')) {
                $promotion->type = $request->type;
            }

            if ($request->has('date_debut')) {
                $promotion->date_debut = $request->date_debut;
            }

            if ($request->has('date_fin')) {
                $promotion->date_fin = $request->date_fin;
            }

            $promotion->save();

            return response()->json([
                'success' => true,
                'message' => 'Promotion mise à jour avec succès',
                'data' => $promotion
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la promotion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
    public function destroy($id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            
            // Vérifier si la promotion a déjà été utilisée
            if ($promotion->utilisation_actuelle > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cette promotion car elle a déjà été utilisée.'
                ], 400);
            }
            
            $promotion->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Promotion supprimée avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la promotion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
    
}