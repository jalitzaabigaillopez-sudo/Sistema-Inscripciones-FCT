<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modalidad;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class ModalidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Modalidad::all();
        return view('catalogos.modalidades.index', compact('data'));
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
        $item = Modalidad::find($id);
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
        ], $mensajes);

        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion;
        $item->save();

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
