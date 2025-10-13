<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Services\SessionService;

class ModalidadController extends Controller
{
    public function __construct(Request $request)
    {
        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }
    }
    public function obtenerModalidades(Request $request)
    {
        $id_evento = $request->input('id_evento');
        $evento = Evento::find($id_evento);
        $modalidades = $evento->modalidades;
        return response()->json($modalidades);
    }
}
