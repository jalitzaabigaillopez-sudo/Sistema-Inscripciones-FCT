<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Grado;
use Illuminate\Http\Request;

class GradosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Grado::all();
        return view('catalogos.grados.index', compact('data'));
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

        return redirect()->back()->with('success', 'Grado creada correctamente.');
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
}
