<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Mail\PasswordMail;
use Illuminate\Support\Facades\Mail;
use App\Services\PasswordGenerator;
use Illuminate\Support\Facades\URL;

class PasswordController extends Controller
{
    /**
     * Se verifica el correo proporcionado y se cambia la contraseña del ususario por una temporal
     */
    public function correoCambiarContraseña(Request $request)
    {

        $usuario = Usuario::where('email', $request['correoInput'])->first();
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $generator = new PasswordGenerator();
        $usuario->password = $generator->generate(12);
        $usuario->save();

        $urlFirmada = URL::temporarySignedRoute('vista.cambiarContraseña', now()->addMinutes(15), // Tiempo de expiración
        ['id' => $usuario->id_usuario]
        );

        Mail::to($usuario->email)->send(new PasswordMail($usuario, $urlFirmada));

        return redirect()->route('login');
    } 

    public function vistaCambiarContraseña($id)
    {
        $usuario = Usuario::find($id);
        return view('sections/completarCambioContraseña', compact('usuario'));
    }

    public function cambiarContraseña(Request $request)
    {
        $validateData = $request->validate([
                'temporaryPassword' => 'required|string',
                'password' => 'required|string|min:8|max:16',
        ]); 

        $id = $request['id_usuario'];
        $usuario = Usuario::where('id_usuario', $id)->first();
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        if ($validateData['temporaryPassword'] === $usuario->password) {
            $usuario->password = $validateData['password']; 
            $usuario->save();
        }else {
            return response()->json(['error' => 'Contraseña temporal no coincide'], 401);
        }

        return redirect()->route('login');
    }
}
