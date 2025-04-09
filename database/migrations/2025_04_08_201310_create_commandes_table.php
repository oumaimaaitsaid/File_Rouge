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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_commande')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('montant_total', 10, 2);
            $table->decimal('frais_livraison', 10, 2)->default(0);
            $table->decimal('remise', 10, 2)->default(0);
            $table->enum('statut', ['en_attente', 'confirmee', 'en_preparation', 'en_livraison', 'livree', 'annulee'])->default('en_attente');
            $table->string('adresse_livraison');
            $table->string('ville_livraison');
            $table->string('code_postal_livraison');
            $table->string('pays_livraison')->default('Maroc');
            $table->string('telephone_livraison');
            $table->text('notes')->nullable();
            $table->text('notes_admin')->nullable();
            $table->string('methode_paiement');
            $table->string('reference_paiement')->nullable();
            $table->boolean('paiement_confirme')->default(false);
            $table->timestamp('date_paiement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};