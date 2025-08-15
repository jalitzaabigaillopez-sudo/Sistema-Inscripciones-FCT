<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AtletasController extends Controller
{
    public function insertarAtleta(Request $request){

        $validateData = $request->validate([
                'nombre' => 'required|string',
                'cedula' => 'require|string',
                'año_nacimiento' => 'required|string|max:4',
                'edad' => 'required|integer|max:3',
                'sexo' => 'require|string',
                'cinturon' => 'required|string',
        ]); 
    }
}
