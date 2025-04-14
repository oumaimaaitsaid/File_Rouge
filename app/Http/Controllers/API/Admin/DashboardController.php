<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //les donnees de la page d'accueil
    public function index()
    {
        try {
            // Statistiques générales
            $totalCommandes = Commande::count();
            $totalClients = User::where('role', 'client')->count();
            $totalProduits = Produit::count();
            
            // Calcul du chiffre d'affaires
            $chiffreAffaires = Commande::where('paiement_confirme', true)
                               ->sum('montant_total');
            
            // Commandes récentes
            $commandesRecentes = Commande::with('user')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get()
                                ->map(function($commande) {
                                    return [
                                        'id' => $commande->id,
                                        'numero_commande' => $commande->numero_commande,
                                        'client' => $commande->user->name . ' ' . $commande->user->prenom,
                                        'montant' => $commande->montant_total,
                                        'statut' => $commande->statut,
                                        'date' => $commande->created_at->format('d/m/Y H:i')
                                    ];
                                });
            
            // Produits les plus vendus
            $produitsPopulaires = DB::table('ligne_commandes')
                                 ->select('produit_id', 'nom_produit', DB::raw('SUM(quantite) as total_vendus'))
                                 ->groupBy('produit_id', 'nom_produit')
                                 ->orderBy('total_vendus', 'desc')
                                 ->limit(5)
                                 ->get();
            
            // Statistiques par statut de commande
            $statutCommandes = Commande::select('statut', DB::raw('count(*) as total'))
                              ->groupBy('statut')
                              ->get()
                              ->pluck('total', 'statut')
                              ->toArray();
            
            // Ventes par jour (7 derniers jours)
            $ventesParJour = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $total = Commande::whereDate('created_at', $date)
                         ->where('paiement_confirme', true)
                         ->sum('montant_total');
                
                $ventesParJour[] = [
                    'date' => Carbon::parse($date)->format('d/m'),
                    'total' => $total
                ];
            }
            
            // Clients récemment inscrits
            $nouveauxClients = User::where('role', 'client')
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get()
                              ->map(function($user) {
                                  return [
                                      'id' => $user->id,
                                      'nom' => $user->name . ' ' . $user->prenom,
                                      'email' => $user->email,
                                      'date_inscription' => $user->created_at->format('d/m/Y')
                                  ];
                              });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'statistiques_generales' => [
                        'total_commandes' => $totalCommandes,
                        'total_clients' => $totalClients,
                        'total_produits' => $totalProduits,
                        'chiffre_affaires' => $chiffreAffaires
                    ],
                    'commandes_recentes' => $commandesRecentes,
                    'produits_populaires' => $produitsPopulaires,
                    'statut_commandes' => $statutCommandes,
                    'ventes_par_jour' => $ventesParJour,
                    'nouveaux_clients' => $nouveauxClients
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données du tableau de bord',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    //sts de ventes de chaque période
    public function salesStats(Request $request)
    {
        try {
            $periode = $request->get('periode', 'semaine');
            $dateDebut = null;
            $dateFin = Carbon::now();
            $format = '';
            $groupBy = '';
            
            switch ($periode) {
                case 'semaine':
                    $dateDebut = Carbon::now()->subDays(7);
                    $format = 'YYYY-MM-DD';
                    $groupBy = 'date';
                    break;
                case 'mois':
                    $dateDebut = Carbon::now()->subDays(30);
                    $format = 'YYYY-MM-DD';
                    $groupBy = 'date';
                    break;
                case 'annee':
                    $dateDebut = Carbon::now()->subMonths(12);
                    $format = 'YYYY-MM';
                    $groupBy = 'mois';
                    break;
                default:
                    $dateDebut = Carbon::now()->subDays(7);
                    $format = 'YYYY-MM-DD';
                    $groupBy = 'date';
            }
            
            $ventes = Commande::where('created_at', '>=', $dateDebut)
                    ->where('created_at', '<=', $dateFin)
                    ->where('paiement_confirme', true)
                    ->select(
                        DB::raw("TO_CHAR(created_at, '{$format}') as {$groupBy}"),
                        DB::raw('SUM(montant_total) as total'),
                        DB::raw('COUNT(*) as commandes')
                    )
                    ->groupBy($groupBy)
                    ->orderBy($groupBy)
                    ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'periode' => $periode,
                    'ventes' => $ventes
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques de ventes',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
  
    
   
}