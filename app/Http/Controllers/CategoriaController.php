<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Models\Atleta;

class CategoriaController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Categoria::with('division'); // si tienes relación con Division

            // Búsqueda
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('sexo', 'like', "%{$search}%")
                        ->orWhere('peso_min', 'like', "%{$search}%")
                        ->orWhere('peso_max', 'like', "%{$search}%")

                        // Buscar en la relación division
                        ->orWhereHas('division', function ($q2) use ($search) {
                            $q2->where('division', 'like', "%{$search}%");
                        });
                });
            }

            $recordsFiltered = $query->count();
            $totalRecords = Categoria::count();

            // Ordenamiento
            if ($request->has('order') && count($request->order) > 0) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                $orderColumnName = $request->columns[$orderColumnIndex]['data'];

                if (in_array($orderColumnName, ['sexo', 'peso_min', 'peso_max'])) {
                    $query->orderBy($orderColumnName, $orderDirection);
                }
            }

            // Paginación
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $categorias = $query->skip($start)->take($length)->get();

            $formattedData = [];
            foreach ($categorias as $item) {
                $formattedData[] = [
                    'id_categoria' => $item->id_categoria,
                    'division' => $item->division ? $item->division->division : 'N/A',
                    'sexo' => $item->sexo,
                    'peso_min' => $item->peso_min,
                    'peso_max' => $item->peso_max,
                    'acciones' => $item->id_categoria,
                ];
            }

            return response()->json([
                'draw' => $request->input('draw', 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $recordsFiltered,
                'data' => $formattedData,
            ]);
        }

        $divisiones = Division::all();
        return view('catalogos.categorias.index', compact('divisiones'));
    }


    public function obtenerCategorias(Request $request)
    {
        $id_division = $request->input('id_division');
        $sexo = $request->input('sexo');

        // $division = Division::find($id_division);
        $categorias = Categoria::where('id_division', $id_division)->where('sexo', $sexo)->get();

        return response()->json($categorias);
    }

    public function store(Request $request)
    {

        // Validar los datos del formulario
        $validatedData = $request->validate([
            'id_division' => [
                'required',
                'numeric',
                'exists:divisiones,id_division'
            ],
            'sexo' => 'required|string|max:255',
            'peso_min' => 'required|numeric',
            'peso_max' => 'required|numeric',
        ]);

        // Validar si la combinación de campos ya existe
        $existingCategory = Categoria::where('id_division', $validatedData['id_division'])
            ->where('sexo', $validatedData['sexo'])
            ->where('peso_min', $validatedData['peso_min'])
            ->where('peso_max', $validatedData['peso_max'])
            ->first();

        if ($existingCategory) {
            // Si la categoría existe, devuelve un error 409 (Conflict)
            return response()->json(['error' => 'Ya existe una categoría con estas características.'], 409);
        }

        $item = new Categoria();

        $item->id_division = $request->id_division;
        $item->sexo = $request->sexo;
        $item->peso_min = $request->peso_min;
        $item->peso_max = $request->peso_max;

        $item->save();

        return response()->json(['success' => 'Categoría creada correctamente.']);
    }

    public function edit(string $id)
    {
        $item = Categoria::find($id);
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Categoria::findOrFail($id);

        $validated = $request->validate([
            'id_division' => 'required|numeric|exists:divisiones,id_division',
            'sexo' => 'required|string|max:255',
            'peso_min' => 'required|numeric',
            'peso_max' => 'required|numeric',
        ]);

        $item->id_division = $validated['id_division'];
        $item->sexo = $validated['sexo'];
        $item->peso_min = $validated['peso_min'];
        $item->peso_max = $validated['peso_max'];
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada correctamente.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Categoria::find($id);

        $item->delete();

        return back();
    }
}
