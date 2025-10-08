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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



class AcademiaController extends Controller
{

    public function index(Request $request)
    {
        // Si la petición viene desde DataTables (AJAX)
        if ($request->ajax()) {
            $query = Academia::with(['usuario', 'distrito.canton.provincia']);

            // Búsqueda global (por nombre, encargado, correo, teléfono, dirección, provincia, cantón, distrito)
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('profesor_encargado', 'like', "%{$search}%")
                        ->orWhere('correo', 'like', "%{$search}%")
                        ->orWhere('telefono', 'like', "%{$search}%")
                        ->orWhere('direccion', 'like', "%{$search}%")
                        ->orWhereHas('distrito.canton.provincia', function ($p) use ($search) {
                            $p->where('nombre', 'like', "%{$search}%");
                        })
                        ->orWhereHas('distrito.canton', function ($c) use ($search) {
                            $c->where('nombre', 'like', "%{$search}%");
                        })
                        ->orWhereHas('distrito', function ($d) use ($search) {
                            $d->where('nombre', 'like', "%{$search}%");
                        });
                });
            }

            // Conteo total
            $recordsFiltered = $query->count();
            $totalRecords = Academia::count();

            // Ordenamiento
            if ($request->has('order') && count($request->order) > 0) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];

                if (in_array($orderColumnName, ['nombre', 'correo', 'telefono', 'estado'])) {
                    $query->orderBy($orderColumnName, $orderDirection);
                }
            }

            // Paginación
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $academias = $query->skip($start)->take($length)->get();

            // Formateo de datos
            $formattedData = [];
            foreach ($academias as $a) {
                $ubicacion = $a->distrito
                    ? "{$a->distrito->canton->provincia->nombre}, {$a->distrito->canton->nombre}, {$a->distrito->nombre}"
                    : '<span class="text-muted">Sin ubicación</span>';

                $estado = match ($a->estado) {
                    'activo' => '<span class="badge bg-success rounded-pill">Activo</span>',
                    'inactivo' => '<span class="badge bg-danger rounded-pill">Inactivo</span>',
                    default => '<span class="badge bg-secondary rounded-pill">Pendiente</span>',
                };

                $acciones = '
                 <div class="d-flex justify-content-center gap-2">
        <button class="btn btn-sm btn-warning rounded-pill btn-edit" data-id="' . $a->id_academia . '" title="Editar">
            <i class="bi bi-pencil-square"></i>
        </button>
        <button class="btn btn-sm btn-danger rounded-pill" onclick="confirmarEliminacion(' . $a->id_academia . ')" title="Eliminar">
            <i class="bi bi-trash"></i>
        </button>
    </div>';

                $formattedData[] = [
                    'nombre' => e($a->nombre),
                    'profesor_encargado' => e($a->profesor_encargado),
                    'correo' => e($a->correo),
                    'telefono' => e($a->telefono),
                    'usuario' => e($a->usuario->nombre_completo ?? '—'),
                    'ubicacion' => $ubicacion,
                    'direccion' => e($a->direccion),
                    'estado' => $estado,
                    'acciones' => $acciones,
                ];
            }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData,
            ]);
        }

        // Carga normal (no AJAX)
        $provincias = Provincia::all();
        return view('catalogos.academias.index', compact('provincias'));
    }


    //####################################### SOLO ADMINISTRADOR ############################################
    public function pre_registroAcademia(Request $request)
    {

        try {
            // VERIFICAR DATOS  DE ENTRADA
            $validateData = $request->validate([
                // usuario
                'profesor_encargado' => 'required|string', //encargado de academia
                'email' => 'required|string|email',
                // 'password' => 'required|string',
                // 'rol' => 'required|string|in:administrador,academia,arbitro',
                // 'estado' => 'required|string|in:activo,inactivo,pendiente',

                // academia
                'nombre' => 'required|string', //nombre de academia
                'direccion' => 'required|string',
                'telefono' => 'required|string',
                'distrito_id' => 'required',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // 10MB máx
            ]);

            // Generar contraseña temporal
            $generator = new PasswordGenerator();
            $temporalPass = $generator->generate(12);

            // Guardar el usuario academia como inactivo
            $usuario = Usuario::create([
                // 'identificacion' => $validateData['identificacion'], //se puede completar despues si no se sabe
                'nombre_completo' => $validateData['profesor_encargado'], //encargado de academia
                'email' => $validateData['email'], //c
                'password' => $temporalPass, //contraseña temporal
                'rol' => 'academia',
                'estado' => 'inactivo', //inactivo por defecto
            ]);
            $usuario->save();

            // ===============================
            // 🖼️ PROCESAR IMAGEN (si se sube)
            // ===============================
            $rutaImagen = null;
            if ($request->hasFile('imagen')) {
                try {
                    $rutaImagen = $request->file('imagen')->store('perfiles', 'public');
                } catch (\Exception $e) {
                    Log::error('❌ Error al guardar imagen de academia: ' . $e->getMessage());
                }
            }

            // Se guarda la academia ligada al usuario creado
            $academia = Academia::create([
                'nombre' => $validateData['nombre'], //nombre de academia
                'profesor_encargado' => $usuario->nombre_completo,
                'direccion' => $validateData['direccion'],
                'correo' => $usuario->email,
                'telefono' => $validateData['telefono'],
                'estado' => 'inactivo', //activo por defecto
                'id_usuario' => $usuario->id_usuario,
                'id_distrito' => $validateData['distrito_id'], //c  
                'imagen' => $rutaImagen, // se guarda la ruta si existe

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

            return redirect()->back()->with('alerta', 'Se ha enviando un correo a esta academia. Ahora se encuentra en proceso de registro.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('❌ Error SQL al crear academia: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'El correo ya está registrado o hubo un error al guardar los datos.'], 500);
            }
            return redirect()->back()->with('error', 'El correo ya está registrado o hubo un error al guardar los datos.');
        } catch (\Exception $e) {
            Log::error('❌ Error general al registrar academia: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Error interno del servidor.'], 500);
            }
            return redirect()->back()->with('error', 'Error interno del servidor.');
        }
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

                    $academia = $usuario->academia;
                    $academia->estado = 'activo';
                    $academia->save();

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
        try {
            //validation messages
            $messages = [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de texto.',
                'nombre.max' => 'El nombre no puede tener más de :max caracteres.',
                'profesor_encargado.required' => 'El profesor encargado es obligatorio.',
                'profesor_encargado.string' => 'El profesor encargado debe ser una cadena de texto.',
                'profesor_encargado.max' => 'El profesor encargado no puede tener más de :max caracteres.',
                'telefono.required' => 'El teléfono es obligatorio.',
                'telefono.string' => 'El teléfono debe ser una cadena de texto.',
                'telefono.max' => 'El teléfono no puede tener más de :max caracteres.',
                'correo.required' => 'El correo electrónico es obligatorio.',
                'correo.email' => 'El correo electrónico debe ser una dirección válida.',
                'correo.max' => 'El correo electrónico no puede tener más de :max caracteres.',
                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'El estado seleccionado no es válido.',
                'provincia.required' => 'La provincia es obligatoria.',
                'provincia.exists' => 'La provincia seleccionada no es válida.',
                'canton.required' => 'El cantón es obligatorio.',
                'canton.exists' => 'El cantón seleccionado no es válido.',
                'distrito.required' => 'El distrito es obligatorio.',
                'distrito.exists' => 'El distrito seleccionado no es válido.',
                'direccion.required' => 'La dirección es obligatoria.',
                'direccion.string' => 'La dirección debe ser una cadena de texto.',
                'direccion.max' => 'La dirección no puede tener más de :max caracteres.',
                'imagen.image' => 'El archivo debe ser una imagen.',
                'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif.',
                'imagen.max' => 'La imagen no puede pesar más de 10 MB.',
                'remove_imagen.in' => 'El valor de remove_imagen no es válido.',
            ];

            // Find tacademy
            $academia = Academia::findOrFail($id);

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'profesor_encargado' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'correo' => 'required|email|max:255',
                'estado' => 'required|in:activo,inactivo',
                'provincia' => 'required|exists:provincias,id_provincia',
                'canton' => 'required|exists:cantones,id_canton',
                'distrito' => 'required|exists:distritos,id_distrito',
                'direccion' => 'required|string|max:255',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // 10MB limit
                'remove_imagen' => 'nullable|in:0,1',
            ], $messages);

            if ($validator->fails()) {
                Log::warning('Validación fallida en update: ' . json_encode($validator->errors()));
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update academy
            $academia->nombre = $request->nombre;
            $academia->profesor_encargado = $request->profesor_encargado;
            $academia->telefono = $request->telefono;
            $academia->correo = $request->correo;
            $academia->estado = $request->estado;
            $academia->id_distrito = $request->distrito;
            $academia->direccion = $request->direccion;

            // image
            if ($request->input('remove_imagen') === '1') {
                Log::info('Intentando eliminar imagen de la academia ID: ' . $id . ', Imagen actual: ' . $academia->imagen);
                if ($academia->imagen && Storage::disk('public')->exists($academia->imagen)) {
                    try {
                        Storage::disk('public')->delete($academia->imagen);
                        Log::info('Imagen eliminada exitosamente: ' . $academia->imagen);
                        $academia->imagen = null;
                    } catch (\Exception $e) {
                        Log::error('Error al eliminar la imagen: ' . $e->getMessage());
                        return response()->json([
                            'success' => false,
                            'error' => 'Error al eliminar la imagen: ' . $e->getMessage()
                        ], 500);
                    }
                } else {
                    Log::warning('No se encontró la imagen para eliminar o ya estaba eliminada: ' . $academia->imagen);
                    $academia->imagen = null;
                }
            } elseif ($request->hasFile('imagen')) {
                Log::info('Subiendo nueva imagen para academia ID: ' . $id . ', Nombre archivo: ' . $request->file('imagen')->getClientOriginalName());
                if ($academia->imagen && Storage::disk('public')->exists($academia->imagen)) {
                    try {
                        Storage::disk('public')->delete($academia->imagen);
                        Log::info('Imagen anterior eliminada: ' . $academia->imagen);
                    } catch (\Exception $e) {
                        Log::warning('No se pudo eliminar la imagen anterior: ' . $e->getMessage());
                    }
                }
                try {
                    $path = $request->file('imagen')->store('perfiles', 'public');
                    $academia->imagen = $path;
                    Log::info('Nueva imagen guardada: ' . $path);
                } catch (\Exception $e) {
                    Log::error('Error al guardar la nueva imagen: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'error' => 'Error al guardar la imagen: ' . $e->getMessage()
                    ], 500);
                }
            }

            // Save academy
            try {
                $academia->save();
                Log::info('Academia actualizada exitosamente: ID ' . $id);
            } catch (\Exception $e) {
                Log::error('Error al guardar la academia: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'error' => 'Error al guardar los datos de la academia: ' . $e->getMessage()
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Academia actualizada correctamente.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error general en update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
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

    public function getProfile()
    {
        return view('academia/perfil-academia');
    }
}
