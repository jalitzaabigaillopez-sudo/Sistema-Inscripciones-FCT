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
            'id_academia' => $atleta['id_academia'],
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

        $bloquearSelectEventos = false;

        $eventos = Evento::all();
        $academia = $usuario->academia;
        $atletas = $academia->atletas;
        return view('academia/inscripcionEvento', compact('eventos', 'academia', 'atletas', 'bloquearSelectEventos'));
    }

    public function vistaMisInscripcionesAcademia(Request $request)
    {
        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);
        $academia = $usuario->academia;

        // Obtener todas las inscripciones de la academia con relaciones
        $inscripciones = Inscripcion::with(['evento', 'atletas'])
            ->where('id_academia', $academia->id_academia)->get()
            ->groupBy(function ($ins) {

                return $ins->id_evento;// agrupar por id_evento
            });

        $inscripcionesAgrupadas = [];

        foreach ($inscripciones as $id_evento => $grupo) {

            $primera = $grupo->first();// Tomamar la primera inscripción del grupo para datos comunes

            $atletas = $grupo->pluck('atletas')->flatten();// Todos los atletas del grupo

            // Entrenadores de este grupo
            $entrenadores = $atletas->where('rol', 'entrenador')
                ->map(function ($a) {
                    return trim("{$a->nombre} {$a->primer_apellido} {$a->segundo_apellido}");
                })->unique()->implode(', ');

            $cantidad_inscritos = $atletas->count();// Cantidad de inscritos

            $inscripcionesAgrupadas[] = (object) [
                'evento' => $primera->evento,
                'entrenador' => $entrenadores ?: '-',
                'cantidad_inscritos' => $cantidad_inscritos,
                'estado' => $primera->estado,
            ];
        }
        return view('academia/misInscripciones', compact('inscripcionesAgrupadas', 'academia'));
    }

    public function editarInscripcion(Request $request, $id_evento)
    {
        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);

        $eventos = Evento::where('id_evento', $id_evento)->get();
        $bloquearSelectEventos = true;

        $academia = $usuario->academia;
        $atletas = $academia->atletas;

        $inscripciones = Inscripcion::with('atleta')
            ->where('id_evento', $id_evento)
            ->where('id_academia', $academia->id_academia)
            ->get();

        $atletasInscripcion = $inscripciones->map(function ($inscripcion) {
            $atleta = $inscripcion->atleta;
            $atleta->grupo = $inscripcion->codigo_equipo;// Agregamos dinámicamente el grupo
            return $atleta;
        });

        return view('academia/inscripcionEvento', compact('eventos', 'academia', 'atletas', 'atletasInscripcion', 'bloquearSelectEventos'));
    }
}
