<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    protected $table = 'modalidades';
    protected $primaryKey= 'id_modalidad';
    public $timestamps = false;    
    protected $fillable = ['nombre', 'descripcion'];
}
