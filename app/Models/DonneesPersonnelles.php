<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonneesPersonnelles extends Model
{
    protected $fillable = [
        'candidat_id',
        'nom',
        'prenom',
        'rue_et_num',
        'npa',
        'localite',
        'date_naissance',
        'langue_maternelle',
        'no_avs',
        'tel_fixe',
        'tel_portable',
        'email_prive',
        'genre',
        'nationalite',
        'lieu_origine',
        'pays_origine',
        'type_permis',
        'validite_permis',
        'remarques'
    ];

    public $timestamps = false;

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
}
