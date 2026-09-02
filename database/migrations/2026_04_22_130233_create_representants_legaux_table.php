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
        Schema::create('representants_legaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidats')->onDelete('cascade');
            $table->integer('ordre')->default(1);
            $table->string('type_lien')->nullable();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('rue_et_num')->nullable();
            $table->string('npa')->nullable();
            $table->string('localite')->nullable();
            $table->string('tel_fixe')->nullable();
            $table->string('tel_portable')->nullable();
            $table->string('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representants_legaux');
    }
};
