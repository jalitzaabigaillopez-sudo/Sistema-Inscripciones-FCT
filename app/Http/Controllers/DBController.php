<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Academia;
use App\Mail\FCTMail;
use Illuminate\Support\Facades\Mail;

class DBController extends Controller
{
    // 127.0.0.1:8000/insertUser
    public function insertUser()
    {
        $usuario = new Usuario();

        $usuario->cedula = '111222333';
        $usuario->nombre = 'John';
        $usuario->email = 'john@example.com';
        $usuario->password = '12345678';
        $usuario->rol = 'academia';
        $usuario->estado = 'activo';

        $usuario->save();
        
        echo "usuario insertado";
    }

    // 127.0.0.1:8000/selectUser
    public function selectUser()
    {
        $usuario = Usuario::find(1);
        echo($usuario->username." ".$usuario->password." ".$usuario->correo);
    }

    // 127.0.0.1:8000/pre_registroAcademia
    public function pre_registroAcademia ()
    {
        $temporalPass = $this->generarPassword();

        // 1
        $usuario = new Usuario();
        $usuario->cedula = 'por completar';
        $usuario->nombre = 'Daniel Umaga';//encargado de academia
        $usuario->email = 'JHON.CHAVES@ucr.ac.cr';//c
        $usuario->password = $temporalPass;//contraseña temporal
        $usuario->rol = 'academia'; 
        $usuario->estado = 'pendiente';//pendiente por default
        $usuario->save();
     
        // 2
        $academia = new Academia();
        $academia->id_usuario = $usuario->id_usuario;
        $academia->nombre = 'Academia los patitos';
        $academia->canton = 'Alajuela';
        $academia->provincia = 'Alajuela';
        $academia->profesor_encargado = $usuario->nombre;;
        $academia->direccion = '300 metro sur 400 m norte';
        $academia->correo = $usuario->email;//c
        $academia->telefono = '12344356';
        $academia->estado = 'activo';//por default
        $academia->save();

        // 3
        Mail::to($usuario->email)->send(new FCTMail($usuario));

        echo "pre-registro de academia completado, se ha enviado un correo.";
    }

    // Método para generar contraseña aleatoria
    public function generarPassword($longitud = 8)
    {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $password = '';

        for ($i = 0; $i < $longitud; $i++) {
            $password .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }

        return $password;
    }
}
