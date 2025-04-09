<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
   use HasFactory;
   protected $fillable =[
    'nom',
    'slug',
    'description',
    'ingredients',
    'prix',
    'prix_promo',
    'stock',
    'disponible',
    'featured',
    'category_id',
   ];
   protected $casts =[
   'prix'=>'float',
    'prix_promo' =>'float',
    'stock'=>'integer',
    'disponible'=>'boolean',
    'featured'=>'boolean',
   ];
   public function categorie(){
    return $this->belongsTo(Categorie::class, 'category_id');
   }
   public function images()
   {
       return $this->hasMany(ImageProduit::class);
   }
   public function imagePrincipale()
    {
        return $this->hasOne(ImageProduit::class)->where('principale', true);
    }
    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

}
