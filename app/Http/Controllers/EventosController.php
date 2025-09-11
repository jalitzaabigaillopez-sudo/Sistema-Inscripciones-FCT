<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\TipoEvento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Evento::all();
        $tipoEvento = TipoEvento::all();
        return view('catalogos.eventos.index', compact('data', 'tipoEvento'));
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
            // Validate request data
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'fecha_inicio_inscripcion' => 'required|date',
                'fecha_final_inscripcion' => 'required|date|after_or_equal:fecha_inicio_inscripcion',
                'fecha_inicio' => 'required|date|after_or_equal:fecha_final_inscripcion',
                'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
                'id_tipo_evento' => 'required|exists:tipos_eventos,id_tipo_evento',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create event
            $evento = new Evento();
            $evento->nombre = $request->nombre;
            $evento->descripcion = $request->descripcion;
            $evento->fecha_inicio_inscripcion = $request->fecha_inicio_inscripcion;
            $evento->fecha_final_inscripcion = $request->fecha_final_inscripcion;
            $evento->fecha_inicio = $request->fecha_inicio;
            $evento->fecha_final = $request->fecha_final;
            $evento->id_tipo_evento = $request->id_tipo_evento;
            $evento->estado = 'activo'; // Default to active as per user creation code

            // Imagen
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('eventos', 'public');
                $evento->imagen = $path;
            }

            $evento->save();

            return response()->json([
                'success' => true,
                'message' => 'Evento registrado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Ocurrió un error al registrar el evento: ' . $e->getMessage()
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
        try {
            $evento = Evento::findOrFail($id);
            return response()->json([
                'success' => true,
                'evento' => [
                    'id' => $evento->id,
                    'nombre' => $evento->nombre,
                    'descripcion' => $evento->descripcion,
                    'fecha_inicio_inscripcion' => $evento->fecha_inicio_inscripcion,
                    'fecha_final_inscripcion' => $evento->fecha_final_inscripcion,
                    'fecha_inicio' => $evento->fecha_inicio,
                    'fecha_final' => $evento->fecha_final,
                    'id_tipo_evento' => $evento->id_tipo_evento,
                    'estado' => $evento->estado,
                    'imagen' => $evento->imagen
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'No se encontró el evento o ocurrió un error.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $evento = Evento::findOrFail($id);

            // ... (Tu código de validación existente) ...
            $validator = Validator::make($request->all(), [
                // ... (Reglas de validación) ...
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'eliminar_imagen' => 'nullable|boolean', // Añadir esta regla de validación
            ]);

            if ($validator->fails()) {
                // ...
            }

            // Lógica de manejo de imagen
            if ($request->has('eliminar_imagen') && $request->eliminar_imagen == '1') {
                // El usuario solicitó eliminar la imagen
                if ($evento->imagen) {
                    Storage::disk('public')->delete($evento->imagen);
                    $evento->imagen = null; // Borrar la ruta de la imagen en la base de datos
                }
            } elseif ($request->hasFile('imagen')) {
                // El usuario subió una nueva imagen
                // 1. Eliminar la imagen anterior si existe
                if ($evento->imagen) {
                    Storage::disk('public')->delete($evento->imagen);
                }
                // 2. Guardar la nueva imagen
                $path = $request->file('imagen')->store('eventos', 'public');
                $evento->imagen = $path;
            }

            // ... (Actualizar el resto de los campos del evento) ...
            $evento->nombre = $request->nombre;
            $evento->descripcion = $request->descripcion;
            $evento->fecha_inicio_inscripcion = $request->fecha_inicio_inscripcion;
            $evento->fecha_final_inscripcion = $request->fecha_final_inscripcion;
            $evento->fecha_inicio = $request->fecha_inicio;
            $evento->fecha_final = $request->fecha_final;
            $evento->id_tipo_evento = $request->id_tipo_evento;
            $evento->estado = $request->estado;

            $evento->save();

            return response()->json([
                'success' => true,
                'message' => 'Evento actualizado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Ocurrió un error al actualizar el evento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Evento::find($id);

        $item->delete();

        return back();
    }
}
