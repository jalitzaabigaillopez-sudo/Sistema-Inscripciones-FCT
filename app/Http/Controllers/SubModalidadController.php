<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modalidad;
use App\Models\SubModalidad;

class SubModalidadController extends Controller
{
    public function obtenerSubModalidades(Request $request)
    {
        $id_modalidad = $request->input('id_modalidad');
        $modalidad = Modalidad::find($id_modalidad);
        $submodalidades = $modalidad->subModalidades;

        return response()->json($submodalidades);
    }


    public function index(Request $request)
    {
        // Si la petición es AJAX (para DataTables)
        if ($request->ajax()) {
            $query = SubModalidad::query();

            $query->orderBy('id_subModalidad', 'desc'); // más reciente primero


            // Filtro de búsqueda
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('descripcion', 'like', "%{$search}%")
                        ->orWhere('cantidad_atletas', 'like', "%{$search}%");

                    // 🔍 Agregar búsqueda textual para el booleano
                    if (in_array($search, ['si', 'sí', '1', 'true', 'mixto'])) {
                        $q->orWhere('sexo_mixto', 1);
                    } elseif (in_array($search, ['no', '0', 'false', 'femenino', 'masculino'])) {
                        $q->orWhere('sexo_mixto', 0);
                    }
                });
            }

            // Conteo de registros
            $recordsFiltered = $query->count();
            $totalRecords = SubModalidad::count();

            // Ordenamiento dinámico
            if ($request->has('order') && count($request->order) > 0) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];

                if (in_array($orderColumnName, ['nombre', 'descripcion', 'cantidad_atletas'])) {
                    $query->orderBy($orderColumnName, $orderDirection);
                }
            }

            // Paginación
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            // Formateo de los datos para DataTables
            $formattedData = [];
            foreach ($data as $item) {
                $formattedData[] = [
                    'id_subModalidad' => $item->id_subModalidad,
                    'nombre' => $item->nombre,
                    'descripcion' => $item->descripcion ?? '<span class="text-muted">—</span>',
                    'cantidad_atletas' => $item->cantidad_atletas,
                    'sexo_mixto' => $item->sexo_mixto ?
                        '<span class="badge bg-success">Sí</span>' :
                        '<span class="badge bg-danger">No</span>',
                    'acciones' => $item->id_subModalidad,
                ];
            }

            // Respuesta JSON para DataTable
            return response()->json([
                'draw' => $request->input('draw', 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData,
            ]);
        }

        //  Vista normal (no AJAX)
        return view('catalogos.modalidades.submodalidades.index');
    }


    public function store(Request $request)
    {

        $mensajes = [
            'nombre.unique' => 'Ya existe una submodalidad con ese nombre.',
            'nombre.required' => 'El nombre es obligatorio.',
            'cantidad_atletas.required' => 'Debe ingresar la cantidad de atletas.',
            'cantidad_atletas.min' => 'La cantidad de atletas debe ser al menos 1.',
        ];

        $request->validate([
            'nombre' => 'required|string|max:255|unique:subModalidades,nombre',
            'descripcion' => 'nullable|string|max:255',
            'cantidad_atletas' => 'required|integer|min:1',
            'sexo_mixto' => 'boolean'
        ], $mensajes);

        $item = new SubModalidad();
        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion;
        $item->cantidad_atletas = $request->cantidad_atletas;
        $item->sexo_mixto = $request->has('sexo_mixto');
        $item->save();

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $sub = SubModalidad::find($id);
        if (!$sub) {
            return response()->json(['error' => 'No encontrada'], 404);
        }
        return response()->json($sub);
    }

    public function update(Request $request, $id)
    {
        $item = SubModalidad::findOrFail($id);

        $mensajes = [
            'nombre.unique' => 'Ya existe una submodalidad con ese nombre.',
            'nombre.required' => 'El nombre es obligatorio.',
            'cantidad_atletas.required' => 'Debe ingresar la cantidad de atletas.',
            'cantidad_atletas.min' => 'La cantidad de atletas debe ser al menos 1.',
        ];

        $request->validate([
            'nombre' => 'required|string|max:255|unique:subModalidades,nombre,' . $item->id_subModalidad . ',id_subModalidad',
            'descripcion' => 'nullable|string|max:255',
            'cantidad_atletas' => 'required|integer|min:1'

        ], $mensajes);

        $item->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'cantidad_atletas' => $request->cantidad_atletas,
            'sexo_mixto' => $request->boolean('sexo_mixto') ? 1 : 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        SubModalidad::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
