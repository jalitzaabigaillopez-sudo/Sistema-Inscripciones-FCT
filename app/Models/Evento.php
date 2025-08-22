<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventos';
    protected $primaryKey= 'id_evento';
    public $timestamps = false;    
    protected $fillable = ['nombre', 'descripcion', 'fecha_inicio_inscripcion', 'fecha_final_inscripcion', 'fecha_inicio', 'fecha_final', 'imagen', 'estado','id_tipo_evento '];
}
