<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadEvento extends Model
{
    protected $table = 'modalidades_eventos';
    protected $primaryKey= 'id_modalidad_evento';
    public $timestamps = false;    
    protected $fillable = ['id_evento', 'id_modalidad'];
}
