<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = [
        'candidat_id',
        'metier',
        'entreprise',
        'lieu',
        'duree'
    ];

    public $timestamps = false;
}
