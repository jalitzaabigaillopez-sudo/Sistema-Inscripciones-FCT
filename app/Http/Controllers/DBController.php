<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class DBController extends Controller
{
    public function insert()
    {
        $usuario = new Usuario();

        $usuario->username = 'john1414';
        $usuario->password = '12345678';
        $usuario->correo = 'john@example.com';
        $usuario->tipo = 0;
        $usuario->activo = 1;

        $usuario->save();
        
        echo "usuario insertado";
    }

    public function select()
    {
        $usuario = Usuario::find(1);
        echo($usuario->username." ".$usuario->password." ".$usuario->correo);
    }
}
