<?php

namespace App\Http\Controllers;

use App\Helpers\RoleGate;
use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Modalidad;
use App\Models\TipoEvento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\SessionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EventosController extends Controller
{
    public function __construct(Request $request)
    {
        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }

        RoleGate::requireAdmin();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //  Actualizar estados automáticamente
        Evento::query()
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_final', '>=', now())
            ->where('estado', '!=', 'en curso')
            ->update(['estado' => 'en curso']);

        Evento::query()
            ->where('fecha_final', '<', now())
            ->where('estado', '!=', 'finalizado')
            ->update(['estado' => 'finalizado']);

        Evento::query()
            ->where('fecha_inicio', '>', now())
            ->whereNotIn('estado', ['activo', 'inactivo'])
            ->update(['estado' => 'activo']);

        //  Si es una petición AJAX (DataTables)
        if ($request->ajax()) {
            $query = Evento::with(['tipoEvento', 'modalidades']); // Cargar relaciones

            $query->orderBy('fecha_inicio', 'desc'); //Evento más próximo

            //  Columnas permitidas para búsqueda y ordenamiento
            $allowedColumns = [
                'nombre',
                'descripcion',
                'fecha_inicio_inscripcion',
                'fecha_final_inscripcion',
                'fecha_final_inscripcion_tardia',
                'fecha_inicio',
                'fecha_final',
                'estado',
                // columnas reales de costos:
                'costo_temprana_1',
                'costo_temprana_2',
                'costo_tardia_1',
                'costo_tardia_2',
            ];

            //  Búsqueda global
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search, $allowedColumns) {
                    foreach ($allowedColumns as $column) {
                        $q->orWhere($column, 'like', "%{$search}%");
                    }

                    // Buscar también por tipo de evento
                    $q->orWhereHas('tipoEvento', function ($q2) use ($search) {
                        $q2->where('nombre', 'like', "%{$search}%");
                    });

                    // Buscar también por nombre de modalidad
                    $q->orWhereHas('modalidades', function ($q3) use ($search) {
                        $q3->where('nombre', 'like', "%{$search}%");
                    });
                });
            }

            //  Totales
            $totalRecords = Evento::count();
            $recordsFiltered = (clone $query)->count();

            //  Ordenamiento dinámico
            if ($request->has('order') && count($request->order) > 0) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];

                if (in_array($orderColumnName, $allowedColumns)) {
                    $query->orderBy($orderColumnName, $orderDirection);
                } elseif ($orderColumnName === 'tipo_evento') {
                    $query->orderBy(
                        TipoEvento::select('nombre')
                            ->whereColumn('tipos_eventos.id_tipo_evento', 'eventos.id_tipo_evento'),
                        $orderDirection
                    );
                }
            }

            //  Paginación
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            //  Formatear datos
            $formattedData = $data->map(function ($item) {
                // Modalidades: mostrar máximo 2 y resto en tooltip
                if ($item->modalidades->isEmpty()) {
                    $modalidadesList = '<span class="text-muted fst-italic">Sin modalidades</span>';
                } else {
                    $names = $item->modalidades->pluck('nombre')->toArray();
                    $visible = array_slice($names, 0, 2);
                    $hidden = array_slice($names, 2);

                    $modalidadesList = '<div class="d-inline-block">';
                    $modalidadesList .= implode(', ', array_map('e', $visible));

                    if (count($hidden) > 0) {
                        $tooltip = e(implode(', ', $hidden));
                        $modalidadesList .= ' <span class="text-primary fw-bold" data-bs-toggle="tooltip" title="' . $tooltip . '">+' . count($hidden) . '</span>';
                    }

                    $modalidadesList .= '</div>';
                }

                return [
                    'imagen' => $item->imagen ? asset('storage/' . $item->imagen) : null,
                    'id_evento' => $item->id_evento,
                    'nombre' => $item->nombre,
                    'descripcion' => $item->descripcion,
                    'fecha_inicio_inscripcion' => $item->fecha_inicio_inscripcion ? \Carbon\Carbon::parse($item->fecha_inicio_inscripcion)->format('Y/m/d') : '',
                    'fecha_final_inscripcion' => $item->fecha_final_inscripcion ? \Carbon\Carbon::parse($item->fecha_final_inscripcion)->format('Y/m/d') : '',
                    'fecha_final_inscripcion_tardia' => $item->fecha_final_inscripcion_tardia ? \Carbon\Carbon::parse($item->fecha_final_inscripcion_tardia)->format('Y/m/d') : '',
                    'fecha_inicio' => $item->fecha_inicio ? \Carbon\Carbon::parse($item->fecha_inicio)->format('Y/m/d') : '',
                    'fecha_final' => $item->fecha_final ? \Carbon\Carbon::parse($item->fecha_final)->format('Y/m/d') : '',
                    'estado' => $item->estado,
                    'tipo_evento' => $item->tipoEvento->nombre ?? 'N/A',
                    'costo' => '
                        <span 
                            class="badge bg-primary text-white"
                            style="cursor:pointer"
                            data-bs-toggle="tooltip"
                            data-bs-html="true"
                            title="
                                <b>Inscripción Temprana</b><br>
                                • 1 modalidad: ₡' . number_format($item->costo_temprana_1, 0, ',', '.') . '<br>
                                • 2 o más: ₡' . number_format($item->costo_temprana_2, 0, ',', '.') . '<br><br>

                                <b>Inscripción Tardía</b><br>
                                • 1 modalidad: ₡' . number_format($item->costo_tardia_1, 0, ',', '.') . '<br>
                                • 2 o más: ₡' . number_format($item->costo_tardia_2, 0, ',', '.') . '
                            "
                        >
                            Ver costos
                        </span>
                    ',
                    'modalidades' => $modalidadesList,
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

        //  Para la carga inicial de la vista
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

            // 🔧 Normalizar valor del costo (punto decimal estándar)
            if ($request->filled('costo')) {
                $request->merge([
                    'costo' => number_format(
                        (float) str_replace(',', '.', $request->costo),
                        2,
                        '.',
                        ''
                    )
                ]);
            }



            // Validate request data
            $validator = Validator::make(
                $request->all(),

                [
                    'nombre' => 'required|string|max:255',
                    'descripcion' => 'nullable|string',
                    'fecha_inicio_inscripcion' => 'required|date',
                    'fecha_final_inscripcion' => 'required|date|after_or_equal:fecha_inicio_inscripcion',
                    'fecha_final_inscripcion_tardia' => 'nullable|date|after_or_equal:fecha_final_inscripcion'
                        . '|before_or_equal:fecha_inicio',
                    'fecha_inicio' => 'required|date|after_or_equal:fecha_final_inscripcion',
                    'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
                    'id_tipo_evento' => 'required|exists:tipos_eventos,id_tipo_evento',
                    'modalidades' => 'required_if:modo,1|array|min:1',
                    'modalidades.*' => 'exists:modalidades,id_modalidad',
                    'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'costo_temprana_1' => 'required|numeric|min:0',
                    'costo_temprana_2' => 'required|numeric|min:0',
                    'costo_tardia_1'   => 'required|numeric|min:0',
                    'costo_tardia_2'   => 'required|numeric|min:0',
                ],
                [
                    // Mensajes personalizados
                    'nombre.required' => 'El nombre del evento es obligatorio.',
                    'fecha_inicio_inscripcion.required' => 'La fecha de inicio de inscripción es obligatoria.',
                    'fecha_final_inscripcion.required' => 'La fecha final de inscripción es obligatoria.',
                    'fecha_final_inscripcion_tardia.after_or_equal' => 'La fecha de inscripción tardía debe ser posterior al fin de inscripción normal.',
                    'fecha_final_inscripcion_tardia.before_or_equal' => 'La inscripción tardía no puede extenderse más allá del inicio del evento.',

                    'fecha_inicio.required' => 'La fecha de inicio del evento es obligatoria.',
                    'fecha_final.required' => 'La fecha final del evento es obligatoria.',
                    'id_tipo_evento.required' => 'Debe seleccionar un tipo de evento.',
                    'modalidades.required' => 'Debe seleccionar al menos una modalidad.',
                    'modalidades.array' => 'El formato de las modalidades no es válido.',
                    'modalidades.*.exists' => 'Una o más modalidades seleccionadas no existen.',
                    'imagen.image' => 'El archivo debe ser una imagen válida.',
                    'imagen.mimes' => 'La imagen debe tener un formato válido (jpeg, png, jpg, gif).',
                    'imagen.max' => 'La imagen no puede pesar más de 2 MB.',
                    'costo_temprana_1.required' => 'El costo de inscripción temprana 1 es obligatorio.',
                    'costo_temprana_1.numeric'  => 'El costo de inscripción temprana 1 debe ser un número válido.',
                    'costo_temprana_1.min'      => 'El costo de inscripción temprana 1 no puede ser negativo.',

                    'costo_temprana_2.required' => 'El costo de inscripción temprana 2 o más es obligatorio.',
                    'costo_temprana_2.numeric'  => 'El costo de inscripción temprana 2 o más debe ser un número válido.',
                    'costo_temprana_2.min'      => 'El costo de inscripción temprana 2 o más no puede ser negativo.',

                    'costo_tardia_1.required' => 'El costo de inscripción tardía 1 es obligatorio.',
                    'costo_tardia_1.numeric'  => 'El costo de inscripción tardía 1 debe ser un número válido.',
                    'costo_tardia_1.min'      => 'El costo de inscripción tardía 1 no puede ser negativo.',

                    'costo_tardia_2.required' => 'El costo de inscripción tardía 2 o más es obligatorio.',
                    'costo_tardia_2.numeric'  => 'El costo de inscripción tardía 2 o más debe ser un número válido.',
                    'costo_tardia_2.min'      => 'El costo de inscripción tardía 2 o más no puede ser negativo.',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // VALOR 0
            $costos = [
                'costo_temprana_1',
                'costo_temprana_2',
                'costo_tardia_1',
                'costo_tardia_2'
            ];

            foreach ($costos as $campo) {
                if (!$request->filled($campo) || $request->$campo === null || $request->$campo === '') {
                    $request->merge([$campo => 0]);
                }
            }

            // Create event
            $evento = new Evento();
            $evento->nombre = $request->nombre;
            $evento->descripcion = $request->descripcion;
            $evento->fecha_inicio_inscripcion = $request->fecha_inicio_inscripcion;
            $evento->fecha_final_inscripcion = $request->fecha_final_inscripcion;
            $evento->fecha_final_inscripcion_tardia = $request->fecha_final_inscripcion_tardia;
            $evento->fecha_inicio = $request->fecha_inicio;
            $evento->fecha_final = $request->fecha_final;
            $evento->id_tipo_evento = $request->id_tipo_evento;
            $evento->estado = 'activo'; // Default 
            $evento->costo_temprana_1 = $request->costo_temprana_1;
            $evento->costo_temprana_2 = $request->costo_temprana_2;
            $evento->costo_tardia_1   = $request->costo_tardia_1;
            $evento->costo_tardia_2   = $request->costo_tardia_2;

            // Imagen
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('eventos', 'public');
                $evento->imagen = $path;
            }

            $evento->save();
            $evento->modalidades()->attach($request->modalidades);

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
            // Cargar evento con sus modalidades
            $evento = Evento::with('modalidades')->findOrFail($id);

            // Obtener solo los IDs de las modalidades relacionadas
            $modalidades_ids = $evento->modalidades->pluck('id_modalidad');

            return response()->json([
                'success' => true,
                'evento' => [
                    'id' => $evento->id,
                    'nombre' => $evento->nombre,
                    'descripcion' => $evento->descripcion,
                    'fecha_inicio_inscripcion' => $evento->fecha_inicio_inscripcion,
                    'fecha_final_inscripcion' => $evento->fecha_final_inscripcion,
                    'fecha_final_inscripcion_tardia' => $evento->fecha_final_inscripcion_tardia,
                    'fecha_inicio' => $evento->fecha_inicio,
                    'fecha_final' => $evento->fecha_final,
                    'id_tipo_evento' => $evento->id_tipo_evento,
                    'costo_temprana_1' => $evento->costo_temprana_1,
                    'costo_temprana_2' => $evento->costo_temprana_2,
                    'costo_tardia_1' => $evento->costo_tardia_1,
                    'costo_tardia_2' => $evento->costo_tardia_2,
                    'estado' => $evento->estado,
                    'imagen' => $evento->imagen
                ],
                // Devolvemos las modalidades asociadas
                'modalidades_ids' => $modalidades_ids,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'No se encontró el evento o ocurrió un error.',
                'exception' => $e->getMessage()
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
            $hoy = now();

            // ===============================
            // Determinar estado del evento
            // ===============================
            $fechaInicioEvento = $evento->fecha_inicio ? Carbon::parse($evento->fecha_inicio) : null;
            $fechaFinalEvento  = $evento->fecha_final ? Carbon::parse($evento->fecha_final) : null;

            $eventoYaTermino = $fechaFinalEvento && $fechaFinalEvento->lt($hoy);
            $eventoEnCurso = $fechaInicioEvento && $fechaInicioEvento->lte($hoy) && $fechaFinalEvento && $fechaFinalEvento->gte($hoy);

            // ===============================
            // Reglas de validación
            // ===============================
            $reglas = [
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'id_tipo_evento' => 'required|exists:tipos_eventos,id_tipo_evento',
                'estado' => 'required|in:activo,inactivo,finalizado',
                'modalidades' => 'required|array|min:1',
                'modalidades.*' => 'exists:modalidades,id_modalidad',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'eliminar_imagen' => 'nullable|boolean',
                'costo_temprana_1' => 'required|numeric|min:0',
                'costo_temprana_2' => 'required|numeric|min:0',
                'costo_tardia_1'   => 'required|numeric|min:0',
                'costo_tardia_2'   => 'required|numeric|min:0',

                'fecha_final_inscripcion_tardia' => 'nullable|date|after_or_equal:fecha_final_inscripcion|before_or_equal:fecha_inicio',
            ];

            // Si el evento no ha iniciado → validar fechas
            if (!$eventoEnCurso && !$eventoYaTermino) {
                $reglas = array_merge($reglas, [
                    'fecha_inicio_inscripcion' => 'required|date',
                    'fecha_final_inscripcion' => 'required|date|after_or_equal:fecha_inicio_inscripcion',
                    'fecha_inicio' => 'required|date|after_or_equal:fecha_final_inscripcion',
                    'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
                ]);
            }

            $mensajes = [
                'nombre.required' => 'El nombre del evento es obligatorio.',
                'fecha_inicio_inscripcion.required' => 'La fecha de inicio de inscripción es obligatoria.',
                'fecha_final_inscripcion.required' => 'La fecha final de inscripción es obligatoria.',
                'fecha_final_inscripcion_tardia.after_or_equal' => 'La inscripción tardía debe ser posterior o igual a la fecha final de inscripción.',
                'fecha_final_inscripcion_tardia.before_or_equal' => 'La inscripción tardía no puede superar la fecha de inicio del evento.',
                'fecha_inicio.required' => 'La fecha de inicio del evento es obligatoria.',
                'fecha_final.required' => 'La fecha final del evento es obligatoria.',
                'id_tipo_evento.required' => 'Debe seleccionar un tipo de evento.',
                'modalidades.required' => 'Debe seleccionar al menos una modalidad.',
                'modalidades.array' => 'El formato de las modalidades no es válido.',
                'modalidades.*.exists' => 'Una o más modalidades seleccionadas no existen.',
                'imagen.image' => 'El archivo debe ser una imagen válida.',
                'imagen.mimes' => 'La imagen debe tener un formato válido (jpeg, png, jpg, gif).',
                'imagen.max' => 'La imagen no puede pesar más de 2 MB.'
            ];

            $validator = Validator::make($request->all(), $reglas, $mensajes);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            // VALOR 0
            $costos = [
                'costo_temprana_1',
                'costo_temprana_2',
                'costo_tardia_1',
                'costo_tardia_2'
            ];

            foreach ($costos as $campo) {
                if (!$request->filled($campo) || $request->$campo === null || $request->$campo === '') {
                    $request->merge([$campo => 0]);
                }
            }

            // ===============================
            // Restricciones por estado
            // ===============================
            if ($eventoYaTermino) {
                return response()->json([
                    'success' => false,
                    'error' => 'Este evento ya finalizó. No se pueden modificar fechas ni datos.'
                ], 403);
            }

            if ($eventoEnCurso) {
                // Bloquear modificación de fechas, mantener las actuales
                $request->merge([
                    'fecha_inicio_inscripcion' => $evento->fecha_inicio_inscripcion,
                    'fecha_final_inscripcion'  => $evento->fecha_final_inscripcion,
                    'fecha_inicio'             => $evento->fecha_inicio,
                    'fecha_final'              => $evento->fecha_final,
                ]);
            } else {
                // Si el frontend no envía las fechas, usar las del evento
                $request->merge([
                    'fecha_inicio_inscripcion' => $request->fecha_inicio_inscripcion ?: $evento->fecha_inicio_inscripcion,
                    'fecha_final_inscripcion'  => $request->fecha_final_inscripcion  ?: $evento->fecha_final_inscripcion,
                    'fecha_inicio'             => $request->fecha_inicio             ?: $evento->fecha_inicio,
                    'fecha_final'              => $request->fecha_final              ?: $evento->fecha_final,
                ]);
            }

            // ===============================
            // Manejo de imagen
            // ===============================
            if ($request->has('eliminar_imagen') && $request->eliminar_imagen == '1') {
                if ($evento->imagen) {
                    Storage::disk('public')->delete($evento->imagen);
                    $evento->imagen = null;
                }
            } elseif ($request->hasFile('imagen')) {
                if ($evento->imagen) {
                    Storage::disk('public')->delete($evento->imagen);
                }
                $path = $request->file('imagen')->store('eventos', 'public');
                $evento->imagen = $path;
            }

            // ===============================
            // Actualizar campos
            // ===============================
            $evento->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_inicio_inscripcion' => $request->fecha_inicio_inscripcion,
                'fecha_final_inscripcion' => $request->fecha_final_inscripcion,
                'fecha_final_inscripcion_tardia' => $request->fecha_final_inscripcion_tardia,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_final' => $request->fecha_final,
                'id_tipo_evento' => $request->id_tipo_evento,
                'estado' => $request->estado,
                'costo_temprana_1' => $request->costo_temprana_1,
                'costo_temprana_2' => $request->costo_temprana_2,
                'costo_tardia_1' => $request->costo_tardia_1,
                'costo_tardia_2' => $request->costo_tardia_2,
            ]);

            // ===============================
            // Modalidades
            // ===============================
            if ($request->has('modalidades')) {
                $evento->modalidades()->sync($request->modalidades);
            }

            return response()->json([
                'success' => true,
                'message' => 'Evento actualizado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Ocurrió un error al actualizar el evento: ' . $e->getMessage(),
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
