<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Candidat;
use App\Models\FichierJoint;
use App\Http\Controllers\ParcoursScolaireController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    Route::get('/parcours-scolaire', [ParcoursScolaireController::class, 'index'])->name('parcours-scolaire');
    Route::post('/parcours-scolaire', [ParcoursScolaireController::class, 'store'])->name('parcours-scolaire.store');
    Route::get('/stages', fn() => 'next')->name('stages');
    Route::get('/candidats/edit/{id}/parcours-scolaire', [ParcoursScolaireController::class, 'edit'])->name('parcours-scolaire.edit');
    Route::put('/candidats/edit/{id}/parcours-scolaire', [ParcoursScolaireController::class, 'update'])->name('parcours-scolaire.update');
    Route::get('/candidat-details/{id}', fn() => 'details')->name('candidat-details');
    app('router')->getRoutes()->refreshNameLookups();

    Storage::fake('dossiers_inscription');
});

it('affiche index sans donnees puis avec bulletin et cv existants', function () {
    $candidat = Candidat::create(['email' => 'parcours@test.ch', 'token' => '1', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get(route('parcours-scolaire'))
        ->assertOk();

    FichierJoint::create(['candidat_id' => $candidat->id, 'type_document' => 'bulletin scolaire', 'nom_fichier' => 'b.pdf', 'chemin_fichier' => 'b.pdf']);
    FichierJoint::create(['candidat_id' => $candidat->id, 'type_document' => 'CV', 'nom_fichier' => 'cv.pdf', 'chemin_fichier' => 'cv.pdf']);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get(route('parcours-scolaire'))
        ->assertOk();
});

it('store sans fichiers enregistre le parcours', function () {
    $candidat = Candidat::create(['email' => 'store1@test.ch', 'token' => '2', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->post(route('parcours-scolaire.store'), [
            'type_parcours' => 'Canton de Berne',
            'nom_ecole' => 'Ecole Test',
        ])
        ->assertRedirect(route('stages'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('parcours_scolaires', ['candidat_id' => $candidat->id, 'type_parcours' => 'Canton de Berne']);
});

it('store avec cv et bulletin uploades', function () {
    $candidat = Candidat::create(['email' => 'store2@test.ch', 'token' => '3', 'statut' => 'nouveau', 'date_creation' => now(), 'dossier_nom' => 'dossier_test']);

    $this->withSession(['candidat_id' => $candidat->id])
        ->post(route('parcours-scolaire.store'), [
            'type_parcours' => 'Autre activite',
            'cv' => UploadedFile::fake()->create('cv.pdf', 100),
            'bulletin_scolaire' => UploadedFile::fake()->create('bulletin.pdf', 100),
        ])
        ->assertRedirect(route('stages'));

    $this->assertDatabaseHas('fichiers_joints', ['candidat_id' => $candidat->id, 'type_document' => 'CV']);
    $this->assertDatabaseHas('fichiers_joints', ['candidat_id' => $candidat->id, 'type_document' => 'bulletin scolaire']);
});

it('edit et update via secretaire', function () {
    $candidat = Candidat::create(['email' => 'edit@test.ch', 'token' => '4', 'statut' => 'nouveau', 'date_creation' => now()]);
    $secretaire = \App\Models\Secretaire::create(['email' => 'sec@test.ch', 'password' => bcrypt('pass')]);

    expect(app(ParcoursScolaireController::class)->edit($candidat->id))
        ->toBeInstanceOf(\Inertia\Response::class);

    $this->actingAs($secretaire, 'secretaire')
        ->put("/candidats/edit/{$candidat->id}/parcours-scolaire", [
            'type_parcours' => 'Modifié',
            'nom_ecole' => 'Nouvelle école',
        ])
        ->assertRedirect("/candidat-details/{$candidat->id}");

    $this->assertDatabaseHas('parcours_scolaires', ['candidat_id' => $candidat->id, 'type_parcours' => 'Modifié']);
});