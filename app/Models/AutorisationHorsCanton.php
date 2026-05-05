<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutorisationHorsCanton extends Model
{
    protected $fillable = [
        'candidat_id',
        'reponse'
    ];

    public $timestamps = false;

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
}
