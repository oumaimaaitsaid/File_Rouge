<?php

namespace App\Services;

use App\Models\User;
use App\Models\Commande;
use App\Models\Promotion;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FideliteService
{
    /**
     * Vérifier les clients fidèles et envoyer des codes promo
     * Cette méthode peut être appelée par une commande programmée
     */
    public function verifierEtEnvoyerCodesPromo()
    {
        
        $users = User::whereHas('commandes', function($query) {
            $query->whereIn('statut', ['livree', 'en_livraison']);
        }, '>=', 2)->get();
        
        if ($users->isEmpty()) {
            Log::info('Aucun client éligible pour un code promo de fidélité');
            return;
        }
        
        $promotionsDisponibles = Promotion::where('active', true)
        ->where('description', 'like', '%fid%')
        ->whereColumn('utilisation_actuelle', '<', 'utilisation_maximum') // Utilisation de whereColumn
        ->where('date_fin', '>', now())
        ->get();

        if ($promotionsDisponibles->isEmpty()) {
            Log::warning('Aucun code promo de fidélité disponible à envoyer');
            return;
        }
        
        foreach ($users as $user) {
            $dejaRecu = $this->aDejaRecuCodePromo($user->id);
            
            if (!$dejaRecu) {
                $promotion = $promotionsDisponibles->first();
                
                if ($promotion) {
                    $this->envoyerCodePromo($user, $promotion->code);
                    
                    $promotion->utilisation_actuelle += 1;
                    $promotion->save();
                    
                    if ($promotion->utilisation_actuelle >= $promotion->utilisation_maximum) {
                        $promotionsDisponibles = $promotionsDisponibles->filter(function ($item) use ($promotion) {
                            return $item->id !== $promotion->id;
                        });
                    }
                    
                    $this->marquerCodeEnvoye($user->id);
                }
            }
        }
    }
    
  
  
  
}