<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Candidat;
use App\Mail\CandidatLoginMail;
use App\Http\Controllers\Auth\CandidatAuthController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::post('/send-link', [CandidatAuthController::class, 'sendLink'])->name('send-link');
    Route::get('/verify-candidat/{token}', [CandidatAuthController::class, 'verifyToken'])->name('verify.candidat');
    Route::post('/candidat-logout', [CandidatAuthController::class, 'logout'])->name('candidat.logout');
    Route::get('/candidat-login', fn() => 'login')->name('candidat-login');
    Route::get('/donnees-personnelles', fn() => 'dp')->name('donnees-personnelles');
    app('router')->getRoutes()->refreshNameLookups();

    Mail::fake();
});

it('sendLink cree un nouveau candidat et envoie le mail', function () {
    $this->post('/send-link', ['email' => 'nouveau@test.ch'])
        ->assertRedirect()
        ->assertSessionHas('status');

    $this->assertDatabaseHas('candidats', ['email' => 'nouveau@test.ch']);
    Mail::assertSent(CandidatLoginMail::class);
});

it('sendLink met a jour le token si le candidat existe deja', function () {
    $candidat = Candidat::create(['email' => 'existant@test.ch', 'token' => 'ancien-token', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->post('/send-link', ['email' => 'existant@test.ch'])
        ->assertRedirect()
        ->assertSessionHas('status');

    $candidat->refresh();
    expect($candidat->token)->not->toBe('ancien-token');
    Mail::assertSent(CandidatLoginMail::class);
});

it('sendLink bloque un candidat avec candidature complete', function () {
    Candidat::create(['email' => 'complet@test.ch', 'token' => 'tok', 'statut' => 'Candidature complète', 'date_creation' => now()]);

    $response = $this->from('/candidat-login')
        ->post('/send-link', ['email' => 'complet@test.ch']);

    expect($response->getSession()->get('errors')->getBag('default')->has('email'))->toBeTrue();
    Mail::assertNotSent(CandidatLoginMail::class);
});


it('verifyToken connecte le candidat avec un token valide', function () {
    $candidat = Candidat::create(['email' => 'verify@test.ch', 'token' => 'token-valide', 'statut' => 'nouveau', 'date_creation' => now()]);

    $this->get('/verify-candidat/token-valide')
        ->assertRedirect('/donnees-personnelles');

    expect(session('candidat_id'))->toBe($candidat->id);
});

it('verifyToken redirige avec erreur si token invalide', function () {
    $response = $this->get('/verify-candidat/token-inexistant');

    expect($response->getSession()->get('errors')->getBag('default')->has('email'))->toBeTrue();
});

it('logout supprime la session et redirige', function () {
    $this->withSession(['candidat_id' => 99])
        ->post('/candidat-logout')
        ->assertRedirect('/candidat-login');

    expect(session('candidat_id'))->toBeNull();
});