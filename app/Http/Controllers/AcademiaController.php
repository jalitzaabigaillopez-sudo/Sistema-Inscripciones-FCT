<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Academia;
use App\Models\ContraseñaTemporal;
use App\Mail\FCTMail;
use App\Models\Canton;
use App\Models\Distrito;
use App\Models\Provincia;
use Illuminate\Support\Facades\Mail;
use App\Services\PasswordGenerator;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class AcademiaController extends Controller
{

    public function index()
    {
        $data = Academia::with('distrito.canton.provincia')->get();
        $provincias = Provincia::all();
        return view('catalogos.academias.index', compact('data', 'provincias'));
    }

    //####################################### SOLO ADMINISTRADOR ############################################
    public function pre_registroAcademia(Request $request)
    {
        // VERIFICAR DATOS  DE ENTRADA
        $validateData = $request->validate([
            // usuario
            'identificacion' => 'required|string', //no requerido
            'nombre_completo' => 'required|string', //encargado de academia
            'email' => 'required|string|email',
            // 'password' => 'required|string',
            'rol' => 'required|string|in:administrador,academia,arbitro',
            'estado' => 'required|string|in:activo,inactivo,pendiente', //debe de venir como pendiente
            'imagen'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // academia
            'nombre' => 'required|string', //nombre de academia
            'direccion' => 'required|string',
            'correo' => 'required|string|email',
            'telefono' => 'required|string',
            'estado' => 'required|string|in:activo,inactivo', //activo por defecto
            'id_distrito' => 'required|integer',
        ]);

        // Generar contraseña temporal
        $generator = new PasswordGenerator();
        $temporalPass = $generator->generate(12);

        // Guardar el usuario academia como inactivo
        $usuario = Usuario::create([
            'identificacion' => $validateData['identificacion'], //se puede completar despues si no se sabe
            'nombre_completo' => $validateData['nombre_completo'], //encargado de academia
            'email' => $validateData['email'], //c
            'password' => $temporalPass, //contraseña temporal
            'rol' => $validateData['rol'],
            'estado' => $validateData['estado'],
            'imagen' => $validateData['imagen'],
        ]);
        $usuario->save();

        // Se guarda la academia ligada al usuario creado
        $academia = Academia::create([
            'nombre' => $validateData['nombre'], //nombre de academia
            'profesor_encargado' => $usuario->nombre_completo,
            'direccion' => $validateData['direccion'],
            'correo' => $usuario->email,
            'telefono' => $validateData['telefono'],
            'estado' => 'activo', //activo por defecto
            'id_usuario' => $usuario->id_usuario,
            'id_distrito' => $validateData['id_distrito'], //c  
        ]);
        $academia->save();

        // Se envia correo a la academia
        // $urlFirmada = URL::temporarySignedRoute('activar.cuenta', now()->addHours(48), // Tiempo de expiración
        // ['id' => $usuario->id_usuario]
        // );

        $contraseñaTemporal = new ContraseñaTemporal();
        $contraseñaTemporal->id_usuario = $usuario->id_usuario;
        $contraseñaTemporal->password_temporal = $temporalPass; //contraseña temporal
        $fecha_creacion = Carbon::now('America/Costa_Rica');
        $fecha_expiracion = Carbon::now('America/Costa_Rica')->addHours(48); // DEFINIR EL TIEMPO MAXIMO c
        $contraseñaTemporal->fecha_creacion = $fecha_creacion;
        $contraseñaTemporal->fecha_expiracion = $fecha_expiracion;
        $contraseñaTemporal->vigente = 'si';
        $contraseñaTemporal->save();

        $url = route('activar.cuenta', ['id' => $usuario->id_usuario]);
        Mail::to($usuario->email)->send(new FCTMail($usuario, $contraseñaTemporal, $url));

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
        // Validar datos de entrada
        $validateData = $request->validate([
            'email' => 'required|string',
            'temporaryPassword' => 'required|string',
            'password' => 'required|string|min:8|max:16',
        ]);
        -

        // Verificar que exista el usuario
        $id = $request['id_usuario'];
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['error' => 'Ha ocurrido un error con el proceso de registro'], 404);
        }

        if ($validateData['email'] === $usuario->email) {
            if ($usuario->estado === 'inactivo') {

                // Verificar que exista la contraseña temporal
                $contraseñaTemporal = ContraseñaTemporal::where('id_usuario', $id)->where('vigente', 'si')->first();
                if (!$contraseñaTemporal) {
                    return response()->json(['error' => 'Ha ocurrido un error con el proceso de registro'], 401);
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
                    $usuario->estado = 'activo';
                    $usuario->save();

                    $contraseñaTemporal->vigente = 'no';
                    $contraseñaTemporal->save();

                    return redirect()->route('login');
                } else {
                    return response()->json(['error' => 'Contraseña temporal no valida, intentelo de nuevo.'], 401);
                }
            } else {
                return response()->json(['error' => 'Este usuario ya no se encuentra en proceso de registro'], 401);
            }
        } else {
            return response()->json(['error' => 'Email no coincide'], 401);
        }
    }



    public function edit(string $id)
    {
        $academia = Academia::with('distrito.canton.provincia')->findOrFail($id);
        return response()->json($academia);
    }

    public function getCantones($provinciaId)
    {
        $cantones = Canton::where('id_provincia', $provinciaId)->get(['id_canton', 'nombre']);
        return response()->json($cantones);
    }

    public function getDistritos($cantonId)
    {
        $distritos = Distrito::where('id_canton', $cantonId)->get(['id_distrito', 'nombre']);
        return response()->json($distritos);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'profesor_encargado' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|max:255',
            'estado' => 'required|in:activo,inactivo',
            'provincia' => 'required|exists:provincias,id_provincia',
            'canton' => 'required|exists:cantones,id_canton',
            'distrito' => 'required|exists:distritos,id_distrito',
            'direccion' => 'required|string|max:255',
        ]);

        $academia = Academia::findOrFail($id);
        $academia->update([
            'nombre' => $request->nombre,
            'profesor_encargado' => $request->profesor_encargado,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'estado' => $request->estado,
            'id_distrito' => $request->distrito,
            'direccion' => $request->direccion,
        ]);

        return response()->json(['success' => 'Academia actualizada correctamente.']);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Academia::find($id);

        $item->delete();

        return back();
    }
}
