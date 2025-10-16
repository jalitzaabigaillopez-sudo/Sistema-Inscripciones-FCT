<?php

namespace App\Http\Controllers;

use App\Models\Provincia;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use App\Services\SessionService;

class AuthController extends Controller
{
    public function verificarUsuario(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:16',
        ]);

        $correo = $request->input('email');
        $password = $request->input('password');

        $usuario = Usuario::where('email', $correo)->first();

        // No existe el usuario
        if (!$usuario) {
            return response()->json([
                'status' => 'error',
                'error' => 'Usuario no encontrado.'
            ], 404);
        }

        //  Contraseña incorrecta
        // Verificar contraseña encriptada
        if (!Hash::check($password, $usuario->password)) {
            return response()->json([
                'status' => 'error',
                'error' => 'Contraseña incorrecta.'
            ], 401);
        }

        //  Usuario inactivo
        if ($usuario->estado !== 'activo') {
            return response()->json([
                'status' => 'error',
                'error' => 'Este usuario no se encuentra activo actualmente.'
            ], 403);
        }

        //  Contraseña vencida
        if ($usuario->password_vencimiento <= 0) {
            $urlFirmada = URL::temporarySignedRoute(
                'vista.cambiarContraseñaVencida',
                now()->addMinutes(config('ConfiguracionFCT._expiracion_cambio_contraseña')),
                ['id' => $usuario->id_usuario]
            );
            return response()->json([
                'status' => 'redirect',
                'message' => 'Tu contraseña ha expirado. Debes actualizarla antes de continuar.',
                'redirect' => $urlFirmada
            ]);
        }

        //  Login correcto
        $request->session()->put('usuario', $usuario->id_usuario);
        $request->session()->put('usuario_expira', now()->addMinutes(50));
        return response()->json([
            'status' => 'success',
            'message' => 'Inicio de sesión exitoso.',
            'redirect' => route('dashboard')
        ]);
    }


    /**
     * 
     */
    public function cerrarSesion(Request $request)
    {

        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }

        // Elimina todas las variables de sesión
        $request->session()->invalidate();

        // Regenera el token CSRF para evitar reutilización
        $request->session()->regenerateToken();

        // Redirige al login
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

        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }

        $usuario = $this->obtenerUsuarioSesion($request);
        if ($usuario instanceof \Illuminate\Http\RedirectResponse)
            return $usuario;

        $vista = $usuario->rol === 'administrador'
            ? 'admin.perfil-admin'
            : 'academia.perfilAcademia';

        // Cargar academia con sus relaciones
        $academia = $usuario->academia()
            ->with(['distrito.canton.provincia'])
            ->first();

        // Cargar provincias para los selects
        $provincias = Provincia::orderBy('nombre')->get(['id_provincia', 'nombre']);

        return view($vista, compact('usuario', 'academia', 'provincias'));
    }
}
