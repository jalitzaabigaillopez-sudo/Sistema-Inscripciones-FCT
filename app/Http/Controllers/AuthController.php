<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function verificarUsuario(Request $request)
    {
        // VERIFICAR DATOS  DE ENTRADA
        $validateData = $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:16',
        ]);

        $correo = $request->input('email');
        $password = $request->input('password');

        $usuario = Usuario::where('email', $correo)->first();

        // Verificar que exista el usuario
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Verificar que la contraseña sea igual a la proporcionada
        if ($password === $usuario->password) {

            // Verificar que la contraseña no haya vencido
            if ($usuario->password_vencimiento > 0) {

                // Verificar que el usuario este activo
                if (!$usuario->estado === 'activo') {
                    return response()->json(['error' => 'Este usuario no se encuentra activo actualmente.'], 401);
                }

                $request->session()->put('usuario', $usuario->id_usuario); // colocar usuario en sesion
                return redirect()->route('dashboard');
            } else {
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

    // DATOS PARA EL PERFIL 
    private function obtenerUsuarioSesion(Request $request)
    {
        $userId = $request->session()->get('usuario');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión primero.');
        }

        $usuario = Usuario::find($userId);

        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Usuario no encontrado.');
        }

        return $usuario;
    }

    public function perfil(Request $request)
    {
        $usuario = $this->obtenerUsuarioSesion($request);
        if ($usuario instanceof \Illuminate\Http\RedirectResponse) return $usuario;

        $vista = $usuario->rol === 'administrador'
            ? 'admin.perfil-admin'
            : 'academia.perfilAcademia';

        return view($vista, compact('usuario'));
    }
}
