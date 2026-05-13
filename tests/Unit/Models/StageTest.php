<?php

uses(Tests\TestCase::class);

use App\Models\Stage;

it('accepts mass assignment data', function () {
    $model = new Stage([
        'candidat_id' => 11,
        'metier' => 'Informaticien',
        'entreprise' => 'Google',
        'lieu' => 'Zurich',
        'duree' => '3 jours'
    ]);

    expect($model->candidat_id)->toBe(11)
        ->and($model->metier)->toBe('Informaticien')
        ->and($model->entreprise)->toBe('Google')
        ->and($model->lieu)->toBe('Zurich')
        ->and($model->duree)->toBe('3 jours');
});