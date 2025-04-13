<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'produit_id',
        'nom_produit',
        'quantite',
        'prix_unitaire',
        'total'
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'float'
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // Calculer le sous-total de cette ligne
    public function sousTotal()
    {
        return $this->quantite * $this->prix_unitaire;
    }
}