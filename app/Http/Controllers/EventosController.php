<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Evento::all();
        return view('catalogos.eventos.index', compact('data'));
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
        //
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

public function api()
{
    $eventos = Evento::all(); // ← ya no filtramos por estado

    $formateados = $eventos->map(function ($evento) {
        return [
            'id' => $evento->id_evento,
            'title' => $evento->nombre,
            'start' => $evento->fecha_inicio,
            'end' => $evento->fecha_final,
            'status' => $evento->estado,
            'color' => $evento->estado === 'activo' ? '#3788d8' : '#d9534f', // azul para activos, rojo para inactivos
        ];
    });

    return response()->json($formateados);
}


}
    
  
