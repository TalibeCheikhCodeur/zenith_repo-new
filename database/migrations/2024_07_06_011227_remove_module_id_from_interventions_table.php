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
        Schema::table('interventions', function (Blueprint $table) {
            // Suppression de la contrainte de clé étrangère
            $table->dropForeign(['module_id']);
            // Suppression de la colonne
            $table->dropColumn('module_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interventions', function (Blueprint $table) {
            // Ajout de la colonne
            $table->bigInteger('module_id')->unsigned();
            // Ajout de la contrainte de clé étrangère
            $table->foreign('module_id')->references('id')->on('modules');
        });
    }
};
