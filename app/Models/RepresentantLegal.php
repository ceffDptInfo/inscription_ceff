<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepresentantLegal extends Model
{

    public $table = 'representants_legaux';
    protected $fillable = [
        'candidat_id',
        'ordre',
        'type_lien',
        'nom',
        'prenom',
        'rue_et_num',
        'npa_localite',
        'tel_fixe',
        'tel_portable',
        'email'
    ];

    public $timestamps = false;

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
}
