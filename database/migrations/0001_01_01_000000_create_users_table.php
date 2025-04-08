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
        Schema::table('users', function (Blueprint $table) {
            // Ajouter les champs après le champ 'name' existant
            $table->renameColumn('name', 'nom');
            $table->string('prenom')->after('nom');
            $table->string('telephone')->nullable()->after('email');
            $table->string('adresse')->nullable()->after('telephone');
            $table->string('ville')->nullable()->after('adresse');
            $table->string('code_postal')->nullable()->after('ville');
            $table->string('pays')->default('Maroc')->after('code_postal');
            $table->enum('role', ['client', 'admin', 'partenaire'])->default('client')->after('pays');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('nom', 'name');
            $table->dropColumn([
                'prenom',
                'telephone',
                'adresse',
                'ville',
                'code_postal',
                'pays',
                'role'
            ]);
        });
    }
};