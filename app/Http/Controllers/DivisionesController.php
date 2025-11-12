<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Services\SessionService;
use Illuminate\Support\Facades\Validator;

class DivisionesController extends Controller
{
    public function __construct(Request $request)
    {
        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }
    }

    public function index(Request $request)
    {
        //  Si es una petición AJAX (DataTables)
        if ($request->ajax()) {
            $query = Division::query();

            // Filtro de búsqueda global
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('division', 'like', "%{$search}%")
                        ->orWhere('year_inicio', 'like', "%{$search}%")
                        ->orWhere('year_final', 'like', "%{$search}%");
                });
            }

            // ORDENAR AUTOMÁTICAMENTE POR AÑO DE INICIO ASCENDENTE
            $query->orderBy('year_inicio', 'asc');

            // Totales
            $recordsFiltered = $query->count();
            $totalRecords = Division::count();

            //  Ordenamiento dinámico
            if ($request->has('order') && count($request->order) > 0) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];

                if (in_array($orderColumnName, ['division', 'year_inicio', 'year_final'])) {
                    $query->orderBy($orderColumnName, $orderDirection);
                }
            }

            // Paginación
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            // Formateo para DataTables
            $formattedData = $data->map(function ($item) {
                return [
                    'id_division' => $item->id_division,
                    'division' => $item->division,
                    'year_inicio' => $item->year_inicio,
                    'year_final' => $item->year_final,
                    'acciones' => $item->id_division,
                ];
            });

            return response()->json([
                'draw' => $request->input('draw', 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData,
            ]);
        }

        //  Carga inicial de la vista
        return view('catalogos.divisiones.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisiones = Division::all(); // o lo que corresponda
        return view('divisiones.create', compact('divisiones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mensajes = [
            'division.unique' => 'Ya existe una división con ese nombre.',
            'division.required' => 'El nombre de la división es obligatorio.',
            'year_inicio.required' => 'El año de inicio es obligatorio.',
            'year_inicio.integer' => 'El año de inicio debe ser un número.',
            'year_inicio.min' => 'El año de inicio debe ser como mínimo 1900.',
            'year_inicio.max' => 'El año de inicio no puede ser mayor que 2100.',
            'year_final.required' => 'El año de finalización es obligatorio.',
            'year_final.integer' => 'El año de finalización debe ser un número.',
            'year_final.min' => 'El año de finalización debe ser como mínimo 1900.',
            'year_final.max' => 'El año de finalización no puede ser mayor que 2100.',
            'year_final.gte' => 'El año de finalización no puede ser menor que el año de inicio.',
        ];

        $validator = Validator::make($request->all(), [
            'division' => 'required|string|max:200|unique:divisiones,division',
            'year_inicio' => 'required|integer|min:1900|max:2100',
            'year_final' => 'required|integer|min:1900|max:2100|gte:year_inicio',
        ], $mensajes);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 🔎 Validar traslape
        $existeTraslape = Division::where(function ($query) use ($request) {
            $query->whereBetween('year_inicio', [$request->year_inicio, $request->year_final])
                ->orWhereBetween('year_final', [$request->year_inicio, $request->year_final])
                ->orWhere(function ($q) use ($request) {
                    $q->where('year_inicio', '<=', $request->year_inicio)
                        ->where('year_final', '>=', $request->year_final);
                });
        })->exists();

        if ($existeTraslape) {
            return response()->json([
                'errors' => [
                    'year_inicio' => [
                        'El rango de años ya está cubierto por otra división registrada.'
                    ]
                ]
            ], 422);
        }

        $item = new Division();

        $item->division = $request->division;
        $item->year_inicio = $request->year_inicio;
        $item->year_final = $request->year_final;

        $item->save();

        return redirect()->back()->with('success', 'División creada correctamente.');
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

    public function edit(string $id_division)
    {
        $item = Division::find($id_division);

        if (!$item) {
            return response()->json(['error' => 'No se encontró la división.'], 404);
        }

        return response()->json($item);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_division)
    {
        $item = Division::find($id_division);

        if (!$item) {
            return redirect()->back()->with('error', 'División no encontrada.');
        }

        $mensajes = [
            'division.required' => 'El nombre de la división es obligatorio.',
            'division.unique' => 'Ya existe una división con ese nombre.',
            'year_inicio.required' => 'El año de inicio es obligatorio.',
            'year_inicio.integer' => 'El año de inicio debe ser un número.',
            'year_inicio.min' => 'El año de inicio debe ser al menos 1900.',
            'year_inicio.max' => 'El año de inicio no puede ser mayor a 2100.',
            'year_final.required' => 'El año de finalización es obligatorio.',
            'year_final.integer' => 'El año de finalización debe ser un número.',
            'year_final.min' => 'El año de finalización debe ser al menos 1900.',
            'year_final.max' => 'El año de finalización no puede ser mayor a 2100.',
            'year_final.gte' => 'El año de finalización no puede ser menor que el año de inicio.',
        ];


        $validator = Validator::make($request->all(), [
            'division' => 'required|string|max:200|unique:divisiones,division,' . $item->id_division . ',id_division',
            'year_inicio' => 'required|integer|min:1900|max:2100',
            'year_final' => 'required|integer|min:1900|max:2100|gte:year_inicio',
        ], $mensajes);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validar rangoo
        // $existeRango = Division::where(function ($query) use ($request) {
        //     $query->whereBetween('year_inicio', [$request->year_inicio, $request->year_final])
        //         ->orWhereBetween('year_final', [$request->year_inicio, $request->year_final])
        //         ->orWhere(function ($q) use ($request) {
        //             $q->where('year_inicio', '<=', $request->year_inicio)
        //                 ->where('year_final', '>=', $request->year_final);
        //         });
        // })
        //     ->where('id_division', '!=', $item->id_division)
        //     ->exists();

        // if ($existeRango) {
        //     return response()->json([
        //         'errors' => [
        //             'year_inicio' => ['El rango de años ya está cubierto por otra división registrada.']
        //         ]
        //     ], 422);
        // }

        $item->division = $request->division;
        $item->year_inicio = $request->year_inicio;
        $item->year_final = $request->year_final;
        $item->save();

        return redirect()->back()->with('success', 'División actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_division)
    {
        $item = Division::find($id_division);

        $item->delete();

        return back()->with('success', 'División eliminada correctamente.');
    }


    public function datos($id_division)
    {
        $division = Division::findOrFail($id_division);
        return response()->json($division);
    }
}
