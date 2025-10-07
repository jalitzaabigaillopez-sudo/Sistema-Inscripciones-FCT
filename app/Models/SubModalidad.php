<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubModalidad extends Model
{
    protected $table = 'submodalidades';
    protected $primaryKey = 'id_subModalidad';
    public $timestamps = false;
    protected $fillable = ['nombre', 'descripcion', 'cantidad_atletas', 'sexo_mixto'];

    protected $casts = [
        'sexo_mixto' => 'boolean',
    ];


    public function modalidades()
    {
        return $this->belongsTo(Modalidad::class, 'id_modalidad');
    }
}
