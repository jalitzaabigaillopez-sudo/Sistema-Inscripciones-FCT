<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function verificarUsuario(Request $request)
    {
        // VERIFICAR DATOS  DE ENTRADA
        $validateData = $request->validate([
                'correo' => 'required|string',
                'password' => 'required|string',
        ]); 

        $correo = $request->input('correo');
        $password = $request->input('password');

        $usuario = Usuario::where('correo', $correo)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        if ($password === $usuario->password) {
            return response()->json(['mensaje' => 'Login correcto']);
        } else {
            return response()->json(['error' => 'Contraseña incorrecta'], 401);
        } 
    }
}
