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
        Schema::create('donnees_personnelles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidats')->onDelete('cascade');
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('rue_et_num')->nullable();
            $table->string('npa')->nullable();
            $table->string('localite')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('langue_maternelle')->nullable();
            $table->string('no_avs')->nullable();
            $table->string('tel_fixe')->nullable();
            $table->string('tel_portable')->nullable();
            $table->string('email_prive')->nullable();
            $table->string('genre')->nullable();
            $table->string('nationalite')->nullable();
            $table->string('pays_origine')->nullable();
            $table->string('type_permis')->nullable();
            $table->date('validite_permis')->nullable();
            $table->string('remarques')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donnees_personnelles');
    }
};
