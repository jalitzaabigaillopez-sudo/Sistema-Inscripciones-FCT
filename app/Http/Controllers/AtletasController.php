<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AtletasController extends Controller
{
    public function insertarAtleta(Request $request){

        $validateData = $request->validate([
                'tipo_identificacion' => 'required|string|in:nacional,otro',
                'identificacioncedula' => 'required|string',
                'primer_apellido' => 'required|string',
                'segundo_apellido' => 'required|string',
                'nombre' => 'required|string',
                'rol' => 'required|string|in:entrenador,asistente. atleta',
                'sexo' => 'required|string|in:Femenino,Masculino',
                'fecha_nacimiento' => 'required|date',
                'estado' => 'require|string|in:activo,inactivo',
                
                
                //Recordar: categorias, grados se cargan en la vista de creacion de atletas:
                'id_categoria' => 'required|integer',
                'id_grado' => 'required|integer',
                // 'id_padron_nacimiento' => 'required|integer',//No se puede validar aqui
                'id_academia' => 'required|integer',//id_de academia actual "registrante"
        ]); 

        
    }
}
