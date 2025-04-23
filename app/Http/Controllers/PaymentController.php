<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Stripe\Stripe;
use \Stripe\Checkout\Session as StripeSession;
use \Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function showCardForm($orderId)
    {
        $commande = Commande::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->with('ligneCommandes.produit') // Pour avoir les détails des produits
            ->firstOrFail();
            
        if ($commande->paiement_confirme) {
            return redirect()->route('checkout.success', ['orderId' => $commande->id])
                ->with('info', 'Cette commande a déjà été payée.');
        }
        
        return view('payment.card', compact('commande'));
    }
    
   
    
    
     
}