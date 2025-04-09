<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Model
{
    use HasFactory, HasApiTokens, Notifiable;
   protected $fillable =[
    'name',
    'prenom',
    'email',
    'password',
    'telephone',
    'addresse',
    'ville',
    'code_postal',
    'pays',
    'role',
   ];

   protected $hidden=[
    'password',
    'remember_token',
   ];

   protected $casts =[
    'email_verified_at' =>'datetime',
    'password' =>'hashed',
   ];
//check if user is admin
   public function isAdmin(){
    return $this->role === 'admin';
   }
//check if user is partenaire
   public function isPartenaire(){
    return $this->role === 'partenaire';
   }
//check if user is client
   public function isClient(){
    return $this->role === 'client';
}
//prend tout les commandes de l'utilisateur
public function commandes(){
    return $this->hasMany(Commande::class);
}
//prend tout les avis de l'utilisateur
public function avis(){
    return $this->hasMany(Avis::class);
}
}