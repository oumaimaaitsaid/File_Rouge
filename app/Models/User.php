<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
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

}