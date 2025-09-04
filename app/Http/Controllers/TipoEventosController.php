<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoEvento;


class TipoEventosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $data = TipoEvento::all();
        return view('catalogos.tipos_eventos.index', compact('data'));
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
        ], $mensajes);

        $item = new TipoEvento();

        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion;

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
        ], $mensajes);

        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion;
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

        return back();
    }

}
