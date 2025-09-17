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
        $eventos = Evento::where('estado', 'activo')->get();
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

    public function inscribirAtleta(Request $request)
    {

        $atleta = $request->input('atleta');

        $inscripcion = Inscripcion::create([
            'id_atleta' => $atleta['id_atleta'],
            'id_evento' => $atleta['id_evento'],
            'id_modalidad' => $atleta['id_modalidad'],
            'id_subModalidad' => $atleta['id_subModalidad'],
            'id_categoria' => $atleta['id_categoria'],
            'fecha_inscripcion' => date("Y-m-d H:i:s"),
            'estado' => 'inactiva',
            'codigo_equipo' => $atleta['grupo'],
        ]);
        return response()->json($inscripcion, 201);
    }

    //====================================================================================================================================
    public function vistaInscripcionesAcademia(Request $request)
    {
        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);

        $eventos = Evento::all();
        $academia = $usuario->academia;
        $atletas = $academia->atletas;
        return view('academia/inscripcionEvento', compact('eventos', 'academia', 'atletas'));
    }

    public function vistaMisInscripcionesAcademia(Request $request)
    {
        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);
        $academia = $usuario->academia;
        return view('academia/misInscripciones', compact('academia'));
    }
}
