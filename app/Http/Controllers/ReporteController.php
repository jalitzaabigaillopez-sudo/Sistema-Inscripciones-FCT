<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Inscripcion;
use App\Models\Evento;
use App\Services\SessionService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AtletasExport;
use App\Exports\InscripcionesExport;

class ReporteController extends Controller
{
    public function __construct(Request $request)
    {
        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }
    }

    /** ======================================== ADMINISTRADOR ======================================== */
    public function exportarAtletasExcel()
    {
        return Excel::download(new AtletasExport, 'atletas.xlsx');
    }

    public function exportarAtletasPdf()
    {
        $export = new AtletasExport();
        return $export->exportPdf();
    }

    public function exportarInscripcionesExcel()
    {
        return Excel::download(new InscripcionesExport, 'inscripciones.xlsx');
    }
    public function exportarInscripcionesPdf()
    {
        $export = new InscripcionesExport();
        return $export->exportPdf();
    }

    /** ======================================== ACADEMIA ======================================== */
    public function exportarAtletasAcadamiaPdf($id_academia)
    {
        $export = new AtletasExport();
        return $export->exportAcademiaPdf($id_academia);
    }

    public function exportarInscripcionesAcademiasPdf($id_academia)
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
