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
    public function statistics()
{
    return view('admin.statistics.index');
}

public function salesStatistics(Request $request)
{
    $period = $request->get('period', 'this_month');
    
    switch ($period) {
        case 'today':
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
            $groupBy = 'hour';
            break;
        case 'yesterday':
            $startDate = now()->subDay()->startOfDay();
            $endDate = now()->subDay()->endOfDay();
            $groupBy = 'hour';
            break;
        case 'this_week':
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
            $groupBy = 'day';
            break;
        case 'last_week':
            $startDate = now()->subWeek()->startOfWeek();
            $endDate = now()->subWeek()->endOfWeek();
            $groupBy = 'day';
            break;
        case 'last_month':
            $startDate = now()->subMonth()->startOfMonth();
            $endDate = now()->subMonth()->endOfMonth();
            $groupBy = 'day';
            break;
        case 'this_year':
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
            $groupBy = 'month';
            break;
        case 'last_year':
            $startDate = now()->subYear()->startOfYear();
            $endDate = now()->subYear()->endOfYear();
            $groupBy = 'month';
            break;
        case 'custom':
            $startDate = $request->has('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : now()->subDays(30)->startOfDay();
            $endDate = $request->has('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();
            
            $diffInDays = $startDate->diffInDays($endDate);
            
            if ($diffInDays <= 1) {
                $groupBy = 'hour';
            } elseif ($diffInDays <= 31) {
                $groupBy = 'day';
            } elseif ($diffInDays <= 365) {
                $groupBy = 'week';
            } else {
                $groupBy = 'month';
            }
            break;
        default:
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
            $groupBy = 'day';
    }
    
    $sales = Commande::where('created_at', '>=', $startDate)
    ->where('created_at', '<=', $endDate)
    ->where('paiement_confirme', true)
    ->select(
        DB::raw("to_char(created_at, 'YYYY-MM-DD') as date"),
        DB::raw("COUNT(*) as count"),
        DB::raw("SUM(montant_total) as total")
    )
    ->groupBy('date')
    ->orderBy('date')
    ->get();

  $salesByPaymentMethod = Commande::where('created_at', '>=', $startDate)
    ->where('created_at', '<=', $endDate)
    ->where('paiement_confirme', true)
    ->select(
        'methode_paiement',
        DB::raw("COUNT(*) as count"),
        DB::raw("SUM(montant_total) as total")
    )
    ->groupBy('methode_paiement')
    ->get();

    return view('admin.statistics.sales', compact('sales', 'salesByPaymentMethod', 'period', 'startDate', 'endDate'));}

public function productStatistics(Request $request)
{
    $period = $request->get('period', 'this_month');
    
    switch ($period) {
        case 'today':
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
            break;
        case 'yesterday':
            $startDate = now()->subDay()->startOfDay();
            $endDate = now()->subDay()->endOfDay();
            break;
        case 'this_week':
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
            break;
        case 'last_week':
            $startDate = now()->subWeek()->startOfWeek();
            $endDate = now()->subWeek()->endOfWeek();
            break;
        case 'last_month':
            $startDate = now()->subMonth()->startOfMonth();
            $endDate = now()->subMonth()->endOfMonth();
            break;
        case 'this_year':
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
            break;
        case 'last_year':
            $startDate = now()->subYear()->startOfYear();
            $endDate = now()->subYear()->endOfYear();
            break;
        case 'custom':
            $startDate = $request->has('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : now()->subDays(30)->startOfDay();
            $endDate = $request->has('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();
            break;
        default:
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
    }
    
    $topProducts = DB::table('ligne_commandes')
        ->join('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
        ->where('commandes.created_at', '>=', $startDate)
        ->where('commandes.created_at', '<=', $endDate)
        ->where('commandes.paiement_confirme', true)
        ->select(
            'ligne_commandes.produit_id',
            'ligne_commandes.nom_produit',
            DB::raw("SUM(ligne_commandes.quantite) as total_quantity"),
            DB::raw("SUM(ligne_commandes.total) as total_amount")
        )
        ->groupBy('ligne_commandes.produit_id', 'ligne_commandes.nom_produit')
        ->orderBy('total_quantity', 'desc')
        ->take(10)
        ->get();
    
    $topCategories = DB::table('ligne_commandes')
        ->join('commandes', 'ligne_commandes.commande_id', '=', 'commandes.id')
        ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id')
        ->join('categories', 'produits.category_id', '=', 'categories.id')
        ->where('commandes.created_at', '>=', $startDate)
        ->where('commandes.created_at', '<=', $endDate)
        ->where('commandes.paiement_confirme', true)
        ->select(
            'categories.id',
            'categories.nom',
            DB::raw("SUM(ligne_commandes.quantite) as total_quantity"),
            DB::raw("SUM(ligne_commandes.total) as total_amount")
        )
        ->groupBy('categories.id', 'categories.nom')
        ->orderBy('total_quantity', 'desc')
        ->get();
    
    return view('admin.statistics.products', compact('topProducts', 'topCategories', 'period', 'startDate', 'endDate'));
}

public function userStatistics(Request $request)
{
    $period = $request->get('period', 'all_time');
    
    switch ($period) {
        case 'this_month':
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
            break;
        case 'last_month':
            $startDate = now()->subMonth()->startOfMonth();
            $endDate = now()->subMonth()->endOfMonth();
            break;
        case 'this_year':
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
            break;
        case 'last_year':
            $startDate = now()->subYear()->startOfYear();
            $endDate = now()->subYear()->endOfYear();
            break;
        case 'custom':
            $startDate = $request->has('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : null;
            $endDate = $request->has('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();
            break;
        default:
            $startDate = null;
            $endDate = now();
    }
    
    $newUsersQuery = User::where('role', 'client');
    if ($startDate) {
        $newUsersQuery->where('created_at', '>=', $startDate);
    }
    $newUsersQuery->where('created_at', '<=', $endDate);
    
    $newUsers = $newUsersQuery->count();
    
    $topCustomers = DB::table('commandes')
        ->join('users', 'commandes.user_id', '=', 'users.id')
        ->where('commandes.paiement_confirme', true);
    
    if ($startDate) {
        $topCustomers->where('commandes.created_at', '>=', $startDate);
    }
    $topCustomers->where('commandes.created_at', '<=', $endDate);
    
    $topCustomers = $topCustomers->select(
            'users.id',
            'users.name',
            'users.prenom',
            'users.email',
            DB::raw("COUNT(commandes.id) as total_orders"),
            DB::raw("SUM(commandes.montant_total) as total_spent")
        )
        ->groupBy('users.id', 'users.name', 'users.prenom', 'users.email')
        ->orderBy('total_spent', 'desc')
        ->take(10)
        ->get();
    
    $userRegistrationByMonth = DB::table('users')
        ->where('role', 'client');
    
    if ($startDate) {
        $userRegistrationByMonth->where('created_at', '>=', $startDate);
    }
    $userRegistrationByMonth->where('created_at', '<=', $endDate);
    
    $userRegistrationByMonth = $userRegistrationByMonth->select(
            DB::raw("to_char(created_at,'YYYY-MM') as month"),
            DB::raw("COUNT(*) as count")
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    
    return view('admin.statistics.users', compact('newUsers', 'topCustomers', 'userRegistrationByMonth', 'period', 'startDate', 'endDate'));
}
}