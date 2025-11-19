<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use mysqli;


class Evento extends Model
{
    protected $table = 'eventos';
    protected $primaryKey = 'id_evento';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio_inscripcion',
        'fecha_final_inscripcion',
        'fecha_final_inscripcion_tardia',
        'fecha_inicio',
        'fecha_final',
        'imagen',
        'estado',
        'id_tipo_evento ',
        'costo_temprana_1',
        'costo_temprana_2',
        'costo_tardia_1',
        'costo_tardia_2'
    ];

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

    public function academia()
    {
        return $this->belongsTo(Academia::class, 'id_academia');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_evento');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'id_division', 'id_division');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento', 'id_evento');
    }
    public function atleta()
    {
        return $this->belongsTo(Atleta::class, 'id_atleta', 'id_atleta');
    }
    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'id_modalidad', 'id_modalidad');
    }
    public function submodalidad()
    {
        return $this->belongsTo(Submodalidad::class, 'id_submodalidad', 'id_submodalidad');
    }
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

    // Método para calcular el costo por atleta según modalidades y fechas
    
public function calcularCostoPorAtleta(int $modalidadesUnicas, bool $esTemprana): float
{
    if ($modalidadesUnicas <= 1) {
        return $esTemprana
            ? (float) ($this->costo_temprana_1 ?? 0)
            : (float) ($this->costo_tardia_1 ?? 0);
    }

    return $esTemprana
        ? (float) ($this->costo_temprana_2 ?? 0)
        : (float) ($this->costo_tardia_2 ?? 0);
}

}
