<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'divisiones';
    protected $primaryKey = 'id_division';
    public $timestamps = false;
    protected $fillable = ['division', 'year_inicio', 'year_final'];

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'id_division', 'id_division');
    }

  public function categoria()
{
    return $this->belongsTo(\App\Models\Categoria::class, 'id_categoria', 'id_categoria');
}
}
