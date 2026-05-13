<?php

uses(Tests\TestCase::class);

use App\Models\FichierJoint;
use App\Models\Candidat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('uses the correct table name', function () {
    $model = new FichierJoint();

    expect($model->getTable())->toBe('fichiers_joints');
});

it('accepts mass assignment data', function () {
    $model = new FichierJoint([
        'candidat_id' => 8,
        'type_document' => 'CV',
        'nom_fichier' => 'cv_john.pdf',
        'chemin_fichier' => '/storage/uploads/cv_john.pdf'
    ]);

    expect($model->candidat_id)->toBe(8)
        ->and($model->type_document)->toBe('CV')
        ->and($model->nom_fichier)->toBe('cv_john.pdf')
        ->and($model->chemin_fichier)->toBe('/storage/uploads/cv_john.pdf');
});

it('belongs to a candidat', function () {
    $model = new FichierJoint();
    $relation = $model->candidat();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Candidat::class);
});