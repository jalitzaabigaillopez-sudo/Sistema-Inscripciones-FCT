<?php

namespace App\Exports;

use App\Models\Academia;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class AcademiasExport implements FromCollection, WithHeadings, WithEvents, WithTitle
{
    public function collection()
    {
        $academias = Academia::with(['usuario', 'distrito.canton.provincia'])->get();

        return new Collection($academias->map(function ($a) {
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
        }));
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Profesor Encargado',
            'Usuario',
            'Correo',
            'Teléfono',
            'Ubicación',
            'Dirección',
            'Estado'
        ];
    }

    public function title(): string
    {
        return 'Listado de Academias';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Encabezado principal (sin formato de color, simple y centrado)
                $sheet->insertNewRowBefore(1, 2); // Inserta 2 filas al inicio
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');

                $sheet->setCellValue('A1', 'Federación Costarricense de Taekwondo');
                $fecha = Carbon::now('America/Costa_Rica')->format('d/m/Y H:i');
                $sheet->setCellValue('A2', 'Listado de Academias — Generado el ' . $fecha);

                // Centrar y aplicar fuente sencilla
                $sheet->getStyle('A1:A2')->getFont()->setName('Arial')->setBold(true)->setSize(12);
                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

                // Auto-ajustar columnas
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
