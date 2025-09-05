<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Models\Atleta;

class CategoriaController extends Controller
{

    public function index()
    {
        $data = Categoria::all();
        $divisiones = Division::all();
        return view('catalogos.categorias.index', compact('data', 'divisiones'));
    }

    public function obtenerCategorias(Request $request)
    {
        $id_atleta = $request->input('id_atleta');
        $atleta = Atleta::find($id_atleta);
        $division = Division::find($atleta->id_division);

        $categorias = Categoria::where('division', $division->division)->where('sexo', $atleta->sexo)->get();


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
