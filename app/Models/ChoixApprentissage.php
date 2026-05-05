<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChoixApprentissage extends Model
{
    protected $fillable = [
        'candidat_id',
        'premier_choix',
        'deuxieme_choix',
    ];

    public $timestamps = false;

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
}
