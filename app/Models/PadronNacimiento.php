<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PadronNacimiento extends Model
{
    protected $table = 'padron_nacimientos';
    protected $primaryKey= 'id_padron_nacimiento';
    public $timestamps = false;    // se deben colocar las columnas created_at y updated_at
    // protected $fillable = ['tipo_identificacion', 'identificacion', 'primer_apellido', 'segundo_apellido', 'fecha_nacimiento'];
}
