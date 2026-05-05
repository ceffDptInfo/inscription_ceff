<?php

use App\Http\Controllers\Auth\CandidatAuthController;
use App\Http\Controllers\Auth\SecretaireAuthController;
use App\Http\Controllers\AutreInscriptionController;
use App\Http\Controllers\ChoixApprentissageController;
use App\Http\Controllers\DonneesPersonnellesController;
use App\Http\Controllers\InformationsController;
use App\Http\Controllers\ParcoursScolaireController;
use App\Http\Controllers\RepresentantLegalController;
use App\Http\Controllers\StageController;
use App\Http\Middleware\EnsureCandidat;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Home');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

//secretaire login
Route::get('/secretaire-login', function () {
    return inertia('secretaire/SecretaireLogin');
})->name('secretaire-login');

Route::post('/secretaire-login', [SecretaireAuthController::class, 'login']);
Route::post('/secretaire-logout', [SecretaireAuthController::class, 'logout'])->name('secretaire.logout');

//candidat login
Route::get('/candidat-login', function () {
    return inertia('candidat/CandidatLogin');
})->name('candidat-login');

Route::post('send-link', [CandidatAuthController::class, 'sendLink']);
Route::get('/verify-candidat/{token}', [CandidatAuthController::class, 'verifyToken'])->name('verify.candidat');

Route::post('/candidat-logout', [CandidatAuthController::class, 'logout'])->name('candidat.logout');

//candidat routes
Route::middleware([EnsureCandidat::class])->group(function () {
    Route::get('/donnees-personnelles', [DonneesPersonnellesController::class, 'index'])->name('donnees-personnelles');

    Route::get('/annexes', function () {
        return inertia('candidat/Annexes');
    })->name('annexes');
    Route::get('/autres-inscriptions', [AutreInscriptionController::class, 'index'])->name('autres-inscriptions');
    Route::get('/choix-apprentissage', [ChoixApprentissageController::class, 'index'])->name('choix-apprentissage');
    Route::get('/informations', [InformationsController::class, 'index'])->name('informations');
    Route::get('/parcours-scolaire', [ParcoursScolaireController::class, 'index'])->name('parcours-scolaire');
    Route::get('/representants-legaux', [RepresentantLegalController::class, 'index'])->name('representants-legaux');
    Route::get('/stages', [StageController::class, 'index'])->name('stages');

    Route::post('/donnees-personnelles', [DonneesPersonnellesController::class, 'store'])->name('donnees-personnelles.store');
    Route::post('/representants-legaux', [RepresentantLegalController::class, 'store'])->name('representants-legaux.store');
    Route::post('/parcours-scolaire', [ParcoursScolaireController::class, 'store'])->name('parcours-scolaire.store');
    Route::post('/stages', [StageController::class, 'store'])->name('stages.store');
    Route::post('/autres-inscriptions', [AutreInscriptionController::class, 'store'])->name('autres-inscriptions.store');
    Route::post('/informations', [InformationsController::class, 'store'])->name('informations.store');
    Route::post('/choix-apprentissage', [ChoixApprentissageController::class, 'store'])->name('choix-apprentissage.store');
});



// secretaire routes
Route::get('/liste-candidats', function () {
    return inertia('secretaire/ListeCandidats');
})->name('liste-candidats')->middleware('auth:secretaire');



require __DIR__ . '/settings.php';
