<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ArrayExport implements FromArray, WithHeadings, WithEvents
{
    protected $data;
    protected $headings;

    public function __construct(array $data, array $headings)
    {
        $this->data = $data;
        $this->headings = $headings;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Agregar espacio para encabezado
                $sheet->insertNewRowBefore(1, 2);
                $sheet->mergeCells('A1:' . $sheet->getHighestColumn() . '1');
                $sheet->mergeCells('A2:' . $sheet->getHighestColumn() . '2');

                // Fecha local (Costa Rica)
                $fecha = Carbon::now('America/Costa_Rica')->format('d/m/Y H:i');

                // Encabezado institucional
                $sheet->setCellValue('A1', 'Federación Costarricense de Taekwondo');
                $sheet->setCellValue('A2', 'Listado de Atletas — Generado el ' . $fecha);

                // Fuente Arial, negrita, centrado
                $sheet->getStyle('A1:A2')->getFont()
                    ->setName('Arial')
                    ->setBold(true)
                    ->setSize(12);
                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

                // Ancho automático para todas las columnas
                foreach (range('A', $sheet->getHighestColumn()) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
