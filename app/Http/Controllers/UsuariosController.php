<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Importar el facade Log


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
            // Validar los datos del formulario
            $validator = Validator::make($request->all(), [
                'identificacion' => 'required|string|max:20|unique:usuarios,identificacion',
                'nombre_completo' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:usuarios,email',
                'password' => 'required|string|min:8|confirmed',
                'rol' => 'required|in:administrador,academia,arbitro',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

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
            $usuario->password = bcrypt($request->password); // Encriptar contraseña
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
