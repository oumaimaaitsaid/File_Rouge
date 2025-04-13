<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Commande::query()->with('user');
        
        // Filtrage par statut
        if ($request->has('status') && !empty($request->status)) {
            $query->where('statut', $request->status);
        }
        
        // Filtrage par paiement
        if ($request->has('payment_confirmed') && $request->payment_confirmed !== null) {
            $query->where('paiement_confirme', $request->payment_confirmed === 'true' || $request->payment_confirmed === '1');
        }
        
        // Recherche par numéro de commande ou email/nom client
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_commande', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('email', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%")
                        ->orWhere('prenom', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Filtrage par date
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Tri
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        // Pagination
        $perPage = $request->get('per_page', 10);
        $orders = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->numero_commande,
                    'customer' => [
                        'id' => $order->user->id,
                        'name' => $order->user->name.' '.$order->user->prenom,
                        'email' => $order->user->email
                    ],
                    'total' => $order->montant_total,
                    'status' => $order->statut,
                    'payment_method' => $order->methode_paiement,
                    'payment_confirmed' => $order->paiement_confirme,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s')
                ];
            }),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
            ]
        ]);
    }
    
    
     
    
   
     
    
   
     
    
   
     
}