<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Academia;
use App\Mail\FCTMail;
use Illuminate\Support\Facades\Mail;

class AcademiaController extends Controller
{
//####################################### SOLO ADMINISTRADOR ###########################################
    public function pre_registroAcademia(Request $request)
    {
        // VERIFICAR DATOS  DE ENTRADA
        $validateData = $request->validate([
                'cedula' => 'string',//no requerido
                'nombreAcademia' => 'required|string', //encargado de academia
                'email' => 'required|string|email',
                'password' => 'required|string',
                'rol' => 'required|string|in:administrador,academia,arbitro',
                'estado' => 'required|string|in:activo,inactivo,pendiente',//debe de venir como pendiente
                'imagen'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
         
                'profesor_encargado' => 'required|string',
                'canton' => 'required|string', 
                'provincia' => 'required|string',
                'direccion' => 'required|string',
                'telefono' => 'required|string',
                // 'estado' => 'required|string|in:activo,inactivo',
        ]); 
        
        // Cedula puede ingresarse despues, puede estar en blanco en pre-registro
        if (empty($validateData['cedula'])) {
            $validateData['cedula'] = 'por completar';
        }

        // Guardar el usuario academia como pendiente
        $usuario = Usuario::create([
        'cedula' => $validateData['cedula'],//se puede completar despues si no se sabe
        'nombre' => $validateData['nombre'],//encargado de academia
        'email' => $validateData['email'],//c
        'password' => $validateData['password'],//contraseña temporal
        'rol' => $validateData['rol'],
        'estado' => $validateData['estado'],
        'imagen' => $validateData['imagen'],
        ]);
        $usuario->save();

        // Se guarda la academia ligada al usuario creado
        $academia = Academia::create([
        'id_usuario' => $usuario->id_usuario,
        'nombre' => $validateData['nombre'],//nombre de academia
        'canton' => $validateData['canton'],//c
        'provincia' => $validateData['provincia'],
        'profesor_encargado' => $usuario->nombre,
        'direccion' => $validateData['direccion'],
        'correo' => $usuario->email,
        'telefono' => $validateData['telefono'],
        'estado' => 'activo',
        ]);
        $academia->save();


        // Se envia correo a la academia
    }

//####################################### SOLO ACADEMIA #################################################
     public function activarCuenta($id)
    {
        $usuario = Usuario::find($id);
        return view('sections/completarRegistroAcademia', compact('usuario'));
    } 
}
