<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey= 'id_categoria';
    public $timestamps = false;    // se deben colocar las columnas created_at y updated_at
    protected $fillable = ['sexo', 'peso_min', 'peso_max', 'id_division'];

    public function atletas()
    {
        return $this->hasMany(Atleta::class, 'id_categoria');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'id_division');
    }

}
