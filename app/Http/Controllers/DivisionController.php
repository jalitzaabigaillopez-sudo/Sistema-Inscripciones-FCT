<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;

class DivisionController extends Controller
{
    public function index()
    {
        $data = Division::all();
        return view('catalogos.divisiones.index', compact('data'));
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
        ];

        $request->validate([
            'division' => 'required|string|max:200|unique:divisiones,division',
            'year_inicio' => 'nullable|string|max:255',
            'year_final' => 'nullable|string|max:255',
        ], $mensajes);

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
            'division.unique' => 'Ya existe una división con ese nombre.',
        ];

   
$request->validate([
    'division' => 'required|string|max:255|unique:divisiones,division,' . $item->id_division . ',id_division',
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