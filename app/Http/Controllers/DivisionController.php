<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Atleta;


class DivisionController extends Controller
{
    public function index()
    {
        $divisiones = Division::all();
        return view('catalogos.divisiones.index', compact('divisiones'));
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

        $item = new Division();

        $item->division = $request->nombre;
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
    public function edit(string $id)
    {
        $item = Division::find($id);
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Division::find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'División no encontrada.');
        }

        $mensajes = [
            'nombre.unique' => 'Ya existe una división con ese nombre.',
        ];

        $request->validate([
            'division' => 'required|string|max:255|unique:divisiones,nombre,' . $item->id_division . ',id_division',
            'year_inicio' => 'nullable|string|max:255',
            'year_final' => 'nullable|string|max:255',
        ], $mensajes);

        $item->division = $request->division;
        $item->year_inicio = $request->year_inicio;
        $item->year_final = $request->year_final;
        $item->save();

        return redirect()->back()->with('success', 'División actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Division::find($id);

        $item->delete();

        return back();
    }
}
