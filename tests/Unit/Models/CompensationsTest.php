<?php

uses(Tests\TestCase::class);

use App\Models\Compensations;
use App\Models\Candidat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('accepts mass assignment data', function () {
    $model = new Compensations([
        'candidat_id' => 5,
        'reponse' => '1'
    ]);

    expect($model->candidat_id)->toBe(5)
        ->and($model->reponse)->toBe('1');
});

it('belongs to a candidat', function () {
    $model = new Compensations();
    $relation = $model->candidat();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Candidat::class);
});