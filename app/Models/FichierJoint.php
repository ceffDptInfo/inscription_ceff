<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichierJoint extends Model
{
    protected $table = 'fichiers_joints';
    protected $fillable = [
        'candidat_id',
        'type_document',
        'nom_fichier',
        'chemin_fichier'
    ];

    public $timestamps = false;

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
}
