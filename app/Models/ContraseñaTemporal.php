<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContraseñaTemporal extends Model
{
    protected $table = 'password_temporales'; 
    protected $primaryKey= 'id_password_temp';
    public $timestamps = false;
    protected $fillable = ['id_usuario', 'password_temporal', 'fecha_creacion', 'fecha_expiracion', 'vigente'];
}
