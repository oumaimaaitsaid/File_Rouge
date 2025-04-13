<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_commande',
        'user_id',
        'montant_total',
        'frais_livraison',
        'remise',
        'statut',
        'adresse_livraison',
        'ville_livraison',
        'code_postal_livraison',
        'pays_livraison',
        'telephone_livraison',
        'notes',
        'notes_admin',
        'methode_paiement',
        'reference_paiement',
        'paiement_confirme',
        'date_paiement'
    ];

  

    

    

   
}