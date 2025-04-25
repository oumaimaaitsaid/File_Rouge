<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $commandes = Commande::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('commandes'));
    }

    public function show($orderId)
    {
        try {
            $commande = Commande::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->with('ligneCommandes.produit')
                ->firstOrFail();

            return view('orders.show', compact('commande'));

        } catch (\Exception $e) {
            return redirect()->route('orders.index')
                ->with('error', 'Commande non trouvée');
        }
    }

}
