<?php

uses(Tests\TestCase::class);

use App\Models\RepresentantLegal;
use App\Models\Candidat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('uses the correct table name', function () {
    $model = new RepresentantLegal();

    expect($model->getTable())->toBe('representants_legaux');
});

it('accepts mass assignment data', function () {
    $model = new RepresentantLegal([
        'candidat_id' => 10,
        'ordre' => 1,
        'type_lien' => 'Pere',
        'nom' => 'Doe',
        'prenom' => 'John',
        'rue_et_num' => 'Rue test 1',
        'npa' => '1000',
        'localite' => 'Lausanne',
        'tel_fixe' => '0210000000',
        'tel_portable' => '0790000000',
        'email' => 'parent@test.com'
    ]);

    expect($model->candidat_id)->toBe(10)
        ->and($model->ordre)->toBe(1)
        ->and($model->type_lien)->toBe('Pere')
        ->and($model->nom)->toBe('Doe')
        ->and($model->prenom)->toBe('John')
        ->and($model->rue_et_num)->toBe('Rue test 1')
        ->and($model->npa)->toBe('1000')
        ->and($model->localite)->toBe('Lausanne')
        ->and($model->tel_fixe)->toBe('0210000000')
        ->and($model->tel_portable)->toBe('0790000000')
        ->and($model->email)->toBe('parent@test.com');
});

it('belongs to a candidat', function () {
    $model = new RepresentantLegal();
    $relation = $model->candidat();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Candidat::class);
});