<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventos';
    protected $primaryKey= 'id_evento';
    public $timestamps = false;    
    protected $fillable = ['nombre', 'descripcion', 'fecha_inicio_inscripcion', 'fecha_final_inscripcion', 'fecha_inicio', 'fecha_final', 'imagen', 'estado','id_tipo_evento '];

    public function tipoEvento()
    {
        return $this->belongsTo(TipoEvento::class, 'id_tipo_evento');
    }

    public function calendario()
{
    return view('calendario');
}

public function eventosJson()
{
    $eventos = Evento::all();

    $eventosFormateados = $eventos->map(function ($evento) {
        return [
            'id_evento' => $evento->id_evento,
            'nombre' => $evento->nombre,
            'descripcion' => $evento->descripcion,
            'fecha_inicio_inscripcion' => $evento->fecha_inicio_inscripcion,
            'fecha_final_inscripcion' => $evento->fecha_final_inscripcion,
            'fecha_inicio' => $evento->fecha_inicio,
            'fecha_final' => $evento->fecha_final,
            'imagen' => $evento->imagen,
            'estado' => $evento->estado,
            'id_tipo_evento' => $evento->id_tipo_evento,
        ];
    });

    return response()->json($eventosFormateados);
}
}
