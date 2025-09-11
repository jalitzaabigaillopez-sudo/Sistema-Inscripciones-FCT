<?php

namespace App\Http\Controllers;

use App\Models\Academia;
use App\Models\Evento;
use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\ModalidadEvento;
use App\Models\Usuario;

class InscripcionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Inscripcion::all();
        $eventos = Evento::all();
        $academias = Academia::all();
        return view('catalogos.inscripciones.index', compact('data', 'eventos', 'academias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function crearInscripcion(Request $request)
    {
        $validateData = $request->validate([
            'atleta' => 'required|integer',// id
            'evento' => 'required|integer',// id
            'modalidad' => 'required|integer',// id
        ]);

        // Crear modalidades_eventos
        $modalidaEvento = ModalidadEvento::create([
            'id_evento' => $validateData['evento'],
            'id_modalidad' => $validateData['modalidad'],
        ]);
        $modalidaEvento->save();

        // Crear inscripcion
        $inscripcion = Inscripcion::create([
            'id_atleta' => $validateData['atleta'],
            'id_modalidad_evento' => $modalidaEvento->id_modalidad_evento,
            'fecha_inscripcion' => $validateData['fecha_inscripcion'],
            'estado' => $validateData['estado'],
        ]);
        $inscripcion->save();
    }

    //====================================================================================================================================
    public function vistaInscripcionesAcademia(Request $request)
    {
        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);

        $eventos = Evento::all();
        $academia = $usuario->academia;
        $atletas = $academia->atletas;
        return view('academia/inscripcionEvento', compact('eventos', 'academia','atletas'));
    }

    public function vistaMisInscripcionesAcademia(Request $request)
    {
        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);
        $academia = $usuario->academia;
        return view('academia/misInscripciones', compact('academia'));
    }
}
