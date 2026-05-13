<?php

uses(Tests\TestCase::class);

use App\Models\AutreInscription;
use App\Models\Candidat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('uses the correct table name', function () {
    $model = new AutreInscription();

    expect($model->getTable())->toBe('autres_inscriptions');
});

it('accepts mass assignment data', function () {
    $model = new AutreInscription([
        'candidat_id' => 2,
        'etablissement' => 'Gymnase de Bienne',
        'lieu' => 'Bienne'
    ]);

    expect($model->candidat_id)->toBe(2)
        ->and($model->etablissement)->toBe('Gymnase de Bienne')
        ->and($model->lieu)->toBe('Bienne');
});

it('belongs to a candidat', function () {
    $model = new AutreInscription();
    $relation = $model->candidat();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Candidat::class);
});