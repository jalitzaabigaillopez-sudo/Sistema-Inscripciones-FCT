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


class PasswordController extends Controller
{
    /**
     * METODO para asignar una contraseña temporal al usuario
     */
    public function correoCambiarContraseña(Request $request)
    {

        // Verificar que exista el usuario
        $usuario = Usuario::where('email', $request['correo'])->first();
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Generara contraseña temporal
        $generator = new PasswordGenerator();
        $temporalPass = $generator->generate(12);

        // Verificar que NO EXISTA una contraseña temporal vigente de este usuario
        $contraseñaTemporal = ContraseñaTemporal::where('id_usuario', $usuario->id_usuario)->where('vigente', 'si')->first();
        if($contraseñaTemporal){
            return response()->json(['error' => 'No es posible continuar ahora mismo, intentelo de nuevo más tarde.'], 401);   
        }
        
        // Crear el registro de la nueva contraseña temporal
        $contraseñaTemporal = new ContraseñaTemporal();
        $contraseñaTemporal->id_usuario = $usuario->id_usuario;  
        $contraseñaTemporal->password_temporal = $temporalPass;//contraseña temporal

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
        return redirect()->back();
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
        $contraseñaTemporal = ContraseñaTemporal::where('password_temporal', $validateData['temporaryPassword'])->where('id_usuario', $id)->first();
        if (!$contraseñaTemporal) {
            return response()->json(['error' => 'Ha ocurrido un error con el proceso de registro. Verifique la información proporcionada.'], 401);
        }

        // Verificar que la contraseña temporal sea igual a la proporcionada por el usuario
        if ($validateData['temporaryPassword'] === $contraseñaTemporal->password_temporal) {

            // Verificar vigencia de contraseña temporal
            if (!$contraseñaTemporal->fecha_expiracion > Carbon::now('America/Costa_Rica')) {

                $contraseñaTemporal->vigente = 'no';
                $contraseñaTemporal->save();
                return response()->json(['error' => 'Su contraseña temporal ha expirado.'], 401);
            } 
            $usuario->password = $validateData['password']; 
            $usuario->save();

            $contraseñaTemporal->vigente = 'no';
            $contraseñaTemporal->save();

            return redirect()->route('login');
        }else {
            return response()->json(['error' => 'Contraseña temporal no coincide'], 401);
        }

        return redirect()->route('login');
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
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        if($validateData['nuevaContraseña'] !== $validateData['confirmarNuevaContraseña']){
            return response()->json(['error' => 'Verifique su contraseña.'], 401);
        }

        if ($usuario->password === $validateData['nuevaContraseña']) {
            return response()->json(['error' => 'La contraseña no puede ser igual que la anterior.'], 401);
        }

        $usuario->password = $validateData['nuevaContraseña'];
        $usuario->password_vencimiento = config('ConfiguracionFCT._vencimiento_contraseña');
        $usuario->save(); 
        return redirect()->route('login');
    }

}
