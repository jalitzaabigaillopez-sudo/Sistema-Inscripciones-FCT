<?php

namespace App\Exports;

use App\Models\Atleta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Barryvdh\DomPDF\Facade\Pdf;

class AtletasExport implements FromCollection, WithHeadings
{

    /** ===================================================================== ADMINISTRADOR ============================================================================ */

    public function collection()
    {
        $atletas = Atleta::with('academias', 'division', 'grado')->get();

        $datos = $atletas->map(function ($a) {
            return [
                'ID' => $a->id_atleta,
                'Tipo de identificacion' => $a->tipo_identificacion,
                'Identificacion' => $a->identificacion,
                'Primer apellido' => $a->primer_apellido,
                'Segundo Apellido' => $a->segundo_apellido,
                'Nombre' => $a->nombre,
                'Sexo' => $a->sexo,
                'Fecha de nacimiento' => $a->fecha_nacimiento,
                'Division' => $a->division->division,
                'Grado' => $a->grado->nombre,
                'Academia' => $a->academias->nombre,
                'Estado' => $a->estado,
            ];
        });

        return new Collection($datos);
    }

    public function headings(): array
    {
        return ['ID', 'Tipo de identificacion', 'Identificacion', 'Primer apellido', 'Segundo Apellido', 'Nombre', 'Sexo', 'Fecha de nacimiento', 'Division', 'Grado', 'Academia', 'Estado'];
    }

    public function exportPdf()
    {
        $atletas = Atleta::with('academias', 'division', 'grado')->get();

        $datos = $atletas->map(function ($a) {
            return [
                'ID' => $a->id_atleta,
                'Tipo de identificacion' => $a->tipo_identificacion,
                'Identificacion' => $a->identificacion,
                'Primer apellido' => $a->primer_apellido,
                'Segundo Apellido' => $a->segundo_apellido,
                'Nombre' => $a->nombre,
                'Sexo' => $a->sexo,
                'Fecha de nacimiento' => $a->fecha_nacimiento,
                'Division' => $a->division->division,
                'Grado' => $a->grado->nombre,
                'Academia' => $a->academias->nombre,
                'Estado' => $a->estado,
            ];
        });

        $pdf = Pdf::loadView('pdf.atletas', ['atletas' => $datos]);

        return $pdf->download('atletas.pdf');
    }

    /** ===================================================================== ACADEMIA ============================================================================ */

    public function exportAcademiaPdf($id_academia)
    {
        $atletas = Atleta::with(['academias', 'division', 'grado'])
        ->where('id_academia', $id_academia) 
        ->get();

        $datos = $atletas->map(function ($a) {
            return [
                'ID' => $a->id_atleta,
                'Tipo de identificacion' => $a->tipo_identificacion,
                'Identificacion' => $a->identificacion,
                'Primer apellido' => $a->primer_apellido,
                'Segundo Apellido' => $a->segundo_apellido,
                'Nombre' => $a->nombre,
                'Sexo' => $a->sexo,
                'Fecha de nacimiento' => $a->fecha_nacimiento,
                'Division' => $a->division->division,
                'Grado' => $a->grado->nombre,
                'Academia' => $a->academias->nombre,
                'Estado' => $a->estado,
            ];
        });

        $pdf = Pdf::loadView('pdf.atletas', ['atletas' => $datos]);

        return $pdf->download('atletas.pdf');
    }
}