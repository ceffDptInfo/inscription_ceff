<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Secretaire extends Authenticatable
{
    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = false;
}
