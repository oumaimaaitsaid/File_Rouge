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
       return $this->hasMany(ImageProduit::class,'produit_id');
   }
   public function imagePrincipale()
    {
        return $this->hasOne(ImageProduit::class)->where('principale', true);
    }
    public function avis()
    {
        return $this->hasMany(Avis::class);
    }
    public function lignesCommande()
    {
        return $this->hasMany(LigneCommande::class);
    }
    public function noteMoyenne(){
        return $this->avis()->where('approuve',true)->avg('note') ?? 0;
    }
    // Dans le modèle Produit, ajoutez cette méthode si elle n'existe pas
public function getPrixActuel()
{
    return ($this->prix_promo && $this->prix_promo < $this->prix) ? $this->prix_promo : $this->prix;
}
}
