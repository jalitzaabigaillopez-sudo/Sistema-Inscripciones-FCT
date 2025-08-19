<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Academia;
use App\Models\ContraseñaTemporal;
use App\Mail\FCTMail;
use Illuminate\Support\Facades\Mail;
use App\Services\PasswordGenerator;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class DBController extends Controller
{
    // 127.0.0.1:8000/insertUser
    public function insertUser()
    {
        $usuario = new Usuario();

        $usuario->identificacion = '111222333';
        $usuario->nombre_completo = 'John Chaves';
        $usuario->email = 'JHON.CHAVES@ucr.ac.cr';
        $usuario->password = '12345678';
        $usuario->rol = 'administrador';
        $usuario->estado = 'activo';
        $usuario->password_vencimiento = 180;

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
        $generator = new PasswordGenerator();
        $temporalPass = $generator->generate(12);

        // 1
        $usuario = new Usuario();
        $usuario->identificacion = '100020003';
        $usuario->nombre_completo = 'John Chaves';//encargado de academia
        $usuario->email = 'JHON.CHAVES@ucr.ac.cr';//c
        $usuario->password = $temporalPass;//contraseña temporal
        $usuario->rol = 'administrador'; 
        $usuario->estado = 'inactivo';//inactivo por default
        $usuario->password_vencimiento = 180;//inactivo por default
        $usuario->save();

        // 2
        $contraseñaTemporal = new ContraseñaTemporal();
        $contraseñaTemporal->id_usuario = $usuario->id_usuario;  
        $contraseñaTemporal->password_temporal = $temporalPass;//contraseña temporal
        $fecha_creacion = Carbon::now('America/Costa_Rica'); 
        $fecha_expiracion = Carbon::now('America/Costa_Rica')->addMinutes(2); // DEFINIR EL TIEMPO MAXIMO c
        $contraseñaTemporal->fecha_creacion = $fecha_creacion;  
        $contraseñaTemporal->fecha_expiracion = $fecha_expiracion; 
        $contraseñaTemporal->vigente = 'si'; 
        $contraseñaTemporal->save();
     
        // 3
        $academia = new Academia();
        $academia->nombre = 'Academia los patitos';
        $academia->profesor_encargado = $usuario->nombre_completo;
        $academia->direccion = '300 metros sur 400 m norte';
        $academia->correo = $usuario->email;//c
        $academia->telefono = '12344356';
        $academia->estado = 'activo';//por default
        $academia->id_usuario = $usuario->id_usuario;       
        $academia->id_distrito = 1;     
        $academia->save();

        // 4
        $url = route('activar.cuenta', ['id' => $usuario->id_usuario]);
        Mail::to($usuario->email)->send(new FCTMail($usuario, $contraseñaTemporal, $url));

        echo "pre-registro de academia completado, se ha enviado un correo.";
    }

    // 127.0.0.1:8000/pre_registroAcademia1
    public function pre_registroAcademia1 ()
    {
        $generator = new PasswordGenerator();
        $temporalPass = $generator->generate(12);

        // 1
        $usuario = new Usuario();
        $usuario->identificacion = '100020004';
        $usuario->nombre_completo = 'John Chaves';//encargado de academia
        $usuario->email = 'johnchaves2002@gmail.com';//c
        $usuario->password = $temporalPass;//contraseña temporal
        $usuario->rol = 'administrador'; 
        $usuario->estado = 'inactivo';//inactivo por default
        $usuario->password_vencimiento = 180;//inactivo por default
        $usuario->save();

        // 2
        $contraseñaTemporal = new ContraseñaTemporal();
        $contraseñaTemporal->id_usuario = $usuario->id_usuario;  
        $contraseñaTemporal->password_temporal = $temporalPass;//contraseña temporal
        $fecha_creacion = Carbon::now('America/Costa_Rica'); 
        $fecha_expiracion = Carbon::now('America/Costa_Rica')->addMinutes(2); // DEFINIR EL TIEMPO MAXIMO c
        $contraseñaTemporal->fecha_creacion = $fecha_creacion;  
        $contraseñaTemporal->fecha_expiracion = $fecha_expiracion; 
        $contraseñaTemporal->vigente = 'si'; 
        $contraseñaTemporal->save();
     
        // 3
        $academia = new Academia();
        $academia->nombre = 'Academia UNO';
        $academia->profesor_encargado = $usuario->nombre_completo;
        $academia->direccion = '500 metros sur 600 m norte';
        $academia->correo = $usuario->email;//c
        $academia->telefono = '33442435';
        $academia->estado = 'activo';//por default
        $academia->id_usuario = $usuario->id_usuario;       
        $academia->id_distrito = 5;     
        $academia->save();

        // 4
        $url = route('activar.cuenta', ['id' => $usuario->id_usuario]);
        Mail::to($usuario->email)->send(new FCTMail($usuario, $contraseñaTemporal, $url));

        echo "pre-registro de academia completado, se ha enviado un correo.";
    }
}
