<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Candidat;
use App\Models\Stage;
use App\Models\AutreInscription;
use App\Models\Secretaire;
use App\Http\Controllers\StageController;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::get('/stages', [StageController::class, 'index'])->name('stages');
    Route::post('/stages', [StageController::class, 'store'])->name('stages.store');
    Route::get('/autres-inscriptions', fn() => 'next')->name('autres-inscriptions');
    Route::get('/candidats/edit/{id}/stages', [StageController::class, 'edit'])->name('stages.edit');
    Route::put('/candidats/edit/{id}/stages', [StageController::class, 'update'])->name('stages.update');
    Route::get('/candidat-details/{id}', fn() => 'details')->name('candidat-details');
    app('router')->getRoutes()->refreshNameLookups();
});

it('affiche les stages existants', function () {
    $candidat = Candidat::create(['email' => 'stage@test.ch', 'token' => '1', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->get(route('stages'))
        ->assertOk();
});

it('store enregistre les stages non vides et ignore les vides', function () {
    $candidat = Candidat::create(['email' => 'store@test.ch', 'token' => '2', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->post(route('stages.store'), [
            'stages' => [
                ['metier' => 'Informaticien', 'entreprise' => 'ACME', 'lieu' => 'Berne', 'duree' => '2 semaines'],
                ['metier' => null, 'entreprise' => null, 'lieu' => null, 'duree' => null],
            ],
        ])
        ->assertRedirect(route('autres-inscriptions'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('stages', ['candidat_id' => $candidat->id, 'metier' => 'Informaticien']);
    expect(Stage::where('candidat_id', $candidat->id)->count())->toBe(1);
});

it('edit et update via secretaire avec stages et inscriptions', function () {
    $candidat = Candidat::create(['email' => 'edit@test.ch', 'token' => '3', 'statut' => 'nouveau', 'date_creation' => now()]);
    $secretaire = Secretaire::create(['email' => 'sec@test.ch', 'password' => bcrypt('pass')]);

    expect(app(StageController::class)->edit($candidat->id))
        ->toBeInstanceOf(\Inertia\Response::class);

    $this->actingAs($secretaire, 'secretaire')
        ->put("/candidats/edit/{$candidat->id}/stages", [
            'stages' => [
                ['metier' => 'Dev', 'entreprise' => 'Corp', 'lieu' => 'NE', 'duree' => '1 mois'],
                ['metier' => null, 'entreprise' => null, 'lieu' => null, 'duree' => null],
            ],
            'inscriptions' => [
                ['etablissement' => 'CPLN', 'lieu' => 'NE'],
                ['etablissement' => null, 'lieu' => null],
            ],
        ])
        ->assertRedirect("/candidat-details/{$candidat->id}");

    $this->assertDatabaseHas('stages', ['candidat_id' => $candidat->id, 'metier' => 'Dev']);
    $this->assertDatabaseHas('autres_inscriptions', ['candidat_id' => $candidat->id, 'etablissement' => 'CPLN']);
    expect(Stage::where('candidat_id', $candidat->id)->count())->toBe(1);
    expect(AutreInscription::where('candidat_id', $candidat->id)->count())->toBe(1);
});