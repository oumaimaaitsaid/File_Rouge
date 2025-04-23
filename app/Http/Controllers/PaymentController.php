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
    
    public function processCardPayment(Request $request, $orderId)
    {
        $commande = Commande::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        Stripe::setApiKey(config('services.stripe.secret'));
        
        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'mad',
                            'product_data' => [
                                'name' => 'Commande #' . $commande->numero_commande,
                            ],
                            'unit_amount' => $commande->montant_total * 100, // En centimes
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('payment.stripe.success', ['orderId' => $commande->id]),
                'cancel_url' => route('payment.stripe.cancel', ['orderId' => $commande->id]),
                'metadata' => [
                    'order_id' => $commande->id,
                ]
            ]);
            
            return redirect($session->url);
            
        } catch (ApiErrorException $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création du paiement : ' . $e->getMessage());
        }
    }
    
    public function handleStripeSuccess(Request $request, $orderId)
    {
        $commande = Commande::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $commande->update([
            'statut' => 'confirmee',
            'paiement_confirme' => true,
            'date_paiement' => now(),
            'reference_paiement' => 'STRIPE-' . strtoupper(substr(md5(rand()), 0, 10))
        ]);
        
        return redirect()->route('checkout.success', ['orderId' => $commande->id])
            ->with('success', 'Paiement effectué avec succès !');
    }
    
    public function handleStripeCancel(Request $request, $orderId)
    {
        return redirect()->route('checkout.index')
            ->with('error', 'Le paiement a été annulé.');
    }
}