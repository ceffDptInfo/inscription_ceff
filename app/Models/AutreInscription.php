<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutreInscription extends Model
{
    protected $table = 'autres_inscriptions';
    protected $fillable = [
        'candidat_id',
        'etablissement',
        'lieu'
    ];

    public $timestamps = false;

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
}
