<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atleta;
use App\Models\Usuario;

class RegistroAtletasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $atletas = Atleta::paginate(10);
        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);
        $academia = $usuario->academia;
        return view('academia.registrosAtletas', compact('atletas', 'academia'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
     return view('academia.atletas.create');
 

    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'           => 'required|string|max:255',
            'primer_apellido'  => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'sexo'             => 'required|in:Masculino,Femenino',
            'edad'             => 'required|integer|min:0|max:120',
            'peso'             => 'required|numeric|min:0|max:999.99',
            'modalidad'        => 'required|string|max:50',
            'participacion'    => 'required|string|max:50',
            'tipo'             => 'required|string|max:50',
            'grupo'            => 'required|string|max:50',
        ]);

    Atleta::create($validated);
    return redirect()->route('atletas.index')
                     ->with('success', 'Atleta creado exitosamente.');

}

    /**
     * Show the form for editing the specified resource.
     */
     public function edit($id)
    {
        $atleta = Atleta::findOrFail($id);
        return view('academia.editAtleta', compact('atleta'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
    {
        $atleta = Atleta::findOrFail($id);

        $validated = $request->validate([
            'nombre'           => 'required|string|max:255',
            'primer_apellido'  => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'sexo'             => 'required|in:Masculino,Femenino',
            'edad'             => 'required|integer|min:0|max:120',
            'peso'             => 'required|numeric|min:0|max:999.99',
            'modalidad'        => 'required|string|max:50',
            'participacion'    => 'required|string|max:50',
            'tipo'             => 'required|string|max:50',
            'grupo'            => 'required|string|max:50',
        ]);

        $atleta->update($validated);

        return redirect()->route('atletas.edit', $atleta->id)
                         ->with('success', 'Atleta actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
      public function destroy($id)
    {
        $atleta = Atleta::findOrFail($id);
        $atleta->delete();

        return redirect()->route('atletas.destroy')
                         ->with('success', 'Atleta eliminado exitosamente.');
    }
}
