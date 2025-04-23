<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable =[
        'nom',
        'slug',
        'description',
        'image',
        'active',
    ];
    protected $casts =[
        'active'=>'boolean',
    ];
    public function produits(){
        return $this->hasMany(Produit::class ,'category_id');
    }
}
