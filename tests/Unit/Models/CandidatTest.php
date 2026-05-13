<?php

uses(Tests\TestCase::class);

use App\Models\Candidat;
use App\Models\AutorisationHorsCanton;
use App\Models\AutreInscription;
use App\Models\ChoixApprentissage;
use App\Models\Compensations;
use App\Models\DonneesPersonnelles;
use App\Models\FichierJoint;
use App\Models\ParcoursScolaire;
use App\Models\RepresentantLegal;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

it('accepts mass assignment data', function () {
    $model = new Candidat([
        'email' => 'john.doe@example.com',
        'token' => 'random-token-123',
        'statut' => 'Nouveau Candidat',
        'dossier_nom' => 'dossier_john',
        'date_creation' => '2026-05-13'
    ]);

    expect($model->email)->toBe('john.doe@example.com')
        ->and($model->token)->toBe('random-token-123')
        ->and($model->statut)->toBe('Nouveau Candidat')
        ->and($model->dossier_nom)->toBe('dossier_john')
        ->and($model->date_creation)->toBe('2026-05-13');
});

it('has correct hasOne relations', function () {
    $model = new Candidat();

    expect($model->autorisationHorsCanton())->toBeInstanceOf(HasOne::class)
        ->and($model->autorisationHorsCanton()->getRelated())->toBeInstanceOf(AutorisationHorsCanton::class)
        ->and($model->choixApprentissage())->toBeInstanceOf(HasOne::class)
        ->and($model->choixApprentissage()->getRelated())->toBeInstanceOf(ChoixApprentissage::class)
        ->and($model->compensations())->toBeInstanceOf(HasOne::class)
        ->and($model->compensations()->getRelated())->toBeInstanceOf(Compensations::class)
        ->and($model->donneesPersonnelles())->toBeInstanceOf(HasOne::class)
        ->and($model->donneesPersonnelles()->getRelated())->toBeInstanceOf(DonneesPersonnelles::class)
        ->and($model->parcoursScolaire())->toBeInstanceOf(HasOne::class)
        ->and($model->parcoursScolaire()->getRelated())->toBeInstanceOf(ParcoursScolaire::class);
});

it('has correct hasMany relations', function () {
    $model = new Candidat();

    expect($model->autresInscriptions())->toBeInstanceOf(HasMany::class)
        ->and($model->autresInscriptions()->getRelated())->toBeInstanceOf(AutreInscription::class)
        ->and($model->fichiersJoints())->toBeInstanceOf(HasMany::class)
        ->and($model->fichiersJoints()->getRelated())->toBeInstanceOf(FichierJoint::class)
        ->and($model->representantsLegaux())->toBeInstanceOf(HasMany::class)
        ->and($model->representantsLegaux()->getRelated())->toBeInstanceOf(RepresentantLegal::class)
        ->and($model->stages())->toBeInstanceOf(HasMany::class)
        ->and($model->stages()->getRelated())->toBeInstanceOf(Stage::class);
});