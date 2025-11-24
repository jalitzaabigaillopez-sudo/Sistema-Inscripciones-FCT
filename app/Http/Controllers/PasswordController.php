<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\ContraseñaTemporal;
use App\Mail\PasswordMail;
use Illuminate\Support\Facades\Mail;
use App\Services\PasswordGenerator;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Services\SessionService;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function __construct(Request $request)
    {
        // Evitar que bloquee rutas públicas
        if (!in_array($request->route()?->getName(), [
            'correo.cambiarContraseña',
            'vista.cambiarContraseña',
            'cambiar.contraseña',
            'vista.cambiarContraseñaVencida',
            'cambiar.contraseñaVencida',
        ])) {
            if (!SessionService::checkSession($request)) {
                redirect()->route('login')->send();
            }
        }
    }
    /**
     * METODO para asignar una contraseña temporal al usuario
     */
    public function correoCambiarContraseña(Request $request)
    {

        // Verificar que exista el usuario
        $usuario = Usuario::where('email', $request['correo'])->first();
        if (!$usuario) {
            return response()->json([
                'status' => 'error',
                'message' => 'El correo ingresado no está registrado en el sistema.'
            ], 404);
        }

        // Generara contraseña temporal
        $generator = new PasswordGenerator();
        $temporalPass = $generator->generate(12);

        // Verificar que NO EXISTA una contraseña temporal vigente de este usuario
        $contraseñaTemporal = ContraseñaTemporal::where('id_usuario', $usuario->id_usuario)->where('vigente', 'si')->first();
        if ($contraseñaTemporal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ya existe una solicitud de contraseña temporal activa. Inténtelo de nuevo más tarde.'
            ], 401);
        }

        // Crear el registro de la nueva contraseña temporal
        $contraseñaTemporal = new ContraseñaTemporal();
        $contraseñaTemporal->id_usuario = $usuario->id_usuario;
        $contraseñaTemporal->password_temporal = $temporalPass; //contraseña temporal

        $fecha_creacion = Carbon::now('America/Costa_Rica');
        $fecha_expiracion = Carbon::now('America/Costa_Rica')->addMinutes(config('ConfiguracionFCT._expiracion_cambio_contraseña'));
        $contraseñaTemporal->fecha_creacion = $fecha_creacion;

        $contraseñaTemporal->fecha_expiracion = $fecha_expiracion;
        $contraseñaTemporal->vigente = 'si';
        $contraseñaTemporal->save();

        // url que llevara a la ventana de cambio de contraseña al usuario
        $url = route('vista.cambiarContraseña', ['id' => $usuario->id_usuario]);

        // Enviar correo usando una clase Mailable
        Mail::to($usuario->email)->send(new PasswordMail($usuario, $contraseñaTemporal, $url));

        // Redirigir al login :_ Falta alertas de avisos
        return response()->json([
            'status' => 'success',
            'message' => 'Se ha enviado una contraseña temporal a su correo electrónico.'
        ]);
    }

    /**
     * SHOW
     * r
     */
    public function vistaCambiarContraseña($id)
    {
        $usuario = Usuario::find($id);
        return view('sections/completarCambioContraseña', compact('usuario'));
    }

    /**
     * Metodo para validar valides de contraseña temporal y guardar nueva
     */
    public function cambiarContraseña(Request $request)
    {
        $validateData = $request->validate([
            'temporaryPassword' => 'required|string',
            'password' => 'required|string|min:8|max:16',
        ]);

        // Verificar que el usuario exista
        $id = $request['id_usuario'];
        $usuario = Usuario::where('id_usuario', $id)->first();
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Verificar que exista la contraseña temporal
        $contraseñaTemporal = ContraseñaTemporal::where('password_temporal', $validateData['temporaryPassword'])
            ->where('id_usuario', $id)
            ->where('vigente', 'si')
            ->first();
        if (!$contraseñaTemporal) {
            return response()->json(['error' => 'La contraseña temporal no es válida o ya fue utilizada.'], 401);
        }

        // Verificar que la contraseña temporal sea igual a la proporcionada por el usuario
        // if ($validateData['temporaryPassword'] === $contraseñaTemporal->password_temporal) {

        // Verificar vigencia de contraseña temporal
        // Verificar vigencia por fecha
        $now = Carbon::now('America/Costa_Rica');
        $exp = Carbon::parse($contraseñaTemporal->fecha_expiracion, 'America/Costa_Rica');

        if ($now->greaterThanOrEqualTo($exp)) {
            $contraseñaTemporal->vigente = 'no';
            $contraseñaTemporal->save();

            return response()->json([
                'error' => 'Su contraseña temporal ha expirado.'
            ], 401);
        }
        $usuario->password = $validateData['password'];
        $usuario->save();

        $contraseñaTemporal->vigente = 'no';
        $contraseñaTemporal->save();

        // return redirect()->route('login');


        return response()->json([
            'status'  => 'success',
            'message' => 'Su contraseña ha sido actualizada correctamente.'
        ]);
    }

    /**
     * SHOW
     * r
     */
    public function vistaCambiarContraseñaVencida($id)
    {
        $usuario = Usuario::find($id);
        return view('sections/contraseñaVencida', compact('usuario'));
    }

    /**
     * Metodo para cambiar la contraseña del usuario cuando ha vencido
     */
    public function cambiarContraseñaVencida(Request $request)
    {
        $validateData = $request->validate([
            'nuevaContraseña' => 'required|string|min:8|max:16',
            'confirmarNuevaContraseña' => 'required|string|min:8|max:16',
        ]);

        // Verificar que el usuario exista
        $id = $request['id_usuario'];
        $usuario = Usuario::where('id_usuario', $id)->first();
        if (!$usuario) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        // Validar que coincidan
        if ($validateData['nuevaContraseña'] !== $validateData['confirmarNuevaContraseña']) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Las contraseñas no coinciden'
            ], 422);
        }

        // ==============================
        // VALIDAR SI ES IGUAL A LA ANTERIOR
        // Funciona si está en TEXTO PLANO o HASH
        // ==============================
        $nueva   = $validateData['nuevaContraseña'];
        $actual  = $usuario->password;

        // ¿La de BD parece hash bcrypt ($2y$...)?
        $esHash = str_starts_with($actual, '$2y$');

        // Si es hash → Hash::check
        // Si no es hash → comparar plano
        $esIgual = $esHash
            ? Hash::check($nueva, $actual)
            : ($actual === $nueva);

        if ($esIgual) {
            return response()->json([
                'status'  => 'error',
                'message' => 'La contraseña no puede ser igual que la anterior.'
            ], 422);
        }

        $usuario->password = $validateData['nuevaContraseña'];
        $usuario->password_vencimiento = config('ConfiguracionFCT._vencimiento_contraseña');
        $usuario->save();

        // ÉXITO → JSON PARA SWEETALERT
        return response()->json([
            'status'  => 'success',
            'message' => 'Contraseña actualizada correctamente.'
        ]);
    }
}
