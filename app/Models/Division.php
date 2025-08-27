<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'divisiones';
    protected $primaryKey = 'id_division';
    public $timestamps = false;
    protected $fillable = ['division', 'year_min', 'year_max'];
}
