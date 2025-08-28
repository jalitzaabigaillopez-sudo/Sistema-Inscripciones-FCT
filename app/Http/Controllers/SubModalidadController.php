<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modalidad;


class SubModalidadController extends Controller
{
    public function obtenerSubModalidades(Request $request)
    {
        $id_modalidad = $request->input('id_modalidad');
        $modalidad = Modalidad::find($id_modalidad);
        $submodalidades = $modalidad->subModalidades;

        return response()->json($submodalidades);
    }
}
