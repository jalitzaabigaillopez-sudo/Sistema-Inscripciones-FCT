<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grados';
    protected $primaryKey= 'id_grado';
    public $timestamps = false;    
    protected $fillable = ['nombre', 'descripcion'];

    public function atletas()
    {
        return $this->hasMany(Atleta::class, 'id_grado');
    }

    
}
