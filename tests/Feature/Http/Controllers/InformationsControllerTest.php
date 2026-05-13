<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Http\Controllers\InformationsController;
use App\Models\AutorisationHorsCanton;
use App\Models\Candidat;
use App\Models\Compensations;
use App\Models\FichierJoint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

beforeEach(function () {
    Route::get('/informations', [InformationsController::class, 'index'])->name('informations');
    Route::post('/informations', [InformationsController::class, 'store'])->name('informations.store');
    Route::get('/choix-apprentissage', fn() => 'next')->name('choix-apprentissage');
    Route::get('/candidats/edit/{id}/informations', [InformationsController::class, 'edit'])->name('informations.edit');
    Route::put('/candidats/edit/{id}/informations', [InformationsController::class, 'update'])->name('informations.update');
    Route::get('/candidat-details/{id}', fn() => 'details')->name('candidat-details');
    app('router')->getRoutes()->refreshNameLookups();

    Storage::fake('dossiers_inscription');
});

it('affiche index sans donnees puis avec donnees existantes', function () {
    $candidat = Candidat::create(['email' => 'info@test.ch', 'token' => '1', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get(route('informations'))
        ->assertOk();

    Compensations::create(['candidat_id' => $candidat->id, 'reponse' => true]);
    AutorisationHorsCanton::create(['candidat_id' => $candidat->id, 'reponse' => true]);
    FichierJoint::create(['candidat_id' => $candidat->id, 'type_document' => 'mesures de compensations', 'nom_fichier' => 'c.pdf', 'chemin_fichier' => 'c.pdf']);
    FichierJoint::create(['candidat_id' => $candidat->id, 'type_document' => 'autorisation hors canton', 'nom_fichier' => 'a.pdf', 'chemin_fichier' => 'a.pdf']);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get(route('informations'))
        ->assertOk();
});

it('store avec reponses true et fichiers uploades', function () {
    $candidat = Candidat::create(['email' => 'store1@test.ch', 'token' => '2', 'statut' => 'nouveau', 'date_creation' => now(), 'dossier_nom' => 'dossier_test']);

    $this->withSession(['candidat_id' => $candidat->id])
        ->post(route('informations.store'), [
            'compensation_reponse' => true,
            'autorisation_reponse' => true,
            'document_compensation' => UploadedFile::fake()->create('comp.pdf', 100),
            'document_autorisation' => UploadedFile::fake()->create('auth.pdf', 100),
        ])
        ->assertRedirect(route('choix-apprentissage'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('compensations', ['candidat_id' => $candidat->id, 'reponse' => true]);
    $this->assertDatabaseHas('autorisation_hors_cantons', ['candidat_id' => $candidat->id, 'reponse' => true]);
});

it('store avec reponses false supprime les fichiers existants', function () {
    $candidat = Candidat::create(['email' => 'store2@test.ch', 'token' => '3', 'statut' => 'nouveau', 'date_creation' => now(), 'dossier_nom' => 'dossier_test']);

    FichierJoint::create(['candidat_id' => $candidat->id, 'type_document' => 'mesures de compensations', 'nom_fichier' => 'c.pdf', 'chemin_fichier' => 'c.pdf']);
    FichierJoint::create(['candidat_id' => $candidat->id, 'type_document' => 'autorisation hors canton', 'nom_fichier' => 'a.pdf', 'chemin_fichier' => 'a.pdf']);

    $this->withSession(['candidat_id' => $candidat->id])
        ->post(route('informations.store'), [
            'compensation_reponse' => false,
            'autorisation_reponse' => false,
        ])
        ->assertRedirect(route('choix-apprentissage'));

    $this->assertDatabaseMissing('fichiers_joints', ['candidat_id' => $candidat->id, 'type_document' => 'mesures de compensations']);
    $this->assertDatabaseMissing('fichiers_joints', ['candidat_id' => $candidat->id, 'type_document' => 'autorisation hors canton']);
});

it('edit et update via secretaire', function () {
    $candidat = Candidat::create(['email' => 'edit@test.ch', 'token' => '5', 'statut' => 'nouveau', 'date_creation' => now()]);
    $secretaire = \App\Models\Secretaire::create(['email' => 'sec@test.ch', 'password' => bcrypt('pass')]);

    $controller = app(InformationsController::class);
    expect($controller->edit($candidat->id))->toBeInstanceOf(Response::class);

    $this->actingAs($secretaire, 'secretaire')
        ->put("/candidats/edit/{$candidat->id}/informations", [
            'compensation_reponse' => true,
            'autorisation_reponse' => false,
        ])
        ->assertRedirect("/candidat-details/{$candidat->id}");

    $this->assertDatabaseHas('compensations', ['candidat_id' => $candidat->id, 'reponse' => true]);
    $this->assertDatabaseHas('autorisation_hors_cantons', ['candidat_id' => $candidat->id, 'reponse' => false]);
});