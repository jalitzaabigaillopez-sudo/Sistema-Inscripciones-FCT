<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Usuario extends Model
{
    use Encryptable;
    protected $encrypted = ['password'];

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;    // se deben colocar las columnas created_at y updated_at
    protected $fillable = ['identificacion', 'nombre_completo', 'email', 'password', 'rol', 'estado', 'password_vencimiento'];

    public function academia()
    {
        return $this->belongsTo(Academia::class, 'id_usuario', 'id_usuario');
    }
}
