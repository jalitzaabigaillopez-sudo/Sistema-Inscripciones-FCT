<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventos';
    protected $primaryKey = 'id_evento';
    public $timestamps = false;
    protected $fillable = ['nombre', 'descripcion', 'fecha_inicio_inscripcion', 'fecha_final_inscripcion', 'fecha_inicio', 'fecha_final', 'imagen', 'estado', 'id_tipo_evento '];

    // Obtener todas las modalidades asociadas
    public function modalidades()
    {
        return $this->belongsToMany(
            Modalidad::class,
            'modalidades_eventos',
            'id_evento',      // clave foránea de Evento en la pivote
            'id_modalidad'    // clave foránea de Modalidad en la pivote
        );
    }

    public function tipoEvento()
    {
        return $this->belongsTo(TipoEvento::class, 'id_tipo_evento');
    }
}
