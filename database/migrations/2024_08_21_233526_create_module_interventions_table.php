<?php

use App\Models\Intervention;
use App\Models\Module;
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
        Schema::create('module_interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Intervention::class)->constrained()->nullable()->onDelete('cascade')->onUpdate('cascade');   
            $table->foreignIdFor(Module::class)->constrained()->nullable()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_interventions');
    }
};
