<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Http\Controllers\DonneesPersonnellesController;
use App\Models\Candidat;
use App\Models\DonneesPersonnelles;
use App\Models\Secretaire;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

beforeEach(function () {
    Storage::fake('dossiers_inscription');
    Route::get('/donnees-personnelles', [DonneesPersonnellesController::class, 'index'])
        ->name('donnees-personnelles');
    Route::post('/donnees-personnelles', [DonneesPersonnellesController::class, 'store'])
        ->name('donnees-personnelles.store');
    Route::get('/representants-legaux', fn() => 'next')
        ->name('representants-legaux');
    Route::get('/candidat-details/{id}', fn() => 'details')
        ->name('candidat-details');
    Route::get('/candidats/edit/{id}/donnees-personnelles', [DonneesPersonnellesController::class, 'edit'])
        ->name('donnees-personnelles.edit');
    Route::put('/candidats/edit/{id}/donnees-personnelles', [DonneesPersonnellesController::class, 'update'])
        ->name('donnees-personnelles.update');

    app('router')->getRoutes()->refreshNameLookups();
});

it('affiche le formulaire sans donnees puis avec donnees existantes', function () {
    $candidat = Candidat::create([
        'email' => 'perso@test.ch',
        'token' => '1',
        'statut' => 'nouveau',
        'date_creation' => now(),
    ]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get(route('donnees-personnelles'))
        ->assertOk();

    DonneesPersonnelles::create([
        'candidat_id' => $candidat->id,
        'nom' => 'Doe',
        'prenom' => 'John',
    ]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get(route('donnees-personnelles'))
        ->assertOk();
});

it('enregistre les donnees personnelles via store', function () {
    $candidat = Candidat::create([
        'email' => 'store@test.ch',
        'token' => '2',
        'statut' => 'nouveau',
        'date_creation' => now(),
    ]);

    $payload = [
        'nom' => 'Smith',
        'prenom' => 'Jane',
        'rue_et_num' => 'Rue du Test 1',
        'npa_localite' => '1000 Lausanne',
        'date_naissance' => '2005-05-05',
        'langue_maternelle' => 'Français',
        'no_avs' => '756.1234.5678.90',
        'tel_portable' => '0791234567',
        'email_prive' => 'jane@test.ch',
        'genre' => 'Féminin',
        'nationalite' => 'Suisse',
        'pays_origine' => 'Suisse',
    ];

    $this->withSession(['candidat_id' => $candidat->id])
        ->post(route('donnees-personnelles.store'), $payload)
        ->assertRedirect(route('representants-legaux'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('donnees_personnelles', [
        'candidat_id' => $candidat->id,
        'nom' => 'Smith',
        'prenom' => 'Jane',
    ]);
});

it('permet a la secretaire d editer et modifier les donnees', function () {
    $candidat = Candidat::create([
        'email' => 'sec@test.ch',
        'token' => '3',
        'statut' => 'nouveau',
        'date_creation' => now(),
    ]);

    $secretaire = Secretaire::create([
        'email' => 'admin@test.ch',
        'password' => bcrypt('password'),
    ]);

    DonneesPersonnelles::create([
        'candidat_id' => $candidat->id,
        'nom' => 'Original',
        'prenom' => 'User',
    ]);

    $controller = app(DonneesPersonnellesController::class);
    expect($controller->edit($candidat->id))->toBeInstanceOf(Response::class);

    $payload = [
        'nom' => 'Modifié',
        'prenom' => 'Admin',
        'rue_et_num' => 'Route de Test 10',
        'npa_localite' => '2610 Saint-Imier',
        'nationalite' => 'Suisse',
        'pays_origine' => 'Suisse',
        'rep1' => [
            'type_lien' => 'Père',
            'nom' => 'Smith',
            'prenom' => 'Robert',
            'rue_et_num' => 'Rue Parent 1',
            'npa_localite' => '2000 Neuchâtel',
            'tel_portable' => '0791111111',
            'email' => 'parent@test.ch',
        ],
        'has_second_rep' => false,
    ];

    $this->actingAs($secretaire, 'secretaire')
        ->put("/candidats/edit/{$candidat->id}/donnees-personnelles", $payload)
        ->assertRedirect("/candidat-details/{$candidat->id}");

    $this->assertDatabaseHas('donnees_personnelles', [
        'candidat_id' => $candidat->id,
        'nom' => 'Modifié',
    ]);
});