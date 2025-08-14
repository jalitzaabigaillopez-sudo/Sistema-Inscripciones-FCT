<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable; 

class Usuario extends Model
{
    use Encryptable;
    protected $encrypted = ['cedula', 'password'];

    protected $table = 'usuarios'; 
    protected $primaryKey= 'id_usuario';
    public $timestamps = true;    // se deben colocar las columnas created_at y updated_at
    protected $fillable = ['cedula', 'nombre',	'email', 'password', 'rol',	'estado'];
}
