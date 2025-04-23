<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->timestamps();
            
            $table->unique(['cart_id', 'produit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};