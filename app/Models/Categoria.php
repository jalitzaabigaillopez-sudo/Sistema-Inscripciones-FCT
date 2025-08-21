<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey= 'id_categoria';
    public $timestamps = false;    // se deben colocar las columnas created_at y updated_at
    // protected $fillable = [''];
}
