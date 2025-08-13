<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Academia extends Model
{
    protected $table = 'academias';
    protected $primaryKey= 'id_academia';

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

}
