<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadEvento extends Model
{
    protected $table = 'modalidades_eventos';
    protected $primaryKey = 'id_modalidad_evento';
    public $timestamps = false;
    protected $fillable = ['id_evento', 'id_modalidad'];


    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento', 'id_evento');
    }

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'id_modalidad', 'id_modalidad');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_modalidad_evento', 'id_modalidad_evento');
    }
}
