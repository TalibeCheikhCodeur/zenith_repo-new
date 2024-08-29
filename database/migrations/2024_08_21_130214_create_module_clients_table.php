<?php

use App\Models\Module;
use App\Models\User;
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
        Schema::create('module_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Module::class)->constrained()->cascadeOnDelete();
            $table->string('numero_serie');
            $table->string('version');
            $table->string('code_annuel');
            $table->string('code_activation');
            $table->integer('nbre_users');
            $table->integer('nbre_salariés')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_clients');
    }
};
