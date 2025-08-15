<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Academia;
use App\Mail\FCTMail;
use Illuminate\Support\Facades\Mail;
use App\Services\PasswordGenerator;
use Illuminate\Support\Facades\URL;

class AcademiaController extends Controller
{
//####################################### SOLO ADMINISTRADOR ############################################
    public function pre_registroAcademia(Request $request)
    {
        // VERIFICAR DATOS  DE ENTRADA
        $validateData = $request->validate([
                'cedula' => 'string',//no requerido
                'nombreAcademia' => 'required|string', //encargado de academia
                'email' => 'required|string|email',
                'password' => 'required|string',
                'rol' => 'required|string|in:administrador,academia,arbitro',
                'estado' => 'required|string|in:activo,inactivo,pendiente',//debe de venir como pendiente
                'imagen'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
         
                'profesor_encargado' => 'required|string',
                'canton' => 'required|string', 
                'provincia' => 'required|string',
                'direccion' => 'required|string',
                'telefono' => 'required|string',
                // 'estado' => 'required|string|in:activo,inactivo',
        ]); 
        
        // Cedula puede ingresarse despues, puede estar en blanco en pre-registro
        if (empty($validateData['cedula'])) {
            $validateData['cedula'] = 'por completar';
        }

        // Generar contraseña temporal
        $generator = new PasswordGenerator();
        $temporalPass = $generator->generate(12);

        // Guardar el usuario academia como pendiente
        $usuario = Usuario::create([
        'cedula' => $validateData['cedula'],//se puede completar despues si no se sabe
        'nombre' => $validateData['nombre'],//encargado de academia
        'email' => $validateData['email'],//c
        'password' => $temporalPass,//contraseña temporal
        'rol' => $validateData['rol'],
        'estado' => $validateData['estado'],
        'imagen' => $validateData['imagen'],
        ]);
        $usuario->save();

        // Se guarda la academia ligada al usuario creado
        $academia = Academia::create([
        'id_usuario' => $usuario->id_usuario,
        'nombre' => $validateData['nombre'],//nombre de academia
        'canton' => $validateData['canton'],//c
        'provincia' => $validateData['provincia'],
        'profesor_encargado' => $usuario->nombre,
        'direccion' => $validateData['direccion'],
        'correo' => $usuario->email,
        'telefono' => $validateData['telefono'],
        'estado' => 'activo',
        ]);
        $academia->save();


        // Se envia correo a la academia
        $urlFirmada = URL::temporarySignedRoute('activar.cuenta', now()->addHours(48), // Tiempo de expiración
        ['id' => $usuario->id_usuario]
        );
        Mail::to($usuario->email)->send(new FCTMail($usuario, $urlFirmada));

        return redirect()->back();
    }

//####################################### SOLO ACADEMIA #################################################
     public function vista_activarCuenta($id)
    {
        $usuario = Usuario::find($id);
        return view('sections/completarRegistroAcademia', compact('usuario'));
    } 

    public function activarCuenta(Request $request)
    {
        $validateData = $request->validate([
                'email' => 'required|string',
                'temporaryPassword' => 'required|string',
                'password' => 'required|string|min:8|max:16',
        ]); -

        $id = $request['id_usuario'];

        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['error' => 'Ha ocurrido un error con el proceso de registro'], 404);
        }

        if ($validateData['email'] === $usuario->email) {
            if ($validateData['temporaryPassword'] === $usuario->password) {
                if($usuario->estado === 'pendiente'){

                    $usuario->password = $validateData['password'];
                    $usuario->estado = 'activo';
                    $usuario->save();

                    return redirect()->route('login');
                }else{
                    return response()->json(['error' => 'Este usuario ya no se encuentra en proceso de registro'], 401);
                }
            } else{
                return response()->json(['error' => 'Contraseña temporal no coincide'], 401);
            }            
        } else {
            return response()->json(['error' => 'Email no coincide'], 401);
        } 
    } 
}
