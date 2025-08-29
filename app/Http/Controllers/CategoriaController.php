<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Models\Atleta;

class CategoriaController extends Controller
{
    public function obtenerCategorias(Request $request)
    {
        $id_atleta = $request->input('id_atleta');
        $atleta = Atleta::find($id_atleta);
        $division = Division::find($atleta->id_division);

        $categorias = Categoria::where('division', $division->division)->where('sexo', $atleta->sexo)->get();
        

        return response()->json($categorias);
    }
}
