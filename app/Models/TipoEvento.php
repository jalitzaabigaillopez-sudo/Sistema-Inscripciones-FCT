<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEvento extends Model
{
    protected $table = 'tipos_eventos';
    protected $primaryKey = 'id_tipo_evento';
    public $timestamps = false;
    protected $fillable = ['nombre', 'descripcion', 'modo'];

    public function eventos()
    {
        return $this->hasMany(TipoEvento::class, 'id_evento');
    }

}
