<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atleta;
use App\Models\Usuario;
use App\Models\Academia;

class RegistroAtletasController extends Controller
{
    /**
     * Mostrar lista de atletas
     */

/*    public function index(Request $request)
    {
        $atletas = Atleta::paginate(10);
        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);
        $academia = $usuario->academia;
        return view('academia.registrosAtletas', compact('atletas', 'academia'));
    }
    */


    public function index(Request $request)
{
    $academia = Academia::first(); // o el que corresponda

    $busqueda = $request->input('buscar');

    $atletas = Atleta::when($busqueda, function ($query, $busqueda) {
        $query->where('nombre', 'like', "%{$busqueda}%")
              ->orWhere('primer_apellido', 'like', "%{$busqueda}%")
              ->orWhere('segundo_apellido', 'like', "%{$busqueda}%")
              ->orWhere('identificacion', 'like', "%{$busqueda}%")
              ->orWhere('sexo', 'like', "%{$busqueda}%")
              ->orWhere('tipo_identificacion', 'like', "%{$busqueda}%");
    })
    ->orderBy('id_atleta', 'asc')->paginate(10);

    return view('academia.registrosAtletas', compact('atletas', 'academia', 'busqueda'));
    
}
    /**
     * Guardar un nuevo atleta
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_identificacion' => 'required|string|max:50',
            'identificacion'      => 'required|string|max:50|unique:atletas,identificacion',
            'primer_apellido'     => 'required|string|max:255',
            'segundo_apellido'    => 'nullable|string|max:255',
            'nombre'              => 'required|string|max:255',
            'sexo'                => 'required|in:Masculino,Femenino',
            'fecha_nacimiento'    => 'required|date',
        ]);

        // si tienes sesión con academia puedes asignarla automáticamente:
        // $validated['id_academia'] = auth()->user()->academia_id;
          $validated['id_division'] = $request->input('id_division', 1); // Asignar una división por defecto si no se proporciona

        Atleta::create($validated);

        return redirect()->route('registro-atletas.index')->with('success', 'Atleta creado exitosamente.');
    }

    /**
     * Actualizar un atleta existente
     */
    public function update(Request $request, $id)
    {
        $atleta = Atleta::findOrFail($id);

        $validated = $request->validate([
            'tipo_identificacion' => 'required|string|max:50',
            'identificacion'      => 'required|string|max:50|unique:atletas,identificacion,'.$id.',id_atleta',
            'primer_apellido'     => 'required|string|max:255',
            'segundo_apellido'    => 'nullable|string|max:255',
            'nombre'              => 'required|string|max:255',
            'sexo'                => 'required|in:Masculino,Femenino',
            'fecha_nacimiento'    => 'required|date',
        ]);

        $atleta->update($validated);

        return redirect()->route('registro-atletas.index')->with('success', 'Atleta actualizado correctamente.');
    }

    /**
     * Eliminar un atleta
     */
    public function destroy($id)
    {
        $atleta = Atleta::findOrFail($id);
        $atleta->delete();

        return redirect()->route('registro-atletas.index')->with('success', 'Atleta eliminado correctamente.');
    }

}

