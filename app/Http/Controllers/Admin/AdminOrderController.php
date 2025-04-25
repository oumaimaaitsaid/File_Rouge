<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminOrderController extends Controller
{
   
    
    public function index(Request $request)
    {
        $query = Commande::with('user');
        
        // Filtrage
        if ($request->has('status') && $request->status != 'all') {
            $query->where('statut', $request->status);
        }
        
        if ($request->has('payment_status')) {
            $query->where('paiement_confirme', $request->payment_status === 'paid');
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('numero_commande', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                  });
            });
        }
        
        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $orders = $query->paginate(20);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    public function show($id)
    {
        $order = Commande::with(['user', 'ligneCommandes.produit'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'statut' => 'required|in:en_attente,confirmee,preparee,expediee,livree,annulee',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $order = Commande::findOrFail($id);
            $order->update(['statut' => $request->statut]);
            
            return redirect()->route('admin.orders.show', $id)
                ->with('success', 'Statut de la commande mis à jour avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());
        }
    }
    
    public function updatePaymentStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'paiement_confirme' => 'required|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $order = Commande::findOrFail($id);
            
            $data = [
                'paiement_confirme' => (bool)$request->paiement_confirme
            ];
            
            if ($request->paiement_confirme && !$order->date_paiement) {
                $data['date_paiement'] = now();
                $data['reference_paiement'] = 'ADMIN-' . strtoupper(substr(md5(rand()), 0, 10));
            }
            
            $order->update($data);
            
            return redirect()->route('admin.orders.show', $id)
                ->with('success', 'Statut de paiement mis à jour avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du statut de paiement: ' . $e->getMessage());
        }
    }
}