<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function validerCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $code = $request->code;
        // Récupérer le panier pour obtenir le montant
        $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
        
        if (!$cart) {
            return redirect()->back()
                ->with('error', 'Panier non trouvé');
        }
        
        $montantPanier = $cart->total();
        $userId = Auth::id();

        $promotion = Promotion::where('code', $code)->first();

        if (!$promotion) {
            return redirect()->back()
                ->with('error', 'Code promo invalide.');
        }

        $validation = $promotion->estValide($montantPanier, $userId);

        if (!$validation['valide']) {
            return redirect()->back()
                ->with('error', $validation['message']);
        }

        // Calculer la réduction
        $reduction = 0;
        $fraisLivraisonGratuits = false;

        if ($promotion->type === 'livraison_gratuite') {
            $fraisLivraisonGratuits = true;
            $message = 'Code promo valide ! Livraison gratuite appliquée.';
        } else {
            $reduction = $promotion->calculerReduction($montantPanier);
            $message = 'Code promo valide ! Réduction de ' . number_format($reduction, 2) . ' MAD appliquée.';
        }
        
        // Stocker le code promo dans la session
        $request->session()->put('promo_code', $code);

        return redirect()->back()
            ->with('success', $message);
    }
    
    public function supprimerCode(Request $request)
    {
        $request->session()->forget('promo_code');
        
        return redirect()->back()
            ->with('success', 'Code promo retiré');
    }
}