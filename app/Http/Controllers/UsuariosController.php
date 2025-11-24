<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Services\SessionService;

class UsuariosController extends Controller
{
    public function __construct(Request $request)
    {
        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }
    }

    public function index(Request $request)
    {
        // Si es una solicitud AJAX, procesamos los datos para DataTables
        if ($request->ajax()) {
            $query = Usuario::with('academia'); // Inicia la consulta del modelo

            //  Usuario actual (de la sesión)
            $usuarioActualId = session('usuario'); // o Auth::id() si usas Auth

            // 1. Aplicar Búsqueda Global (si existe)
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    // Se busca en todas las columnas relevantes
                    $q->where('identificacion', 'like', "%{$search}%")
                        ->orWhere('nombre_completo', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('rol', 'like', "%{$search}%");
                });
            }

            // Obtener el total de registros después de aplicar el filtro (recordsFiltered)
            $recordsFiltered = $query->count();
            $totalRecords = Usuario::count(); // Total de registros sin filtros

            // ======================
            // 2. APLICAR ORDENAMIENTO
            // ======================
            $draw = (int) $request->input('draw', 1);

            // PRIMERA CARGA: orden alfabético
            if ($draw === 1) {
                $query->orderBy('nombre_completo', 'asc');
            } else if ($request->has('order') && count($request->order) > 0) {

                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection   = $request->order[0]['dir'];
                $orderColumnName  = $request->columns[$orderColumnIndex]['data'];

                // EVITAR columnas que NO existen en BD
                if (
                    !in_array($orderColumnName, ['id_usuario', 'identificacion', 'nombre_completo', 'email', 'rol', 'estado'])
                ) {
                    // fallback seguro
                    $query->orderBy('nombre_completo', 'asc');
                } else {
                    $query->orderBy($orderColumnName, $orderDirection);
                }
            }

            // 3. Aplicar Paginación
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            // 4. Formatear Datos para DataTables
            $formattedData = [];
            foreach ($data as $item) {

                $formattedData[] = [
                    'imagen_url' => $item->imagen ? asset('storage/' . $item->imagen) : null,

                    // Nombre del archivo para el modal
                    'imagen'     => $item->imagen,
                    'id_usuario'      => $item->id_usuario,
                    'identificacion' => $item->identificacion,
                    'nombre_completo' => $item->nombre_completo,
                    'email' => $item->email,
                    'rol' => $item->rol,
                    'estado' => $item->estado,
                    'usuario_actual'   => ($item->id_usuario == $usuarioActualId), // Se usa el ID para generar los botones

                    'academia' => $item->academia ? $item->academia->nombre : '—',
                ];
            }

            // Devolver la respuesta JSON
            return response()->json([
                'draw' => $request->input('draw', 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData,
            ]);
        }

        // Si no es AJAX, se carga la vista normalmente
        return view('catalogos.usuarios.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $messages = [
                'identificacion.required' => 'La identificación es obligatoria.',
                'identificacion.string' => 'La identificación debe ser una cadena de texto.',
                'identificacion.max' => 'La identificación no puede tener más de :max caracteres.',
                'identificacion.unique' => 'La identificación ya está en uso.',
                'nombre_completo.required' => 'El nombre completo es obligatorio.',
                'nombre_completo.string' => 'El nombre completo debe ser una cadena de texto.',
                'nombre_completo.max' => 'El nombre completo no puede tener más de :max caracteres.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
                'email.max' => 'El correo electrónico no puede tener más de :max caracteres.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.string' => 'La contraseña debe ser una cadena de texto.',
                'password.min' => 'La contraseña debe tener al menos :min caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'rol.required' => 'El rol es obligatorio.',
                'rol.in' => 'El rol seleccionado no es válido.',
                'imagen.image' => 'El archivo debe ser una imagen.',
                'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif.',
                'imagen.max' => 'La imagen no puede pesar más de 10 MB.',
            ];
            // Validar los datos del formulario
            $validator = Validator::make($request->all(), [
                'identificacion' => 'required|string|max:20|unique:usuarios,identificacion',
                'nombre_completo' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:usuarios,email',
                'password' => 'required|string|min:8|confirmed',
                'rol' => 'required|in:administrador,academia,arbitro',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            ], $messages);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear el usuario
            $usuario = new Usuario();
            $usuario->identificacion = $request->identificacion;
            $usuario->nombre_completo = $request->nombre_completo;
            $usuario->email = $request->email;
            $usuario->password = $request->password;
            $usuario->rol = $request->rol;
            $usuario->estado = 'activo';
            $usuario->password_vencimiento = 180;

            // Manejar la subida de la imagen
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('perfiles', 'public');
                $usuario->imagen = $path;
            }

            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error en store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        try {
            $messages = [
                'identificacion.required' => 'La identificación es obligatoria.',
                'identificacion.string' => 'La identificación debe ser una cadena de texto.',
                'identificacion.max' => 'La identificación no puede tener más de :max caracteres.',
                'identificacion.unique' => 'La identificación ya está en uso.',
                'nombre_completo.required' => 'El nombre completo es obligatorio.',
                'nombre_completo.string' => 'El nombre completo debe ser una cadena de texto.',
                'nombre_completo.max' => 'El nombre completo no puede tener más de :max caracteres.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser válido.',
                'email.max' => 'El correo electrónico no puede tener más de :max caracteres.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'password.regex' => 'La contraseña debe incluir mayúscula, minúscula, número y carácter especial.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.max' => 'La contraseña no puede tener más de 11 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'rol.required' => 'El rol es obligatorio.',
                'rol.in' => 'El rol seleccionado no es válido.',
                'imagen.image' => 'El archivo debe ser una imagen.',
                'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif.',
                'imagen.max' => 'La imagen no puede pesar más de 10 MB.',
            ];

            $usuario = Usuario::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'identificacion' => 'required|string|max:20|unique:usuarios,identificacion,' . $id . ',id_usuario',
                'nombre_completo' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:usuarios,email,' . $id . ',id_usuario',
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'max:11',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/'
                ],
                'rol' => 'required|in:administrador,academia,arbitro',
                'estado' => 'required|in:activo,inactivo',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
                'remove_imagen' => 'nullable|in:0,1',
            ], $messages);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar datos generales
            $usuario->identificacion = $request->identificacion;
            $usuario->nombre_completo = $request->nombre_completo;
            $usuario->email = $request->email;
            $usuario->rol = $request->rol;
            $usuario->estado = $request->estado;

            $passwordCambiada = false;

            // Si hay nueva contraseña
            if ($request->filled('password')) {
                $usuario->password = $request->password; // El mutator en el modelo hará el hash
                $usuario->password_vencimiento = 180;
                $passwordCambiada = true;
            }

            // Manejo de imagen
            if ($request->input('remove_imagen') === '1') {
                if ($usuario->imagen) {
                    Storage::disk('public')->delete($usuario->imagen);
                    $usuario->imagen = null;
                }
            } elseif ($request->hasFile('imagen')) {
                if ($usuario->imagen) {
                    Storage::disk('public')->delete($usuario->imagen);
                }
                $path = $request->file('imagen')->store('perfiles', 'public');
                $usuario->imagen = $path;
            }

            $usuario->save();

            // Si cambió su contraseña, cerrar sesión
            if ($passwordCambiada && session('usuario') == $usuario->id_usuario) {
                Session::flush();
                Auth::logout();

                return response()->json([
                    'success' => true,
                    'logout' => true,
                    'message' => 'Tu contraseña ha sido actualizada. Por seguridad, debes volver a iniciar sesión. 
                Recuerda cambiarla periódicamente para mantener tu cuenta protegida.'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente. 
            Se recomienda cambiar la contraseña periódicamente para mayor seguridad.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error en update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor.'
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     $usuarioActualId = session('usuario');

    //     if ($id == $usuarioActualId) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'No puedes eliminar tu propio usuario.'
    //         ], 403);
    //     }

    //     $usuario = Usuario::find($id);
    //     if (!$usuario) {
    //         return response()->json(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
    //     }

    //     $usuario->delete();

    //     return response()->json(['status' => 'success', 'message' => 'Usuario eliminado correctamente.']);
    // }

    public function inactivar($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $usuario->estado = 'inactivo';
            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Usuario inactivado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al inactivar el usuario.'
            ], 500);
        }
    }
}
