<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    protected $fillable = [
        'email',
        'token',
        'statut',
        'dossier_nom',
        'date_creation'
    ];

    public $timestamps = false;

    public function autorisationHorsCanton()
    {
        return $this->hasOne(AutorisationHorsCanton::class);
    }

    public function autresInscriptions()
    {
        return $this->hasMany(AutreInscription::class);
    }

    public function choixApprentissage()
    {
        return $this->hasOne(ChoixApprentissage::class);
    }

    public function compensations()
    {
        return $this->hasOne(Compensations::class);
    }

    public function donneesPersonnelles()
    {
        return $this->hasOne(DonneesPersonnelles::class);
    }

    public function fichiersJoints()
    {
        return $this->hasMany(FichierJoint::class);
    }

    public function parcoursScolaire()
    {
        return $this->hasOne(ParcoursScolaire::class);
    }

    public function representantsLegaux()
    {
        return $this->hasMany(RepresentantLegal::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }
}
