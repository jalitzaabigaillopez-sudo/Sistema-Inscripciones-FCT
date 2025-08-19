<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\URL;

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
            if($usuario->password_vencimiento > 0){
                if($usuario->estado === 'activo'){
                    $request->session()->put('usuario', $usuario->id_usuario);// colocar usuario en sesion
                    return redirect()->route('dashboard');

                } else if($usuario->estado === 'inactivo'){
                    return response()->json(['error' => 'Este usuario no se encuentra activo actualmente.'], 401);
                }
            }else {
                // Contraseña vencida
                $urlFirmada = URL::temporarySignedRoute('vista.cambiarContraseñaVencida', now()->addMinutes(config('ConfiguracionFCT._expiracion_cambio_contraseña')), ['id' => $usuario->id_usuario]);          
                return redirect($urlFirmada);
            }
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
