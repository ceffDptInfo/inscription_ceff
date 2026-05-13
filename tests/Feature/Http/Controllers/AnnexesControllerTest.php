<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Candidat;
use App\Models\DonneesPersonnelles;
use App\Models\ParcoursScolaire;
use App\Models\Compensations;
use App\Models\AutorisationHorsCanton;
use App\Models\FichierJoint;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnexesController;

beforeEach(function () {
    Route::get('/annexes', [AnnexesController::class, 'index']);
});

it('couvre les conditions positives et presence de fichier', function () {
    $candidat = Candidat::create([
        'email' => 'a@a.ch',
        'token' => '1',
        'statut' => 'nouveau',
        'date_creation' => now()
    ]);

    DonneesPersonnelles::create(['candidat_id' => $candidat->id, 'nationalite' => 'Autre']);
    ParcoursScolaire::create(['candidat_id' => $candidat->id, 'type_parcours' => 'Canton de Berne']);
    Compensations::create(['candidat_id' => $candidat->id, 'reponse' => true]);
    AutorisationHorsCanton::create(['candidat_id' => $candidat->id, 'reponse' => true]);
    FichierJoint::create(['candidat_id' => $candidat->id, 'type_document' => 'photo portrait', 'nom_fichier' => 'p.jpg', 'chemin_fichier' => 'p.jpg']);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get('/annexes')
        ->assertStatus(200);
});

it('couvre l\'alternative scolaire et les relations vides', function () {
    $candidat = Candidat::create([
        'email' => 'b@b.ch',
        'token' => '2',
        'statut' => 'nouveau',
        'date_creation' => now()
    ]);

    ParcoursScolaire::create(['candidat_id' => $candidat->id, 'type_parcours' => 'Autre activite']);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get('/annexes')
        ->assertStatus(200);
});