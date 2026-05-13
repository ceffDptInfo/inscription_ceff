<?php

uses(Tests\TestCase::class);

use App\Models\ChoixApprentissage;
use App\Models\Candidat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('accepts mass assignment data', function () {
    $model = new ChoixApprentissage([
        'candidat_id' => 3,
        'premier_choix' => 'Informaticien',
        'deuxieme_choix' => 'Employe de commerce'
    ]);

    expect($model->candidat_id)->toBe(3)
        ->and($model->premier_choix)->toBe('Informaticien')
        ->and($model->deuxieme_choix)->toBe('Employe de commerce');
});

it('belongs to a candidat', function () {
    $model = new ChoixApprentissage();
    $relation = $model->candidat();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Candidat::class);
});