<?php

namespace App\Http\Controllers;

use App\Helpers\RoleGate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoEvento;
use App\Services\SessionService;


class TipoEventosController extends Controller
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
        // Para DataTables (AJAX)
        if ($request->ajax()) {
            $query = TipoEvento::query();

            // Búsqueda global
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('descripcion', 'like', "%{$search}%");
                });
            }

            // Totales
            $recordsFiltered = $query->count();
            $totalRecords = TipoEvento::count();

            // Ordenamiento
            if ($request->has('order') && count($request->order) > 0) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];

                if (in_array($orderColumnName, ['nombre', 'descripcion'])) {
                    $query->orderBy($orderColumnName, $orderDirection);
                }
            }

            // Paginación
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            // Formateo
            $formattedData = $data->map(function ($item) {
                return [
                    'id_tipo_evento' => $item->id_tipo_evento,
                    'nombre' => $item->nombre,
                    'descripcion' => $item->descripcion,
                    'acciones' => $item->id_tipo_evento,
                ];
            });

            return response()->json([
                'draw' => $request->input('draw', 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData,
            ]);
        }

        // Carga inicial
        return view('catalogos.tipos_eventos.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos_eventos = TipoEvento::all(); // o lo que corresponda
        return view('eventos.create', compact('tipos_eventos'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mensajes = [
            'nombre.unique' => 'Ya existe un tipo de evento con ese nombre.',
        ];

        $request->validate([
            'nombre' => 'required|string|max:200|unique:tipos_eventos,nombre',
            'descripcion' => 'nullable|string|max:255',
            'modo' => 'required|in:0,1',
        ], $mensajes);

        $item = new TipoEvento();

        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion;
        $item->modo = $request->modo;

        $item->save();

        return redirect()->back()->with('success', 'Tipo de evento creada correctamente.');
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
        $item = TipoEvento::find($id);
        return response()->json($item);
    }

    public function datos($id)
    {
        $item = TipoEvento::find($id);

        if (!$item) {
            return response()->json(['error' => 'Tipo de evento no encontrado'], 404);
        }

        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = TipoEvento::find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'Tipo de evento no encontrado.');
        }

        $mensajes = [
            'nombre.unique' => 'Ya existe un tipo de evento con ese nombre.',
        ];

        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_eventos,nombre,' . $item->id_tipo_evento . ',id_tipo_evento',
            'descripcion' => 'nullable|string|max:255',
            'modo' => 'required|in:0,1',
        ], $mensajes);

        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion;
        $item->modo = $request->modo;
        $item->save();

        return redirect()->back()->with('success', 'Tipo de evento actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = TipoEvento::find($id);

        $item->delete();

        return back()->with('success', 'Tipo de evento eliminado correctamente.');
    }
}
