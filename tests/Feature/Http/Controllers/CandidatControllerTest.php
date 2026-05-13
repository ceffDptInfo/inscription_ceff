<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Http\Controllers\CandidatController;
use App\Mail\ConfirmationInscriptionMail;
use App\Models\Candidat;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Mail::fake();
});

it('index retourne une URL contenant candidats', function () {
    $controller = new CandidatController();

    Route::get('/candidats', fn() => '')->name('candidats.index');
    app('router')->getRoutes()->refreshNameLookups();

    $result = $controller->index();
    expect($result)->toContain('candidats');
});

it('affiche un candidat et charge ses relations', function () {
    $candidat = Candidat::create([
        'email' => 'show@test.ch',
        'token' => 'tok',
        'statut' => 'nouveau',
        'date_creation' => now(),
    ]);

    Route::get('/candidat-details/{candidat}', fn() => '')->name('candidats.show');
    app('router')->getRoutes()->refreshNameLookups();

    $controller = new CandidatController();
    $result = $controller->show($candidat);

    expect($result)->toContain((string) $candidat->id);
});

it('soumet le formulaire final, envoie un mail et nettoie la session', function () {
    Route::get('/candidat-login', fn() => 'login')->name('candidat-login');
    Route::post('/form', [CandidatController::class, 'submitForm'])->name('form.store');
    app('router')->getRoutes()->refreshNameLookups();

    $candidat = Candidat::create([
        'email' => 'final@test.ch',
        'token' => 'token-existant',
        'statut' => 'Nouveau candidat',
        'date_creation' => now(),
    ]);

    $this->withSession(['candidat_id' => $candidat->id])
        ->post('/form')
        ->assertRedirect(route('candidat-login'))
        ->assertSessionHas('success')
        ->assertSessionMissing('candidat_id');

    $candidat->refresh();
    expect($candidat->token)->toBe('')
        ->and($candidat->statut)->toBe('Candidature complète');

    Mail::assertSent(ConfirmationInscriptionMail::class);
});