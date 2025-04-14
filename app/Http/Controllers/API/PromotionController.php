<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    
    public function validerCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'montant_panier' => 'required|numeric|min:0'
        ]);

        $code = $request->code;
        $montantPanier = $request->montant_panier;
        $userId = Auth::id();

        $promotion = Promotion::where('code', $code)->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Code promo invalide.'
            ], 404);
        }

        $validation = $promotion->estValide($montantPanier, $userId);

        if (!$validation['valide']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message']
            ], 400);
        }

        // Calculer la réduction
        $reduction = 0;
        $fraisLivraisonGratuits = false;

        if ($promotion->type === 'livraison_gratuite') {
            $fraisLivraisonGratuits = true;
        } else {
            $reduction = $promotion->calculerReduction($montantPanier);
        }

        return response()->json([
            'success' => true,
            'message' => 'Code promo valide!',
            'data' => [
                'promotion_id' => $promotion->id,
                'code' => $promotion->code,
                'type' => $promotion->type,
                'reduction' => $reduction,
                'livraison_gratuite' => $fraisLivraisonGratuits
            ]
        ]);
    }
}

