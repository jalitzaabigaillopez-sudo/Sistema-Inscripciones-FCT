<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';
    protected $primaryKey = 'id_inscripcion';
    public $timestamps = false;
    protected $fillable = ['id_academia', 'id_atleta', 'id_evento', 'id_modalidad', 'id_subModalidad', 'id_categoria', 'fecha_inscripcion', 'tipo_inscripcion', 'estado', 'peso', 'codigo_equipo', 'rol'];

    public function atletas()
    {
        return $this->hasMany(Atleta::class, 'id_atleta', 'id_atleta');
    }
    public function academia()//bt_export
    {
        return $this->belongsTo(Academia::class, 'id_academia', 'id_academia');
    }
    public function atleta()//bt_export
    {
        return $this->belongsTo(Atleta::class, 'id_atleta', 'id_atleta');
    }

    public function evento()//bt_export
    {
        return $this->belongsTo(Evento::class, 'id_evento', 'id_evento');
    }

    public function modalidad()//bt_export
    {
        return $this->belongsTo(Modalidad::class, 'id_modalidad', 'id_modalidad');
    }

    public function subModalidad()//bt_export
    {
        return $this->belongsTo(SubModalidad::class, 'id_subModalidad', 'id_subModalidad');
    }

    public function categoria()//bt_export
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function modalidadEvento()
    {
        return $this->belongsTo(ModalidadEvento::class, 'id_modalidad_evento', 'id_modalidad_evento');
    }

    public function grado()
{
    return $this->belongsTo(Grado::class, 'id_grado'); // ajustá el nombre de la columna si es diferente
}


    /*
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
        */
}
