<?php

namespace App\Http\Controllers;

use App\Exports\AcademiasExport;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Inscripcion;
use App\Models\Evento;
use App\Services\SessionService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AtletasExport;
use App\Exports\InscripcionesExport;
use App\Helpers\RoleGate;
use App\Models\Academia;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function __construct(Request $request)
    {
        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }
    }

    /** ======================================== ADMINISTRADOR ======================================== */
    public function exportarAtletas(Request $request, $tipo)
    {
        $query = \App\Models\Atleta::with(['division', 'grado', 'academias'])
            ->join('academias', 'atletas.id_academia', '=', 'academias.id_academia')
            ->select('atletas.*'); // Evita duplicar columnas

        // Filtros con prefijos de tabla correctos
        if ($request->filled('tipo_identificacion')) {
            $query->where('atletas.tipo_identificacion', $request->tipo_identificacion);
        }
        if ($request->filled('sexo')) {
            $query->where('atletas.sexo', $request->sexo);
        }
        if ($request->filled('id_grado')) {
            $query->where('atletas.id_grado', $request->id_grado);
        }
        if ($request->filled('estado')) {
            $query->where('atletas.estado', $request->estado);
        }
        if ($request->filled('id_academia')) {
            $query->where('atletas.id_academia', $request->id_academia);
        }

        // Orden por academia + atleta
        $query->orderBy('academias.nombre', 'asc')
            ->orderBy('atletas.nombre', 'asc')
            ->orderBy('atletas.primer_apellido', 'asc');


        $atletas = $query->get();

        $datos = $atletas->map(function ($a) {
            return [
                // 'ID' => $a->id_atleta,
                'Tipo de ID' => $a->tipo_identificacion,
                'Identificación' => $a->identificacion,
                'Nombre completo' => trim("{$a->nombre} {$a->primer_apellido} {$a->segundo_apellido}"),
                'Sexo' => $a->sexo,
                'Fecha de nacimiento' => $a->fecha_nacimiento
                    ? \Carbon\Carbon::parse($a->fecha_nacimiento)->format('d-m-Y')
                    : '',
                'División' => $a->division->division ?? '',
                'Grado' => $a->grado->nombre ?? '',
                'Academia' => $a->academias->nombre ?? '',
                // 'Estado' => $a->estado,
            ];
        });

        if ($tipo === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\ArrayExport($datos->toArray(), array_keys($datos->first() ?? [])),
                'Registros_de_Atletas.xlsx'
            );
        }

        if ($tipo === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.atletas', ['atletas' => $datos])
                ->setPaper('a4', 'landscape');
            return $pdf->download('Registros_de_Atletas.pdf');
        }

        abort(404);
    }

    public function exportarAtletasExcel(Request $request)
    {
        $filtros = $request->only(['tipo_identificacion', 'sexo', 'id_grado', 'estado', 'id_academia']);
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AtletasExport($filtros),
            'atletas_filtrados.xlsx'
        );
    }

    // ACADEMIAS
    public function exportarAcademiasExcel()
    {
        return Excel::download(new AcademiasExport, 'Registros_de_Academias.xlsx');
    }

    public function exportarAcademiasPdf()
    {
        $academias = Academia::with(['usuario', 'distrito.canton.provincia'])->get();

        $datos = $academias->map(function ($a) {
            $ubicacion = $a->distrito
                ? "{$a->distrito->canton->provincia->nombre}, {$a->distrito->canton->nombre}, {$a->distrito->nombre}"
                : 'Sin ubicación';

            return [
                'Nombre' => $a->nombre,
                'Profesor Encargado' => $a->profesor_encargado,
                'Usuario' => $a->usuario->nombre_completo ?? '—',
                'Correo' => $a->correo,
                'Teléfono' => $a->telefono,
                'Ubicación' => $ubicacion,
                'Dirección' => $a->direccion,
                'Estado' => ucfirst($a->estado),
            ];
        });

        $pdf = Pdf::loadView('pdf.academias', ['academias' => $datos])
            ->setPaper('a4', 'landscape');

        return $pdf->download('Registros_de_Academias.pdf');
    }

    public function exportarInscripcionesExcel()
    {
        return Excel::download(new InscripcionesExport, 'inscripciones.xlsx');
    }

    public function prueba1()
    {
        return Excel::download(new InscripcionesExport, 'inscripciones.xlsx');
    }

    public function exportarInscripcionesPdf()
    {
        $export = new InscripcionesExport();
        return $export->exportPdf();
    }

    //EXCEL
    public function exportarInscripcionesEventoExcel($id_evento)
    {
        return Excel::download(new InscripcionesExport($id_evento, null), "inscripciones_eventos_{$id_evento}.xlsx");
    }

    public function exportarInscripcionesAcademiaExcel($id_academia)
    {
        return Excel::download(new InscripcionesExport(null, $id_academia), "inscripciones_academia_{$id_academia}.xlsx");
    }


    /** ======================================== ACADEMIA ======================================== */
    //PDF
    public function exportarInscripcionesEventoPdf($id_evento)
    {
        $export = new InscripcionesExport();
        return $export->exportarInscripcionesEventoPdf($id_evento);
    }

    public function exportarInscripcionesAcademiaPdf($id_academia)
    {
        $export = new InscripcionesExport();
        return $export->exportarInscripcionesAcademiaPdf($id_academia);
    }

    public function exportarAtletasAcadamiaPdf($id_academia)
    {
        $export = new AtletasExport();
        return $export->exportAcademiaPdf($id_academia);
    }

    public function exportarInscripcionesAcademiasPdf($id_academia)//@audit aqui
    {
        $export = new InscripcionesExport();
        return $export->exportAcademiaPdf($id_academia);
    }

    public function exportarInscripcionesEventoAcademiasPdf(Request $request, $id_evento)
    {
        $export = new InscripcionesExport();
        return $export->exportEventoAcademiaPdf($id_evento);
    }

    public function vistaReportesGeneralesAcademia(Request $request)
    {
        // Solo academias
        RoleGate::requireAcademia();
        
        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);
        $academia = $usuario->academia;
        $id_academia = $academia->id_academia ?? null;

        // OBTENER EN QUE EVENTOS ESTOY INSCRITOS
        $eventosIds = Inscripcion::where('id_academia', $academia->id_academia)
            ->distinct()                // elimina duplicados
            ->pluck('id_evento');

        $eventos = Evento::whereIn('id_evento', $eventosIds)
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return view("academia/reportes/reportesGenerales", compact('academia', 'eventos'));
    }
}
