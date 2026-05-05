<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcoursScolaire extends Model
{
    protected $fillable = [
        'candidat_id',
        'type_parcours',
        'nom_ecole',
        'lieu_ecole',
        'niveau_francais',
        'niveau_math',
        'niveau_allemand',
        'description_activite'
    ];

    public $timestamps = false;

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
}
