<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';
    protected $primaryKey = 'id_inscripcion';
    public $timestamps = false;
    protected $fillable = ['id_atleta', 'id_evento', 'id_modalidad', 'id_subModalidad', 'id_categoria', 'fecha_inscripcion', 'estado', 'codigo_equipo'];

    public function atleta()
    {
        return $this->belongsTo(Atleta::class, 'id_atleta', 'id_atleta');
    }

    public function modalidadEvento()
    {
        return $this->belongsTo(ModalidadEvento::class, 'id_modalidad_evento', 'id_modalidad_evento');
    }

    // Relación indirecta para acceder a Evento a través de ModalidadEvento
    public function evento()
    {
        return $this->hasOneThrough(
            Evento::class,
            ModalidadEvento::class,
            'id_modalidad_evento', // FK en ModalidadEvento
            'id_evento',           // PK en Evento
            'id_modalidad_evento', // FK en Inscripcion
            'id_evento'            // PK en ModalidadEvento
        );
    }

    // Relación indirecta para acceder a Modalidad a través de ModalidadEvento
    public function modalidad()
    {
        return $this->hasOneThrough(
            Modalidad::class,
            ModalidadEvento::class,
            'id_modalidad_evento', // FK en ModalidadEvento
            'id_modalidad',        // PK en Modalidad
            'id_modalidad_evento', // FK en Inscripcion
            'id_modalidad'         // PK en ModalidadEvento
        );
    }
}
