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

    protected $casts = [
        'montant_total' => 'float',
        'frais_livraison' => 'float',
        'remise' => 'float',
        'paiement_confirme' => 'boolean',
        'date_paiement' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    // Générer un numéro de commande unique
    public static function generateOrderNumber()
    {
        return 'TS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}