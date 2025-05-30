<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function total()
    {
        return $this->items->sum(function ($item) {
            return $item->quantite * $item->prix_unitaire;
        });
    }

    public function itemCount()
    {
        return $this->items->count();
    }
}