<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Http\Controllers\ChoixApprentissageController;
use App\Models\Candidat;
use App\Models\ChoixApprentissage;
use App\Models\Secretaire;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::get('/choix-apprentissage', [ChoixApprentissageController::class, 'index'])->name('choix-apprentissage');
    Route::post('/choix-apprentissage', [ChoixApprentissageController::class, 'store'])->name('choix-apprentissage.store');
    Route::get('/annexes', fn() => 'annexes')->name('annexes');
    Route::get('/candidat-details/{id}', fn() => 'details')->name('candidat-details');
});

it('affiche les choix apprentissage existants ou nuls', function () {
    $candidat = Candidat::create(['email' => 'a@test.ch', 'token' => '1', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get(route('choix-apprentissage'))
        ->assertOk();

    ChoixApprentissage::create(['candidat_id' => $candidat->id, 'premier_choix' => 'Informatique']);
    $this->withSession(['candidat_id' => $candidat->id])
        ->get(route('choix-apprentissage'))
        ->assertOk();
});

it('enregistre ou met a jour le choix via store', function () {
    $candidat = Candidat::create(['email' => 'b@test.ch', 'token' => '2', 'statut' => 'nouveau', 'date_creation' => now()]);

    $payload = ['premier_choix' => 'Mecanique', 'deuxieme_choix' => 'Electricite'];

    $this->withSession(['candidat_id' => $candidat->id])
        ->post(route('choix-apprentissage.store'), $payload)
        ->assertRedirect(route('annexes'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('choix_apprentissages', [
        'candidat_id' => $candidat->id,
        'premier_choix' => 'Mecanique'
    ]);
});

it('permet a la secretaire d editer les choix', function () {
    $candidat = Candidat::create(['email' => 'c@test.ch', 'token' => '3', 'statut' => 'nouveau', 'date_creation' => now()]);

    $controller = new ChoixApprentissageController();
    $response = $controller->edit($candidat->id);

    expect($response)->toBeInstanceOf(\Inertia\Response::class);
});

it('permet a la secretaire de mettre a jour les choix', function () {
    $candidat = Candidat::create(['email' => 'd@test.ch', 'token' => '4', 'statut' => 'nouveau', 'date_creation' => now()]);
    $secretaire = Secretaire::create(['email' => 'sec@test.ch', 'password' => bcrypt('password')]);

    $payload = ['premier_choix' => 'Art', 'deuxieme_choix' => null];

    $this->actingAs($secretaire, 'secretaire')
        ->put("/candidats/edit/{$candidat->id}/choix-apprentissage", $payload)
        ->assertRedirect("/candidat-details/{$candidat->id}");

    $this->assertDatabaseHas('choix_apprentissages', [
        'candidat_id' => $candidat->id,
        'premier_choix' => 'Art'
    ]);
});