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

   
   

  

    
}