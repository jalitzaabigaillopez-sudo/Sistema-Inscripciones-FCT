<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modalidad;
use App\Models\SubModalidad;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Services\SessionService;

class ModalidadesController extends Controller
{
    public function __construct(Request $request)
    {
        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }
    }
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        // Para solicitudes AJAX de DataTables
        if ($request->ajax()) {
            $query = Modalidad::query();

            // Aplica búsqueda si existe
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    // Ajustar la búsqueda a los campos de Modalidad
                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('descripcion', 'like', "%{$search}%")
                        // Buscar también dentro de las submodalidades asociadas
                        ->orWhereHas('subModalidades', function ($sub) use ($search) {
                            $sub->where('nombre', 'like', "%{$search}%")
                                ->orWhere('descripcion', 'like', "%{$search}%");
                        });
                });
            }

            // Obtener el total de registros ANTES de la paginación
            $recordsFiltered = $query->count();
            $totalRecords = Modalidad::count(); // Total sin filtros

            // Aplicar ordenamiento
            if ($request->has('order') && count($request->order) > 0) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];

                // Asegurar que solo se ordene por columnas válidas de la tabla Modalidad
                if (in_array($orderColumnName, ['nombre', 'descripcion'])) {
                    $query->orderBy($orderColumnName, $orderDirection);
                }
            }

            // Aplicar paginación
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            // Formatear datos para DataTables
            $formattedData = [];
            foreach ($data as $item) {
                if ($item->subModalidades->isEmpty()) {
                    $subModalidadesList = '<span class="text-muted fst-italic">Sin submodalidades</span>';
                } else {
                    $names = $item->subModalidades->pluck('nombre')->toArray();
                    $visible = array_slice($names, 0, 2);
                    $hidden = array_slice($names, 2);

                    $subModalidadesList = '<div class="d-inline-block">';
                    $subModalidadesList .= implode(', ', array_map('e', $visible));

                    if (count($hidden) > 0) {
                        $tooltip = e(implode(', ', $hidden));
                        $subModalidadesList .= ' <span class="text-primary fw-bold" data-bs-toggle="tooltip" title="' . $tooltip . '">+' . count($hidden) . '</span>';
                    }

                    $subModalidadesList .= '</div>';
                }

                $formattedData[] = [
                    'id_modalidad' => $item->id_modalidad,
                    'nombre' => $item->nombre,
                    'descripcion' => $item->descripcion ?? '<span class="text-muted">—</span>',
                    'submodalidades' => $subModalidadesList,
                    'acciones' => $item->id_modalidad,
                ];
            }

            return response()->json([
                'draw' => $request->input('draw', 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData,
            ]);
        }

        $modalidades = Modalidad::all();
        $submodalidades = SubModalidad::all();

        // Para la carga inicial de la página (retorna la vista)
        return view('catalogos.modalidades.index', compact('modalidades', 'submodalidades'));
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
        $mensajes = [
            'nombre.unique' => 'Ya existe una modalidad con ese nombre.',
        ];

        $request->validate([
            'nombre' => 'required|string|max:255|unique:modalidades,nombre',
            'descripcion' => 'nullable|string|max:255',
        ], $mensajes);

        $item = new Modalidad();

        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion;

        $item->save();

        // Asignar submodalidades si existen
        if ($request->has('submodalidades')) {
            $item->subModalidades()->attach($request->submodalidades);
        }

        return redirect()->back()->with('success', 'Modalidad creada correctamente.');
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
        $item = Modalidad::with('subModalidades')->find($id);
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Modalidad::find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'Modalidad no encontrada.');
        }

        $mensajes = [
            'nombre.unique' => 'Ya existe una modalidad con ese nombre.',
        ];

        $request->validate([
            'nombre' => 'required|string|max:255|unique:modalidades,nombre,' . $item->id_modalidad . ',id_modalidad',
            'descripcion' => 'nullable|string|max:255',
            'submodalidades' => 'nullable|array',

        ], $mensajes);

        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion;
        $item->save();

        // 🔹 Actualizar relaciones (sync reemplaza lo anterior)
        if ($request->has('submodalidades')) {
            $item->subModalidades()->sync($request->submodalidades);
        } else {
            $item->subModalidades()->detach(); // si no selecciona ninguna
        }

        return redirect()->back()->with('success', 'Modalidad actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Modalidad::find($id);

        $item->delete();

        return back();
    }
}
