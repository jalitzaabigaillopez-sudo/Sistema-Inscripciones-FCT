<?php

namespace App\Http\Controllers;

use App\Helpers\RoleGate;
use App\Models\Academia;
use App\Models\Atleta;
use App\Models\Evento;
use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\ModalidadEvento;
use App\Models\Usuario;
use Carbon\Carbon;
use App\Services\SessionService;
use Illuminate\Support\Facades\Schema;

class InscripcionController extends Controller
{
    public function __construct(Request $request)
    {
        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }
    }

    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    $eventos = Evento::all()->keyBy('id_evento');
    $academias = Academia::all()->keyBy('id_academia');

    // 1) resumen por atleta (1 fila por atleta-evento-academia-estado)
    $resumenAtletas = Inscripcion::query()
        ->where('rol', 'atleta')
        ->whereNotNull('id_modalidad')
        ->select('id_evento', 'id_academia', 'id_atleta', 'estado')
        ->selectRaw('COUNT(DISTINCT id_modalidad) as modalidades')
        ->selectRaw("MAX(CASE WHEN tipo_inscripcion = 'tardia' THEN 1 ELSE 0 END) as es_tardia")
        ->selectRaw('COUNT(*) as inscripciones_atleta')
        ->groupBy('id_evento', 'id_academia', 'id_atleta', 'estado')
        ->get();

    // 2) agrupar por evento + academia + estado (para tabla admin)
    $inscripciones = $resumenAtletas
        ->groupBy(fn ($r) => $r->id_evento . '-' . $r->id_academia . '-' . $r->estado)
        ->map(function ($grupo) use ($eventos, $academias) {

            $first = $grupo->first();
            $evento = $eventos->get($first->id_evento);
            $academia = $academias->get($first->id_academia);

            $totalMonto = 0.0;

            foreach ($grupo as $row) {
                $esDosOMas = (int)$row->modalidades >= 2;
                $esTardia = (int)$row->es_tardia === 1; // por atleta (no por hoy)

                if ($esTardia) {
                    $totalMonto += $esDosOMas
                        ? (float)($evento->costo_tardia_2 ?? 0)
                        : (float)($evento->costo_tardia_1 ?? 0);
                } else {
                    $totalMonto += $esDosOMas
                        ? (float)($evento->costo_temprana_2 ?? 0)
                        : (float)($evento->costo_temprana_1 ?? 0);
                }
            }

            return (object)[
                'id_evento' => $first->id_evento,
                'id_academia' => $first->id_academia,
                'estado' => $first->estado,

                // atletas únicos en ese evento + academia + estado
                'total_atletas' => $grupo->sum('inscripciones_atleta'),

                // monto total por atleta (1 vez), segun modalidad(es) y tipo (temprana/tardia) del atleta
                'total_monto' => $totalMonto,

                'evento' => $evento,
                'academia' => $academia,
            ];
        })
        ->values();

    return view('catalogos.inscripciones.index', [
        'inscripciones' => $inscripciones,
        'academias' => $academias->values(),
        'eventos' => $eventos->values(),
    ]);
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
        // Solo academias
        RoleGate::requireAcademia();

        try {

            $atleta = $request->input('atleta');

            // Determinar tipo de inscripción (normal o tardía) =============================
            $evento = Evento::findOrFail($atleta['id_evento']);

            $hoy = Carbon::today();

            $tipoInscripcion = 'normal';
            if ($evento->fecha_final_inscripcion && $evento->fecha_final_inscripcion_tardia) {
                $fechaFinalNormal = Carbon::parse($evento->fecha_final_inscripcion);
                $fechaFinalTardia = Carbon::parse($evento->fecha_final_inscripcion_tardia);

                if ($hoy->gt($fechaFinalNormal) && $hoy->lte($fechaFinalTardia)) {
                    $tipoInscripcion = 'tardia';
                } elseif ($hoy->gt($fechaFinalTardia)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El periodo de inscripción (incluyendo tardía) ya ha finalizado.'
                    ], 400);
                }
            } elseif ($evento->fecha_final_inscripcion && $hoy->gt(Carbon::parse($evento->fecha_final_inscripcion))) {
                return response()->json([
                    'success' => false,
                    'message' => 'El periodo de inscripción ha finalizado.'
                ], 400);
            }
            //===============================================================================

            $inscripcion = Inscripcion::create([
                'id_academia' => $atleta['id_academia'],
                'id_atleta' => $atleta['id_atleta'],
                'id_evento' => $atleta['id_evento'],
                'id_modalidad' => $atleta['id_modalidad'],
                'id_subModalidad' => $atleta['id_subModalidad'],
                'id_categoria' => ($atleta['id_categoria'] == 0) ? null : $atleta['id_categoria'],// en el caso de pommsae y freestyle llega en 0
                'fecha_inscripcion' => now(),
                'tipo_inscripcion' => $tipoInscripcion,
                'estado' => 'inactiva',
                'peso' => $atleta['peso'],
                'codigo_equipo' => $atleta['grupo'],
                'peso' => $atleta['peso'],
                'codigo_equipo' => $atleta['grupo'],
                'rol' => $atleta['rol'],
            ]);
            return response()->json([
                'success' => true,
                'message' => "Atleta inscrito correctamente como inscripción {$tipoInscripcion}.",
                'data' => $inscripcion
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al registrar la inscripción: ' . $e->getMessage()
            ], 500);
        }

    }

    public function modificarInscripcionAtleta(Request $request)
    {
        // Solo academias
        RoleGate::requireAdminOrAcademia();

        $atletasModificar = $request->input('atletasModificar', []);
        $datosNuevos = $request->input('datosNuevos', []); // Datos nuevos para actualizar

        foreach ($atletasModificar as $atleta) {
            // Buscar la inscripción que coincida con todos los datos "actuales"
            $inscripcion = Inscripcion::where('id_atleta', $atleta['id_atleta'])
                ->where('id_academia', $atleta['id_academia'])
                ->where('id_evento', $atleta['id_evento'])
                ->where('id_modalidad', $atleta['id_modalidad'])
                ->where('id_subModalidad', $atleta['id_subModalidad'])
                // ->where('id_categoria', $atleta['id_categoria'])
                ->where('codigo_equipo', $atleta['grupo'])
                ->where('rol', $atleta['rol'])
                ->first();

            if ($inscripcion) {
                // Reemplazar con los datos nuevos
                $inscripcion->id_atleta = $datosNuevos['id_atleta'];
                $inscripcion->id_evento = $datosNuevos['id_evento'];
                $inscripcion->id_academia = $datosNuevos['id_academia'];

                $inscripcion->id_modalidad = $datosNuevos['id_modalidad'];
                $inscripcion->id_subModalidad = $datosNuevos['id_subModalidad'];

                $inscripcion->id_categoria = ($datosNuevos['id_categoria'] == 0) ? null : $datosNuevos['id_categoria'];
                $inscripcion->codigo_equipo = $datosNuevos['grupo'];
                $inscripcion->rol = $datosNuevos['rol'];
                $inscripcion->estado = 'inactiva';
                $inscripcion->peso = $datosNuevos['peso'];
                $inscripcion->fecha_inscripcion = now();

                $inscripcion->save();
            }
        }

        return response()->json([
            'debug' => $request->all()
        ]);
    }

    public function eliminarInscripcionAtleta(Request $request)
    {
        // Solo academias
        RoleGate::requireAdminOrAcademia();

        $response = false;
        $atletasModificar = $request->input('atletasModificar', []);

        foreach ($atletasModificar as $atleta) {
            // Buscar la inscripción que coincida con todos los datos "actuales"
            $inscripcion = Inscripcion::where('id_atleta', $atleta['id_atleta'])
                ->where('id_academia', $atleta['id_academia'])
                ->where('id_evento', $atleta['id_evento'])
                ->where('id_modalidad', $atleta['id_modalidad'])
                ->where('id_subModalidad', $atleta['id_subModalidad'])
                // ->where('id_categoria', $atleta['id_categoria'])
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
        // Solo academias
        RoleGate::requireAcademia();

        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);
        $academia = $usuario->academia;

        // OBTENER EN QUE EVENTOS ESTOY INSCRITOS
        $eventosIds = Inscripcion::where('id_academia', $academia->id_academia)
            ->distinct()                // elimina duplicados
            ->pluck('id_evento');

        $bloquearSelectEventos = false;
        $hoy = now()->toDateString();

        $eventos = Evento::with('tipoEvento')
            ->where('estado', 'activo')
            ->where(function ($query) use ($hoy) {
                $query->where(function ($q) use ($hoy) {
                    $q->whereNotNull('fecha_inicio_inscripcion')
                        ->whereNotNull('fecha_final_inscripcion')
                        ->where('fecha_inicio_inscripcion', '<=', $hoy)
                        ->where('fecha_final_inscripcion', '>=', $hoy);
                })
                    ->orWhere(function ($q) use ($hoy) {
                        $q->whereNotNull('fecha_final_inscripcion_tardia')
                            ->where('fecha_final_inscripcion_tardia', '>=', $hoy);
                    });
            })
            ->orderBy('fecha_inicio_inscripcion', 'asc')
            ->get();

        $academia = $usuario->academia;
        $atletas = $academia->atletas()->with(['grados', 'division'])->get();
        // dd($eventos);
        return view('academia/inscripcionEvento', compact('eventos', 'academia', 'atletas', 'eventosIds', 'bloquearSelectEventos'));
    }

    /**
     * Dirige a la vista de "misInscripciones" y lleva un grupo de datos basicos para mostrar
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function vistaMisInscripcionesAcademia(Request $request)
    {
            // Solo academias
            RoleGate::requireAcademia();

            $usuarioId = $request->session()->get('usuario');
            $usuario = Usuario::find($usuarioId);
            $academia = $usuario->academia;

            // Agrupar por evento
            $inscripcionesPorEvento = Inscripcion::with(['evento'])
                ->where('id_academia', $academia->id_academia)
                ->get()
                ->groupBy('id_evento');

            $inscripcionesAgrupadas = [];

            foreach ($inscripcionesPorEvento as $id_evento => $grupo) {

                $primera = $grupo->first();
                $evento  = $primera->evento;

                // Resumen por atleta (modalidades + si fue tardía)
                $resumenAtletas = Inscripcion::query()
                    ->where('id_academia', $academia->id_academia)
                    ->where('id_evento', $id_evento)
                    ->where('rol', 'atleta')
                    ->whereNotNull('id_modalidad')
                    ->select('id_atleta')
                    ->selectRaw('COUNT(DISTINCT id_modalidad) as modalidades')
                    ->selectRaw("MAX(CASE WHEN tipo_inscripcion = 'tardia' THEN 1 ELSE 0 END) as es_tardia")
                    ->selectRaw('COUNT(*) as inscripciones_atleta')
                    ->groupBy('id_atleta')
                    ->get();

                // CONTAR UN ATLETA AUNQUE ESTE INSCRITO EN VARIAS MODALIDADES
                $total_atletas = $resumenAtletas->count(); // atletas únicos
                
                $cantidad_inscritos = Inscripcion::where('id_academia', $academia->id_academia)
                ->where('id_evento', $id_evento)
                ->where('rol', 'atleta')
                ->whereNotNull('id_modalidad')
                ->count();

                $totalMonto = 0.0;

                foreach ($resumenAtletas as $row) {
                    $esDosOMas = (int)$row->modalidades >= 2;
                    $esTardia  = (int)$row->es_tardia === 1;

                    if ($esTardia) {
                        $totalMonto += $esDosOMas
                            ? (float)($evento->costo_tardia_2 ?? 0)
                            : (float)($evento->costo_tardia_1 ?? 0);
                    } else {
                        $totalMonto += $esDosOMas
                            ? (float)($evento->costo_temprana_2 ?? 0)
                            : (float)($evento->costo_temprana_1 ?? 0);
                    }
                }

                $inscripcionesAgrupadas[] = (object)[
                    'evento' => $evento,
                    'cantidad_inscritos' => $cantidad_inscritos,
                    'total_monto' => $totalMonto,
                    'estado' => $primera->estado,
                    'tipo_inscripcion' => $primera->tipo_inscripcion, // si quieres "mixto" te lo ajusto
                ];
            }

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
        // Solo academias
        RoleGate::requireAcademia();

        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);
        $academia = $usuario->academia;

        $eventosIds = Inscripcion::where('id_academia', $academia->id_academia)
            ->distinct()
            ->pluck('id_evento');

        $eventos = Evento::where('id_evento', $id_evento)->get();
        $bloquearSelectEventos = true;

        $academia = $usuario->academia;
        $atletas = $academia->atletas;

        $inscripciones = Inscripcion::with(['atleta', 'grado', 'division', 'modalidad', 'subModalidad', 'categoria', 'evento'])
            ->where('id_evento', $id_evento)
            ->where('id_academia', $academia->id_academia)
            ->get();

        $atletasInscripcion = $inscripciones->map(function ($inscripcion) {

            $atleta = Atleta::find($inscripcion->id_atleta);
            $grado = $atleta->grado;
            $division = $atleta->division;

            $atleta = $inscripcion->atleta->replicate(); // crea una copia del atleta original

            $atleta->id_atleta = $inscripcion->id_atleta;
            $atleta->id_inscripcion = $inscripcion->id_inscripcion;
            $atleta->rol = $inscripcion->rol;
            $atleta->grupo = $inscripcion->codigo_equipo;
            $atleta->peso = $inscripcion->peso;
            $atleta->grado = $grado;
            $atleta->division = $division;
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

        return view('academia/inscripcionEvento', compact('eventos', 'academia', 'atletas', 'modalidades', 'atletasInscripcion', 'bloquearSelectEventos', 'eventosIds'));
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










    //=?=?=?=?=?=?=?=?=?=?=?=?=??=?=?=?=?=?=?=?=?=?=?=?=?=?=?=?=?=?=?=?=??=?==?=?=?=?=?=?=??=?=?=?=?=?=?=?=?=??=?=?=?=
/*
   ╔════════════════════════════════════════════════════════════════╗

   ║                         ADMINISTRADOR                          ║ 

   ╚════════════════════════════════════════════════════════════════╝
*/

    public function administradorEditarInscripcion(Request $request, $id_evento, $id_academia)
    {
         // Solo admin
        RoleGate::requireAdmin();
        
        $eventos = Evento::where('id_evento', $id_evento)->get();
        $bloquearSelectEventos = true;

        $academia = Academia::find($id_academia);
        $atletas = $academia->atletas()->with('grado')->get();

        $inscripciones = Inscripcion::with(['atleta.grado', 'modalidad', 'subModalidad', 'categoria', 'evento'])
            ->where('id_evento', $id_evento)
            ->where('id_academia', $academia->id_academia)
            ->get();

        $atletasInscripcion = $inscripciones->map(function ($inscripcion) {
            $atleta = $inscripcion->atleta->replicate(); // crea una copia del atleta original

            $atleta->id_atleta = $inscripcion->id_atleta;
            $atleta->id_inscripcion = $inscripcion->id_inscripcion;
            $atleta->rol = $inscripcion->rol;
            $atleta->grupo = $inscripcion->codigo_equipo;
            $atleta->peso = $inscripcion->peso;
            
            //  grado REAL del atleta (puede ser null, y JS ya lo maneja)
            $atleta->id_grado = $inscripcion->atleta->id_grado;
            $atleta->grado = $inscripcion->atleta->grado;
            
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

        $id_academia = $academia->id_academia;

        return view('admin/inscripcionEvento', compact('eventos', 'academia', 'atletas', 'modalidades', 'atletasInscripcion', 'bloquearSelectEventos', 'id_academia'));
    }

    public function administradorEliminarInscripcion($id_evento, $id_academia)
    {
         // Solo admin
        RoleGate::requireAdmin();

        $inscripcion = Inscripcion::where('id_evento', $id_evento)
        ->where('id_academia', $id_academia);

        if ($inscripcion) {
            $inscripcion->delete();
            return redirect()->back()->with('success', 'Inscripción eliminada.');
        }

        return response()->json(['success' => true, 'msg' => 'No se pudo eliminar esta inscripcion']);
    }



    public function AdministradorInscribirAtleta(Request $request)
    {
        // Solo admin
        RoleGate::requireAdmin();

        $atleta = $request->input('atleta');

        $inscripcion = Inscripcion::create([
            'id_academia' => $atleta['id_academia'],
            'id_atleta' => $atleta['id_atleta'],
            'id_evento' => $atleta['id_evento'],
            'id_modalidad' => $atleta['id_modalidad'],
            'id_subModalidad' => $atleta['id_subModalidad'],
            'id_categoria' => ($atleta['id_categoria'] == 0) ? null : $atleta['id_categoria'],// en el caso de pommsae y freestyle llega en 0
            'fecha_inscripcion' => now(),
            'tipo_inscripcion' => 'normal', //????
            'estado' => 'activa',
            'peso' => $atleta['peso'],
            'codigo_equipo' => $atleta['grupo'],
            'peso' => $atleta['peso'],
            'codigo_equipo' => $atleta['grupo'],
            'rol' => $atleta['rol'],
        ]);
        return response()->json($inscripcion, 201);
    }
}
