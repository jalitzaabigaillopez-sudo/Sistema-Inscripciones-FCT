<?php

namespace App\Http\Controllers;

use App\Models\Academia;
use App\Models\Evento;
use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\ModalidadEvento;
use App\Models\Atleta;

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

    public function vistaInscripcionP1()
    {
        //vista, evento e inscricipcion de prueba
        $academia = Academia::find(1);
        $evento = Evento::with('modalidades')->find(7);

        return view('inscripciones-parte1', compact('academia', 'evento'));
    }

    public function vistaInscripcionP2(Request $request)
    {
        $ids = json_decode($request->input('ids'), true);//son las ids de los atletas guardada en forma de arreglo en un input tipo hidden desde js
        $atletas = Atleta::whereIn('id_atleta', $ids)->get();

        $id_evento = $request->input('id_evento');
        $evento = Evento::with('modalidades')->find($id_evento);        

        return view('inscripciones-parte2', compact('atletas', 'evento'));
    }

    //====================================================================================================================================
    public function vistaInscripcionesAcademia($id_academia)
    {
        $eventos = Evento::all();
        $academia = Academia::find($id_academia);
        $atletas = $academia->atletas;
        return view('academia/inscripcionEvento', compact('eventos', 'academia','atletas'));
    }
}
