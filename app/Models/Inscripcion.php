<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';
    protected $primaryKey= 'id_inscripcion';
    public $timestamps = false;    
    protected $fillable = ['id_atleta', 'id_modalidad_evento', 'fecha_inscripcion', 'estado'];
}
