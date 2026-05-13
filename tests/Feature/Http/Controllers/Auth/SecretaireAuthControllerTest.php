<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\Secretaire;
use App\Http\Controllers\Auth\SecretaireAuthController;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::middleware('web')->group(function () {
        Route::post('/secretaire-login', [SecretaireAuthController::class, 'login']);
        Route::post('/secretaire-logout', [SecretaireAuthController::class, 'logout'])->name('secretaire.logout');
        Route::post('/secretaire-store', [SecretaireAuthController::class, 'store']);
        Route::get('/secretaire-login', fn() => 'login')->name('secretaire-login');
        Route::get('/liste-candidats', fn() => 'liste')->name('liste-candidats');
    });
    app('router')->getRoutes()->refreshNameLookups();
});

it('login avec identifiants valides redirige vers liste-candidats', function () {
    Secretaire::create(['email' => 'sec@test.ch', 'password' => bcrypt('password')]);

    $this->post('/secretaire-login', ['email' => 'sec@test.ch', 'password' => 'password'])
        ->assertRedirect('liste-candidats');
});

it('login avec identifiants invalides retourne une erreur', function () {
    Secretaire::create(['email' => 'sec@test.ch', 'password' => bcrypt('password')]);

    $response = $this->from('/secretaire-login')
        ->post('/secretaire-login', ['email' => 'sec@test.ch', 'password' => 'mauvais']);

    $errors = $response->getSession()->get('errors');
    expect(isset($errors['default']['messages']['email']))->toBeTrue();
});

it('logout deconnecte et redirige vers secretaire-login', function () {
    $secretaire = Secretaire::create(['email' => 'sec@test.ch', 'password' => bcrypt('password')]);

    $this->actingAs($secretaire, 'secretaire')
        ->post('/secretaire-logout')
        ->assertRedirect('/secretaire-login');
});

it('store cree un nouveau secretaire et redirige', function () {
    $this->post('/secretaire-store', ['email' => 'nouveau@test.ch', 'password' => 'secret'])
        ->assertRedirect(route('secretaire-login'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('secretaires', ['email' => 'nouveau@test.ch']);
});