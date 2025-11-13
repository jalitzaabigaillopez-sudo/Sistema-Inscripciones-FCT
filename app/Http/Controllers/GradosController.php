<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Grado;
use Illuminate\Http\Request;
use App\Services\SessionService;

class GradosController extends Controller
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
            $query = Grado::query();

            // Aplica búsqueda si existe
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    // Ajustar la búsqueda a los campos de Grado
                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('descripcion', 'like', "%{$search}%");
                });
            }

            // Obtener el total de registros ANTES de la paginación
            $recordsFiltered = $query->count();
            $totalRecords = Grado::count(); // Total sin filtros

            // Aplicar ordenamiento
            if ($request->has('order') && count($request->order) > 0) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];

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
                $formattedData[] = [
                    'id_grado' => $item->id_grado, // Asumiendo campo 'id'
                    'nombre' => $item->nombre,
                    'descripcion' => $item->descripcion,
                    'acciones' => $item->id_grado, // Usamos el ID para las acciones
                ];
            }

            return response()->json([
                'draw' => $request->input('draw', 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData,
            ]);
        }

        // Para la carga inicial de la página (retorna la vista)
        return view('catalogos.grados.index');
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
            'nombre.unique' => 'Ya existe un grado con ese nombre.',
        ];

        $request->validate([
            'nombre' => 'required|string|max:200|unique:grados,nombre',
            'descripcion' => 'nullable|string|max:255',
        ], $mensajes);

        $item = new Grado();

        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion;

        $item->save();

        return redirect()->back()->with('success', 'Grado creado correctamente.');
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
        $item = Grado::find($id);
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Grado::find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'Grado no encontrado.');
        }

        $mensajes = [
            'nombre.unique' => 'Ya existe un grado con ese nombre.',
        ];

        $request->validate([
            'nombre' => 'required|string|max:255|unique:grados,nombre,' . $item->id_grado . ',id_grado',
            'descripcion' => 'nullable|string|max:255',
        ], $mensajes);

        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion;
        $item->save();

        return redirect()->back()->with('success', 'Grado actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Grado::find($id);

        $item->delete();

        return back();
    }

    public function obtenerGradosMenoresA()
    {
        $grados = Grado::all();
        return response()->json($grados);
    }
}
