<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Usuario::all();
        return view('catalogos.usuarios.index', compact('data'));
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
            'imagen.max' => 'La imagen no puede pesar más de 2048 KB.',
        ];
            // Validar los datos del formulario
            $validator = Validator::make($request->all(), [
                'identificacion' => 'required|string|max:20|unique:usuarios,identificacion',
                'nombre_completo' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:usuarios,email',
                'password' => 'required|string|min:8|confirmed',
                'rol' => 'required|in:administrador,academia,arbitro',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'imagen.max' => 'La imagen no puede pesar más de 2048 KB.',
        ];
            // Buscar el usuario
            $usuario = Usuario::findOrFail($id);

            // Validar los datos del formulario
            $validator = Validator::make($request->all(), [
                'identificacion' => 'required|string|max:20|unique:usuarios,identificacion,' . $id . ',id_usuario',
                'nombre_completo' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:usuarios,email,' . $id . ',id_usuario',
                'password' => 'nullable|string|min:8|confirmed',
                'rol' => 'required|in:administrador,academia,arbitro',
                'estado' => 'required|in:activo,inactivo',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'remove_imagen' => 'nullable|in:0,1',
            ], $messages);

            if ($validator->fails()) {
                Log::warning('Validación fallida en update: ' . json_encode($validator->errors()));
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar los datos del usuario
            $usuario->identificacion = $request->identificacion;
            $usuario->nombre_completo = $request->nombre_completo;
            $usuario->email = $request->email;
            $usuario->rol = $request->rol;
            $usuario->estado = $request->estado;

            // Actualizar la contraseña solo si se proporciona
            if ($request->filled('password')) {
                $usuario->password = $request->password; // Dejar que Encryptable lo maneje
                $usuario->password_vencimiento = 180; // Reiniciar el vencimiento
            }

            // Manejar la imagen
            if ($request->input('remove_imagen') === '1') {
                Log::info('Eliminando imagen del usuario: ' . $usuario->imagen);
                if ($usuario->imagen) {
                    Storage::disk('public')->delete($usuario->imagen);
                    $usuario->imagen = null;
                }
            } elseif ($request->hasFile('imagen')) {
                Log::info('Subiendo nueva imagen en update: ' . $request->file('imagen')->getClientOriginalName());
                if ($usuario->imagen) {
                    Storage::disk('public')->delete($usuario->imagen);
                }
                $path = $request->file('imagen')->store('perfiles', 'public');
                $usuario->imagen = $path;
                Log::info('Imagen guardada en update: ' . $path);
            }

            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente.'
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
    public function destroy(string $id)
    {
        $item = Usuario::find($id);

        $item->delete();

        return back();
    }
}
