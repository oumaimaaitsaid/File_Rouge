<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('ingredients');
            $table->decimal('prix', 10, 2);
            $table->decimal('prix_promo', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('disponible')->default(true);
            $table->boolean('featured')->default(false);
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};