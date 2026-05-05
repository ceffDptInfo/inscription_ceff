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
        Schema::create('parcours_scolaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidats')->onDelete('cascade');
            $table->string('type_parcours')->nullable();
            $table->string('nom_ecole')->nullable();
            $table->string('lieu_ecole')->nullable();
            $table->string('niveau_francais')->nullable();
            $table->string('niveau_math')->nullable();
            $table->string('niveau_allemand')->nullable();
            $table->string('description_activite')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcours_scolaires');
    }
};
