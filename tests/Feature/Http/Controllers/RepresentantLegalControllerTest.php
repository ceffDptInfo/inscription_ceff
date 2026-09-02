<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Candidat;
use App\Models\RepresentantLegal;
use App\Http\Controllers\RepresentantLegalController;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::get('/representants-legaux', [RepresentantLegalController::class, 'index'])->name('representants-legaux');
    Route::post('/representants-legaux', [RepresentantLegalController::class, 'store'])->name('representants-legaux.store');
    Route::get('/parcours-scolaire', fn() => 'next')->name('parcours-scolaire');
    app('router')->getRoutes()->refreshNameLookups();
});

it('affiche les representants legaux', function () {
    $candidat = Candidat::create(['email' => 'rep@test.ch', 'token' => '1', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get(route('representants-legaux'))
        ->assertOk();
});

it('store avec un seul representant supprime le second si existant', function () {
    $candidat = Candidat::create(['email' => 'rep1@test.ch', 'token' => '2', 'statut' => 'nouveau', 'date_creation' => now()]);

    RepresentantLegal::create(['candidat_id' => $candidat->id, 'ordre' => 2, 'nom' => 'ASupprimer']);

    $this->withSession(['candidat_id' => $candidat->id])
        ->post(route('representants-legaux.store'), [
            'rep1' => ['type_lien' => 'Mère', 'nom' => 'Martin', 'prenom' => 'A', 'rue_et_num' => 'Rue 1', 'npa' => '2000','localite' => 'NE', 'tel_portable' => '079', 'email' => 'a@test.ch'],
            'has_second_rep' => false,
        ])
        ->assertRedirect(route('parcours-scolaire'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('representants_legaux', ['candidat_id' => $candidat->id, 'ordre' => 1, 'nom' => 'Martin']);
    $this->assertDatabaseMissing('representants_legaux', ['candidat_id' => $candidat->id, 'ordre' => 2]);
});

it('store avec second representant', function () {
    $candidat = Candidat::create(['email' => 'rep2@test.ch', 'token' => '3', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->post(route('representants-legaux.store'), [
            'rep1' => ['type_lien' => 'Mère', 'nom' => 'Martin', 'prenom' => 'A', 'rue_et_num' => 'Rue 1', 'npa' => '2000','localite' => 'NE', 'tel_portable' => '079', 'email' => 'a@test.ch'],
            'has_second_rep' => true,
            'rep2' => ['type_lien' => 'Père', 'nom' => 'Dupont', 'prenom' => 'B', 'rue_et_num' => 'Rue 2', 'npa' => '2001','localite' => 'NE', 'tel_portable' => '078', 'email' => 'b@test.ch'],
        ])
        ->assertRedirect(route('parcours-scolaire'));

    $this->assertDatabaseHas('representants_legaux', ['candidat_id' => $candidat->id, 'ordre' => 2, 'nom' => 'Dupont']);
});