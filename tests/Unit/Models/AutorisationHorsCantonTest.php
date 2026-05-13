<?php

uses(Tests\TestCase::class); //démarrer laravel

use App\Models\AutorisationHorsCanton;
use App\Models\Candidat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('accepts mass assignment data', function () {
    $model = new AutorisationHorsCanton([
        'candidat_id' => 1,
        'reponse' => '1'
    ]);

    expect($model->candidat_id)->toBe(1)
        ->and($model->reponse)->toBe('1');
});

it('belongs to a candidat', function () {
    $model = new AutorisationHorsCanton();
    $relation = $model->candidat();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Candidat::class);
});