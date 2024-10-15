<?php

use App\Models\FicheDesc;
use App\Models\Intervention;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('fiche_descs', function (Blueprint $table) {
    //         $table->id();
    //         $table->text("description")->nullable();
    //         $table->foreignIdFor(Intervention::class)->nullable()->constrained()->cascadeOnDelete();
    //         $table->timestamps();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists('fiche_descs');
    // }
};
