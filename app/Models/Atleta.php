<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable; 

class Atleta extends Model
{
    use Encryptable;
    protected $encrypted = [''];

    protected $table = 'atletas';
    protected $primaryKey= 'id_atleta';
    public $timestamps = true;    // se deben colocar las columnas created_at y updated_at
    protected $fillable = ['id_atleta', 'nombre', 'cedula', 'año_nacimiento', 'edad', 'sexo', 'cinturon'];

}
