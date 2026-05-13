<?php

uses(Tests\TestCase::class);

use App\Models\ParcoursScolaire;
use App\Models\Candidat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('accepts mass assignment data', function () {
    $model = new ParcoursScolaire([
        'candidat_id' => 9,
        'type_parcours' => 'Scolaire',
        'nom_ecole' => 'Gymnase de Bienne',
        'lieu_ecole' => 'Bienne',
        'niveau_francais' => 'A',
        'niveau_math' => 'A',
        'niveau_allemand' => 'B',
        'description_activite' => ''
    ]);

    expect($model->candidat_id)->toBe(9)
        ->and($model->type_parcours)->toBe('Scolaire')
        ->and($model->nom_ecole)->toBe('Gymnase de Bienne')
        ->and($model->lieu_ecole)->toBe('Bienne')
        ->and($model->niveau_francais)->toBe('A')
        ->and($model->niveau_math)->toBe('A')
        ->and($model->niveau_allemand)->toBe('B')
        ->and($model->description_activite)->toBe('');
});

it('belongs to a candidat', function () {
    $model = new ParcoursScolaire();
    $relation = $model->candidat();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Candidat::class);
});