<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\TipoEvento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Evento::all();
        $tipoEvento = TipoEvento::all();
        return view('catalogos.eventos.index', compact('data', 'tipoEvento'));
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

        $item = new Evento();
        
        $item->nombre = $request->nombre;
        $item->descripcion = $request->descripcion ?? null;
        $item->fecha_inicio_inscripcion = $request->fecha_inicio_inscripcion ?? null;
        $item->fecha_final_inscripcion = $request->fecha_final_inscripcion ?? null;
        $item->fecha_inicio = $request->fecha_inicio ?? null;
        $item->fecha_final = $request->fecha_final ?? null;
        $item->imagen = $request->imagen ?? null;
        $item->estado = $request->estado ?? 'activo';
        $item->id_tipo_evento = $request->id_tipo_evento;

        $item->save();

        return redirect()->route('eventos.index')->with('success', 'Evento creado correctamente.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
