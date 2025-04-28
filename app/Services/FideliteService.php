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
    
    /**
     * Vérifier si un utilisateur a déjà reçu un code promo récemment
     * 
     * @param int $userId
     * @return bool
     */
    protected function aDejaRecuCodePromo($userId)
    {
        $user = User::find($userId);
        return $user->a_recu_code_fidelite;
    }
    protected function marquerCodeEnvoye($userId)
    {
        $user = User::find($userId);
        $user->a_recu_code_fidelite = true;
        $user->date_code_fidelite = now();
        $user->save();
        
        Log::info("Utilisateur #{$userId} marqué comme ayant reçu un code promo");
    }
    
    /**
     * Marquer qu'un utilisateur a reçu un code promo
     * 
     * @param int $userId
     */
   
    
    /**
     * Envoyer un code promo à un utilisateur
     *
     * @param User $user
     * @param string $codePromo
     */
    protected function envoyerCodePromo(User $user, $codePromo)
    {
        try {
            Mail::send('emails.code-promo-fidelite', [
                'user' => $user,
                'codePromo' => $codePromo
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Merci pour votre fidélité - Votre code promo');
            });
            
            Log::info("Code promo envoyé à l'utilisateur #{$user->id} ({$user->email})");
            
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi du code promo: " . $e->getMessage());
        }
    }
}