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
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->enum('types_intervention',["sur site","par correspondance"])->nullable();
            $table->string('description');
            $table->date( 'date_intervention')->nullable();
            $table->dateTime('debut_intervention')->nullable();
            $table->dateTime('fin_intervention')->nullable();
            $table->foreignIdFor(Module::class)->constrained();
            $table->foreignIdFor(User::class)->constrained()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
