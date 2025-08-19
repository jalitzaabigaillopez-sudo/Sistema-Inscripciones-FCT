<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable; 

class Atleta extends Model
{
    use Encryptable;
    protected $encrypted = [];

    protected $table = 'atletas';
    protected $primaryKey= 'id_atleta';
    public $timestamps = true;    // se deben colocar las columnas created_at y updated_at
    protected $fillable = ['tipo_identificacion', 'identificacion', 'primer_apellido', 'segundo_apellido', 'nombre', 'rol', 'sexo', 'fecha_nacimiento', 'estado', 'id_categoria', 'id_grado', 'id_padron_nacimiento', 'id_academia'];

}
