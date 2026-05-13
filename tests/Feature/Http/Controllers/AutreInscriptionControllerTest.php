<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Candidat;
use App\Models\AutreInscription;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutreInscriptionController;

beforeEach(function () {
    Route::get('/autres-inscriptions', [AutreInscriptionController::class, 'index'])->name('autres-inscriptions');
    Route::post('/autres-inscriptions', [AutreInscriptionController::class, 'store']);
    Route::get('/informations', fn() => 'page informations')->name('informations');
});

it('affiche la liste des inscriptions', function () {
    $candidat = Candidat::create([
        'email' => 'test@test.ch',
        'token' => 'abc',
        'statut' => 'nouveau',
        'date_creation' => now()
    ]);

    AutreInscription::create([
        'candidat_id' => $candidat->id,
        'etablissement' => 'Ecole A',
        'lieu' => 'Lausanne'
    ]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get('/autres-inscriptions')
        ->assertStatus(200);
});

it('enregistre de nouvelles inscriptions et ignore les lignes vides', function () {
    $candidat = Candidat::create([
        'email' => 'store@test.ch',
        'token' => 'def',
        'statut' => 'nouveau',
        'date_creation' => now()
    ]);

    $payload = [
        'inscriptions' => [
            ['etablissement' => 'Gymnase', 'lieu' => 'Bienne'],
            ['etablissement' => '', 'lieu' => ''],
        ]
    ];

    $this->withSession(['candidat_id' => $candidat->id])
        ->post('/autres-inscriptions', $payload)
        ->assertRedirect(route('informations'))
        ->assertSessionHas('success');

    expect(AutreInscription::where('candidat_id', $candidat->id)->count())->toBe(1);
});