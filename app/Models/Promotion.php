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

   

   
  

   
   
   
   

}