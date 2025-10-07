<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    protected $table = 'modalidades';
    protected $primaryKey= 'id_modalidad';
    public $timestamps = false;    
    protected $fillable = ['nombre', 'descripcion'];

    // Obtener todas las submodalidades asociadas
    public function subModalidades()
    {
        return $this->belongsToMany(
            SubModalidad::class,
            'modalidades_submodalidades',
            'id_modalidad',      // clave foránea de Modalidad en la pivote
            'id_subModalidad'    // clave foránea de Modalidad en la pivote
        );
    }

    // Eventos donde aparece esta modalidad
    public function eventos()
    {
        return $this->belongsToMany(
            Evento::class,
            'modalidades_eventos',
            'id_modalidad',   
            'id_evento'       
        );
    }
}
