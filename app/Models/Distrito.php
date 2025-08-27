<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    protected $table = 'distritos';
    protected $primaryKey = 'id_distrito';
    public $timestamps = false;

    public function canton()
    {
        return $this->belongsTo(Canton::class, 'id_canton');
    }
}
