<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'valeur',
        'montant_minimum',
        'utilisation_max',
        'utilisation_actuelle',
        'usage_unique_par_client',
        'date_debut',
        'date_fin',
        'active'
    ];

    protected $casts = [
        'valeur' => 'float',
        'montant_minimum' => 'float',
        'utilisation_max' => 'integer',
        'utilisation_actuelle' => 'integer',
        'usage_unique_par_client' => 'boolean',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'active' => 'boolean'
    ];

   
    public function estValide($montantPanier = null, $userId = null)
    {
        // Vérifier si le code est actif
        if (!$this->active) {
            return [
                'valide' => false,
                'message' => 'Ce code promo n\'est pas actif.'
            ];
        }

        // Vérifier les dates de validité
        $now = Carbon::now();
        if ($now->lt($this->date_debut) || $now->gt($this->date_fin)) {
            return [
                'valide' => false,
                'message' => 'Ce code promo n\'est pas valide à cette date.'
            ];
        }

        // Vérifier le nombre maximum d'utilisations
        if ($this->utilisation_max !== null && $this->utilisation_actuelle >= $this->utilisation_max) {
            return [
                'valide' => false,
                'message' => 'Ce code promo a atteint son nombre maximum d\'utilisations.'
            ];
        }

        // Vérifier si l'utilisateur a déjà utilisé ce code (si usage unique par client)
        if ($userId && $this->usage_unique_par_client) {
            $utilisation = $this->utilisations()->where('user_id', $userId)->first();
            if ($utilisation) {
                return [
                    'valide' => false,
                    'message' => 'Vous avez déjà utilisé ce code promo.'
                ];
            }
        }

        // Vérifier le montant minimum d'achat
        if ($montantPanier !== null && $this->montant_minimum !== null && $montantPanier < $this->montant_minimum) {
            return [
                'valide' => false,
                'message' => "Le montant minimum d'achat pour ce code promo est de {$this->montant_minimum} MAD."
            ];
        }

        return [
            'valide' => true,
            'message' => 'Code promo valide.'
        ];
    }

   
    public function calculerReduction($montant)
    {
        switch ($this->type) {
            case 'pourcentage':
                return $montant * ($this->valeur / 100);
            
            case 'montant_fixe':
                return min($montant, $this->valeur); // La réduction ne peut pas dépasser le montant total
            
            case 'livraison_gratuite':
                return 0; // La réduction de la livraison sera appliquée séparément
            
            default:
                return 0;
        }
    }

   
   

}