<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
