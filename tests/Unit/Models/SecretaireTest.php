<?php

uses(Tests\TestCase::class);

use App\Models\Secretaire;

it('accepts mass assignment data', function () {
    $model = new Secretaire([
        'email' => 'secretaire@ecole.ch',
        'password' => 'motdepasse123'
    ]);

    expect($model->email)->toBe('secretaire@ecole.ch')
        ->and($model->password)->toBe('motdepasse123');
});

it('hides password in arrays', function () {
    $model = new Secretaire([
        'email' => 'secretaire@ecole.ch',
        'password' => 'motdepasse123'
    ]);

    $array = $model->toArray();

    expect($array)->not->toHaveKey('password')
        ->and($array)->toHaveKey('email');
});