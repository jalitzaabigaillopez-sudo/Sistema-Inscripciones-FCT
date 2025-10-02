<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Atleta extends Model
{
    use Encryptable;
    protected $encrypted = [];

    protected $table = 'atletas';
    protected $primaryKey = 'id_atleta';
    public $timestamps = false;    // se deben colocar las columnas created_at y updated_at
    protected $fillable = ['tipo_identificacion', 'identificacion', 'primer_apellido', 'segundo_apellido', 'nombre', 'sexo', 'fecha_nacimiento', 'estado', 'imagen', 'id_division', 'id_grado', 'id_padron_nacimiento', 'id_academia'];


    public function academias()
    {
        return $this->belongsTo(Academia::class, 'id_academia', 'id_academia');
    }

    public function categorias()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

      public function grados()
    {
        return $this->belongsTo(Grado::class, 'id_grado');
    }

}
