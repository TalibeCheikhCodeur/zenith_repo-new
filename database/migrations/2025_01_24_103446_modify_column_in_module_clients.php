<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('module_clients', function (Blueprint $table) {
            $table->string('date_fin_validite')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_clients', function (Blueprint $table) {
            $table->dateTime('date_fin_validite')->change();
        });
    }
};
