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
    
    
   
}