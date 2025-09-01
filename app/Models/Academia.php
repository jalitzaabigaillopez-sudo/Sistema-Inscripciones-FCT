<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Academia extends Model
{

    use Encryptable;
    protected $encrypted = [];

    protected $table = 'academias';
    protected $primaryKey = 'id_academia';
    public $timestamps = true;    // se deben colocar las columnas created_at y updated_at
    protected $fillable = ['nombre', 'profesor_encargado', 'direccion', 'correo', 'telefono', 'estado', 'id_usuario', 'id_distrito'];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_academia');
    }

    public function atletas()
    {
        return $this->hasMany(Atleta::class, 'id_academia');
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'id_distrito');
    }

     public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
