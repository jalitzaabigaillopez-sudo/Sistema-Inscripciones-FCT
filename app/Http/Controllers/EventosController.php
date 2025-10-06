<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Modalidad;
use App\Models\TipoEvento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Actualizar estado de eventos finalizados automáticamente
        Evento::where('estado', 'activo')
            ->where('fecha_final', '<', now())
            ->update(['estado' => 'finalizado']);

        // Si es petición AJAX para DataTables
        if ($request->ajax()) {
            $query = Evento::with('tipoEvento'); // Cargar relación

            // Columnas permitidas para búsqueda y ordenamiento
            $allowedColumns = [
                'nombre',
                'descripcion',
                'fecha_inicio_inscripcion',
                'fecha_final_inscripcion',
                'fecha_inicio',
                'fecha_final',
                'estado'
            ];

            // Búsqueda global
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search, $allowedColumns) {
                    foreach ($allowedColumns as $column) {
                        $q->orWhere($column, 'like', "%{$search}%");
                    }

                    // Buscar también en el nombre del tipo de evento
                    $q->orWhereHas('tipoEvento', function ($q2) use ($search) {
                        $q2->where('nombre', 'like', "%{$search}%");
                    });
                });
            }

            // Total de registros filtrados
            $recordsFiltered = $query->count();
            $totalRecords = Evento::count();

            // Ordenamiento
            if ($request->has('order') && count($request->order) > 0) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];

                if (in_array($orderColumnName, $allowedColumns)) {
                    $query->orderBy($orderColumnName, $orderDirection);
                } elseif ($orderColumnName === 'tipo_evento') {
                    // Ordenar por relación tipoEvento
                    $query->join('tipo_eventos', 'eventos.id_tipo_evento', '=', 'tipo_eventos.id_tipo_evento')
                        ->orderBy('tipo_eventos.nombre', $orderDirection)
                        ->select('eventos.*');
                }
            }

            // Paginación
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            // Formatear datos
            $formattedData = $data->map(function ($item) {
                return [
                    'id_evento' => $item->id_evento,
                    'nombre' => $item->nombre,
                    'descripcion' => $item->descripcion,
                    'fecha_inicio_inscripcion' => $item->fecha_inicio_inscripcion ? \Carbon\Carbon::parse($item->fecha_inicio_inscripcion)->format('m/d/Y') : '',
                    'fecha_final_inscripcion' => $item->fecha_final_inscripcion ? \Carbon\Carbon::parse($item->fecha_final_inscripcion)->format('m/d/Y') : '',
                    'fecha_inicio' => $item->fecha_inicio ? \Carbon\Carbon::parse($item->fecha_inicio)->format('m/d/Y') : '',
                    'fecha_final' => $item->fecha_final ? \Carbon\Carbon::parse($item->fecha_final)->format('m/d/Y') : '',
                    'estado' => $item->estado,
                    'tipo_evento' => $item->tipoEvento->nombre ?? 'N/A',
                    'acciones' => $item->id_evento,
                ];
            });

            return response()->json([
                'draw' => $request->input('draw', 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData,
            ]);
        }

        // Para la carga inicial de la vista
        $tipoEvento = TipoEvento::all();
        $modalidades = Modalidad::with('submodalidades')->get();

        return view('catalogos.eventos.index', compact('tipoEvento', 'modalidades'));
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
                'id_modalidad' => 'required|exists:modalidades,id_modalidad',
                'id_submodalidad' => 'required|exists:submodalidades,id_submodalidad',
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

            // Guardar relación con modalidad y submodalidad
            $evento->modalidades()->attach($request->id_modalidad, [
                'id_submodalidad' => $request->id_submodalidad
            ]);

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

    public function api()
    {
        $eventos = Evento::all(); // ← ya no filtramos por estado

        $formateados = $eventos->map(function ($evento) {
            return [
                'id' => $evento->id_evento,
                'title' => $evento->nombre,
                'start' => $evento->fecha_inicio,
                'end' => $evento->fecha_final,
                'status' => $evento->estado,
                'color' => $evento->estado === 'activo' ? '#3788d8' : '#d9534f', // azul para activos, rojo para inactivos
            ];
        });

        return response()->json($formateados);
    }
}
