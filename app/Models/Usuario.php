<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable; 

class Usuario extends Model
{
    use Encryptable;
    protected $encrypted = ['username', 'password'];

    protected $table = 'usuarios'; 
    public $timestamps = true;    // se deben colocar las columnas created_at y updated_at
    protected $fillable = ['username', 'password',	'correo',	'tipo',	'activo'];
}
