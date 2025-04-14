<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionUtilisation extends Model
{
    use HasFactory;

    protected $table = 'promotion_utilisations';

    protected $fillable = [
        'promotion_id',
        'user_id',
        'commande_id',
        'date_utilisation'
    ];

    protected $casts = [
        'date_utilisation' => 'datetime'
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
}