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
            'peso' => $atleta['peso'],
            'codigo_equipo' => $atleta['grupo'],
        ]);
        return response()->json($inscripcion, 201);
    }

    public function modificarInscripcionAtleta(Request $request)
    {
        $atletasModificar = $request->input('atletasModificar', []);
        $datosNuevos = $request->input('datosNuevos', []); // Datos nuevos para actualizar

        $resultado = [];

        foreach ($atletasModificar as $atleta) {
            // Buscar la inscripción que coincida con todos los datos "actuales"
            $inscripcion = Inscripcion::where('id_atleta', $atleta['id_atleta'])
                ->where('id_academia', $atleta['id_academia'])
                ->where('id_evento', $atleta['id_evento'])
                ->where('id_modalidad', $atleta['id_modalidad'])
                ->where('id_subModalidad', $atleta['id_subModalidad'])
                ->where('id_categoria', $atleta['id_categoria'])
                ->where('codigo_equipo', $atleta['grupo'])
                ->first();

            if ($inscripcion) {
                // Reemplazar con los datos nuevos
                $inscripcion->id_atleta = $datosNuevos['id_atleta'] ?? $inscripcion->id_atleta;
                $inscripcion->id_evento = $datosNuevos['id_evento'] ?? $inscripcion->id_evento;
                $inscripcion->id_academia = $datosNuevos['id_academia'] ?? $inscripcion->id_academia;

                $inscripcion->id_modalidad = $datosNuevos['id_modalidad'] ?? $inscripcion->id_modalidad;
                $inscripcion->id_subModalidad = $datosNuevos['id_subModalidad'] ?? $inscripcion->id_subModalidad;
                $inscripcion->id_categoria = $datosNuevos['id_categoria'] ?? $inscripcion->id_categoria;
                $inscripcion->codigo_equipo = $datosNuevos['grupo'] ?? $inscripcion->codigo_equipo;
                $inscripcion->estado = 'inactiva';
                $inscripcion->peso = $datosNuevos['peso'] ?? $inscripcion->peso;
                $inscripcion->fecha_inscripcion = now();

                $inscripcion->save();

                $resultado[] = [
                    'id_atleta' => $atleta['id_atleta'],
                    'modificado' => true,
                    'inscripcion' => $inscripcion
                ];
            } else {
                $resultado[] = [
                    'id_atleta' => $atleta['id_atleta'],
                    'modificado' => false,
                    'mensaje' => 'No se encontró inscripción que coincida exactamente'
                ];
            }
        }

        return response()->json($resultado);
    }

    public function eliminarInscripcionAtleta(Request $request)
    {
        $response = false;
        $atletasModificar = $request->input('atletasModificar', []);

        foreach ($atletasModificar as $atleta) {
            // Buscar la inscripción que coincida con todos los datos "actuales"
            $inscripcion = Inscripcion::where('id_atleta', $atleta['id_atleta'])
                ->where('id_academia', $atleta['id_academia'])
                ->where('id_evento', $atleta['id_evento'])
                ->where('id_modalidad', $atleta['id_modalidad'])
                ->where('id_subModalidad', $atleta['id_subModalidad'])
                ->where('id_categoria', $atleta['id_categoria'])
                ->where('codigo_equipo', $atleta['grupo'])
                ->first();

            if ($inscripcion) {
                $inscripcion->delete();
                $response = true;
            } else {
                $response = false;
            }
        }
        return response()->json($response);
    }

    //====================================================================================================================================
    /**
     * Dirige a la vista de "inscripcionEvento" y lleva los datos necesarios
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
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

    /**
     * Dirige a la vista de "misInscripciones" y lleva un grupo de datos basicos para mostrar
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
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

        // dd($inscripcionesAgrupadas);
        return view('academia/misInscripciones', compact('inscripcionesAgrupadas', 'academia'));
    }

    /**
     * Carga la vista de "inscripcionEvento" pero desde otra ruta para usarla como editor
     * @param \Illuminate\Http\Request $request
     * @param mixed $id_evento
     * @return \Illuminate\Contracts\View\View
     */
    public function editarInscripcion(Request $request, $id_evento)
    {
        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);

        $eventos = Evento::where('id_evento', $id_evento)->get();
        $bloquearSelectEventos = true;

        $academia = $usuario->academia;
        $atletas = $academia->atletas;

        $inscripciones = Inscripcion::with(['atleta', 'modalidad', 'subModalidad', 'categoria', 'evento'])
            ->where('id_evento', $id_evento)
            ->where('id_academia', $academia->id_academia)
            ->get();

        $atletasInscripcion = $inscripciones->map(function ($inscripcion) {
            $atleta = $inscripcion->atleta;

            // todos los datos
            $atleta->grupo = $inscripcion->codigo_equipo;
            $atleta->peso = $inscripcion->peso;
            $atleta->modalidad = $inscripcion->modalidad;
            $atleta->subModalidad = $inscripcion->subModalidad;
            $atleta->categoria = $inscripcion->categoria;
            $atleta->evento = $inscripcion->evento;

            $atleta->id_division = $inscripcion->categoria->id_division ?? null;

            return $atleta;
        });

        // MODALIDADES - SUBMODALIDADE Y CATEGORIAS
        $evento = Evento::find($id_evento);
        $modalidades = $evento->modalidades;

        return view('academia/inscripcionEvento', compact('eventos', 'academia', 'atletas', 'modalidades', 'atletasInscripcion', 'bloquearSelectEventos'));
    }

    public function eliminarInscripcion(Request $request)
    {
        $id_academia = $request->input('id_academia');
        $id_evento = $request->input('id_evento');

        $inscripcion = Inscripcion::where('id_evento', $id_evento)->where('id_academia', $id_academia);

        if ($inscripcion) {
            $inscripcion->delete();
            return response()->json(['success' => true, 'msg' => 'Inscripción eliminada']);
        }

        return response()->json(['success' => true, 'msg' => 'No se pudo eliminar esta inscripcion']);
    }

    public function confirmarInscripcion(Request $request)
    {
        $id_academia = $request->input('id_academia');
        $id_evento = $request->input('id_evento');

        $datosActualizar = [
            'estado' => 'activa'
        ];

        $inscripcion = Inscripcion::where('id_evento', $id_evento)->where('id_academia', $id_academia);

        if ($inscripcion) {
            $inscripcion->update($datosActualizar);
            return response()->json(['success' => true, 'msg' => 'Inscripción actualizada']);
        }

        return response()->json(['success' => false, 'msg' => 'No se encontró inscripción']);
    }
}
