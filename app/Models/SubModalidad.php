<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubModalidad extends Model
{
    protected $table = 'submodalidades';
    protected $primaryKey= 'id_subModalidad';
    public $timestamps = false;    
    protected $fillable = ['nombre', 'descripcion'];
}
