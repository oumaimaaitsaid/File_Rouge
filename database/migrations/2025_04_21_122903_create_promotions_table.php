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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description');
            $table->enum('type', ['pourcentage', 'montant_fixe', 'livraison_gratuite']);
            $table->decimal('valeur', 10, 2);
            $table->decimal('montant_minimum', 10, 2)->nullable();
            $table->integer('utilisation_maximum')->nullable();
            $table->integer('utilisation_actuelle')->default(0);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};