<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class ModalidadController extends Controller
{
    public function obtenerModalidades(Request $request)
    {
        $id_evento = $request->input('id_evento');
        $evento = Evento::find($id_evento);
        $modalidades = $evento->modalidades;
        return response()->json($modalidades);
    }
}
