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
                'email' => 'required|string',
                'password' => 'required|string',
        ]); 

        $correo = $request->input('email');
        $password = $request->input('password');

        $usuario = Usuario::where('email', $correo)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        if ($password === $usuario->password) {

            if($usuario->estado === 'activo'){
                $request->session()->put('usuario', $usuario->id_usuario);
                return redirect()->route('dashboard');

            } else if($usuario->estado === 'inactivo'){
                return response()->json(['error' => 'Este usuario no se encuentra activo actualmente.'], 401);
            }

            return response()->json(['error' => 'Usuario pendiente de completar registro'], 401);
        } else {
            return response()->json(['error' => 'Contraseña incorrecta'], 401);
        } 
    }

    /**
     * 
     */
    public function cerrarSesion(Request $request)
    {
        $request->session()->forget('usuario'); 
        return redirect()->route('login');
    }
}
