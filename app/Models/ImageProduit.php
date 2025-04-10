<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageProduit extends Model
{
    use HasFactory;
    protected $fillable =[
        'produit_id',
        'chemin',
        'principale',
        'order'
    ];
    protected $casts =[
        'principale'=>'boolean',
        'order'=>'integer'
    ];
    

}
