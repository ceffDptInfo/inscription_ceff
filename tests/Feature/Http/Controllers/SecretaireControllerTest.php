<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Candidat;
use App\Models\Secretaire;
use App\Http\Controllers\SecretaireController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Route::get('/liste-candidats', [SecretaireController::class, 'index'])->name('liste-candidats');
    Route::put('/liste-candidats/{id}/statut', [SecretaireController::class, 'updateStatut'])->name('liste-candidats.update-statut');
    Route::get('/candidat-details/{id}', [SecretaireController::class, 'showCandidat'])->name('candidat-details');
    Route::delete('/candidat/{id}', [SecretaireController::class, 'deleteCandidat'])->name('candidat.delete');
    app('router')->getRoutes()->refreshNameLookups();

    Storage::fake('dossiers_inscription');
});

it('index affiche la liste des candidats', function () {
    $secretaire = Secretaire::create(['email' => 'sec@test.ch', 'password' => bcrypt('pass')]);
    Candidat::create(['email' => 'c@test.ch', 'token' => '1', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->actingAs($secretaire, 'secretaire')
        ->get(route('liste-candidats'))
        ->assertOk();
});

it('updateStatut modifie le statut du candidat', function () {
    $secretaire = Secretaire::create(['email' => 'sec2@test.ch', 'password' => bcrypt('pass')]);
    $candidat = Candidat::create(['email' => 'upd@test.ch', 'token' => '2', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->actingAs($secretaire, 'secretaire')
        ->put(route('liste-candidats.update-statut', $candidat->id), ['statut' => 'validé'])
        ->assertRedirect();

    $this->assertDatabaseHas('candidats', ['id' => $candidat->id, 'statut' => 'validé']);
});

it('showCandidat affiche le detail du candidat', function () {
    $secretaire = Secretaire::create(['email' => 'sec3@test.ch', 'password' => bcrypt('pass')]);
    $candidat = Candidat::create(['email' => 'show@test.ch', 'token' => '3', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->actingAs($secretaire, 'secretaire')
        ->get("/candidat-details/{$candidat->id}")
        ->assertOk();
});

it('deleteCandidat supprime le candidat et son dossier', function () {
    $secretaire = Secretaire::create(['email' => 'sec4@test.ch', 'password' => bcrypt('pass')]);
    $candidat = Candidat::create(['email' => 'del@test.ch', 'token' => '4', 'statut' => 'nouveau', 'date_creation' => now(), 'dossier_nom' => 'dossier_test']);

    Storage::disk('dossiers_inscription')->makeDirectory('dossier_test');

    $this->actingAs($secretaire, 'secretaire')
        ->delete("/candidat/{$candidat->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('candidats', ['id' => $candidat->id]);
});