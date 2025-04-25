<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        // Statistiques pour le tableau de bord
        $totalUsers = User::where('role', 'client')->count();
        $totalProducts = Produit::count();
        $totalOrders = Commande::count();
        $totalRevenue = Commande::where('paiement_confirme', true)->sum('montant_total');
        
        // Commandes récentes
        $recentOrders = Commande::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Produits populaires
        $popularProducts = DB::table('ligne_commandes')
            ->select('produit_id', 'nom_produit', DB::raw('SUM(quantite) as total_vendus'))
            ->groupBy('produit_id', 'nom_produit')
            ->orderBy('total_vendus', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalProducts', 
            'totalOrders', 
            'totalRevenue',
            'recentOrders',
            'popularProducts'
        ));
    }






}