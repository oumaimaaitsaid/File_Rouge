<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use Illuminate\Support\Facades\Auth;
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
    public function cancel($orderId)
    {
        try {
            $commande = Commande::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($commande->statut != 'en_attente' && $commande->statut != 'confirmee') {
                return redirect()->back()
                    ->with('error', 'Cette commande ne peut plus être annulée');
            }

            DB::beginTransaction();

            foreach ($commande->ligneCommandes as $ligne) {
                $produit = Produit::find($ligne->produit_id);
                if ($produit) {
                    $produit->update([
                        'stock' => $produit->stock + $ligne->quantite
                    ]);
                }
            }

            $commande->update([
                'statut' => 'annulee'
            ]);

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Commande annulée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'annulation de la commande: ' . $e->getMessage());
        }
    }


}
