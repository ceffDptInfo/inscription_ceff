<?php

uses(Tests\TestCase::class);

use App\Models\DonneesPersonnelles;
use App\Models\Candidat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('accepts mass assignment data', function () {
    $model = new DonneesPersonnelles([
        'candidat_id' => 7,
        'nom' => 'Doe',
        'prenom' => 'John',
        'rue_et_num' => 'Rue de la Gare 1',
        'npa_localite' => '1000 Lausanne',
        'date_naissance' => '2005-08-15',
        'langue_maternelle' => 'Français',
        'no_avs' => '756.1234.5678.90',
        'tel_fixe' => '0211234567',
        'tel_portable' => '0791234567',
        'email_prive' => 'john.doe@test.ch',
        'genre' => 'Homme',
        'nationalite' => 'Suisse',
        'pays_origine' => '',
        'type_permis' => '',
        'validite_permis' => '',
        'remarques' => ''
    ]);

    expect($model->candidat_id)->toBe(7)
        ->and($model->nom)->toBe('Doe')
        ->and($model->prenom)->toBe('John')
        ->and($model->rue_et_num)->toBe('Rue de la Gare 1')
        ->and($model->npa_localite)->toBe('1000 Lausanne')
        ->and($model->date_naissance)->toBe('2005-08-15')
        ->and($model->langue_maternelle)->toBe('Français')
        ->and($model->no_avs)->toBe('756.1234.5678.90')
        ->and($model->tel_fixe)->toBe('0211234567')
        ->and($model->tel_portable)->toBe('0791234567')
        ->and($model->email_prive)->toBe('john.doe@test.ch')
        ->and($model->genre)->toBe('Homme')
        ->and($model->nationalite)->toBe('Suisse')
        ->and($model->pays_origine)->toBe('')
        ->and($model->type_permis)->toBe('')
        ->and($model->validite_permis)->toBe('')
        ->and($model->remarques)->toBe('');
});

it('belongs to a candidat', function () {
    $model = new DonneesPersonnelles();
    $relation = $model->candidat();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Candidat::class);
});