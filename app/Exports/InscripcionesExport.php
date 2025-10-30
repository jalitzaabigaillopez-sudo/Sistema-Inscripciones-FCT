<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\Usuario;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Barryvdh\DomPDF\Facade\Pdf;

class InscripcionesExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    protected $id_evento;
    protected $id_academia;

    public function __construct($id_evento = null, $id_academia = null)
    {
        $this->id_evento = $id_evento;
        $this->id_academia = $id_academia;
    }

    /** ===================================================================== ADMINISTRADOR ============================================================================ */
    public function collection()
    {
        $query = Inscripcion::with(['academia', 'atleta', 'evento', 'modalidad', 'subModalidad', 'categoria']);

        if ($this->id_evento) {
            $query->where('id_evento', $this->id_evento);
        }
        if ($this->id_academia) {
            $query->where('id_academia', $this->id_academia);
        }

        $inscripciones = $query->get();

        return new Collection($inscripciones->map(function ($i) {
            return [
                'ID inscripción' => $i->id_inscripcion,
                'Academia' => $i->academia->nombre ?? '—',
                'Atleta' => $i->atleta->nombre . " " . $i->atleta->primer_apellido . " " . $i->atleta->segundo_apellido ?? '—',
                'Evento' => $i->evento->nombre ?? '—',
                'Modalidad' => $i->modalidad->nombre ?? '—',
                'Submodalidad' => $i->subModalidad->nombre ?? '—',
                'Categoría' => ($i->categoria?->peso_min !== null && $i->categoria?->peso_max !== null)
                    ? "Más de {$i->categoria->peso_min} kg & No excede {$i->categoria->peso_max} kg"
                    : '—',
                'Fecha inscripción' => $i->fecha_inscripcion,
                'Estado' => $i->estado,
                'Peso' => $i->peso ?? '—',
                'Código de equipo' => $i->codigo_equipo ?? '—',
                'Rol' => $i->rol ?? '—',
            ];
        }));
    }

    public function headings(): array
    {
        return [
            'ID inscripción',
            'Academia',
            'Atleta',
            'Evento',
            'Modalidad',
            'Submodalidad',
            'Categoría',
            'Fecha inscripción',
            'Estado',
            'Peso',
            'Código de equipo',
            'Rol'
        ];
    }

    public function exportarInscripcionesEventoPdf($id_evento)
    {
        $inscripciones = Inscripcion::with([
            'academia',
            'atleta',
            'evento',
            'modalidad',
            'subModalidad',
            'categoria'
        ])->where('id_evento', $id_evento)->get();

        $datos = $inscripciones->map(function ($i) {
            return [
                'ID inscripción' => $i->id_inscripcion,
                'Academia' => $i->academia->nombre ?? '—',
                'Atleta' => $i->atleta ? $i->atleta->nombre . " " . $i->atleta->primer_apellido . " " . $i->atleta->segundo_apellido : '—',
                'Evento' => $i->evento->nombre ?? '—',
                'Modalidad' => $i->modalidad->nombre ?? '—',
                'Submodalidad' => $i->subModalidad->nombre ?? '—',
                'Categoría' => ($i->categoria?->peso_min !== null && $i->categoria?->peso_max !== null)
                    ? "Más de {$i->categoria->peso_min} kg & No excede {$i->categoria->peso_max} kg"
                    : '—',
                'Fecha inscripción' => $i->fecha_inscripcion,
                'Estado' => $i->estado,
                'Peso' => $i->peso ?? '—',
                'Código de equipo' => $i->codigo_equipo ?? '—',
                'Rol' => $i->rol ?? '—',
            ];
        });

        $datosAgrupados = $datos->groupBy('Academia');

        $pdf = Pdf::loadView('pdf.inscripciones', ['inscripciones' => $datosAgrupados]);
        return $pdf->download('inscripciones.pdf');
    }


    public function exportarInscripcionesAcademiaPdf($id_academia)
    {
        $inscripciones = Inscripcion::with([
            'academia',
            'atleta',
            'evento',
            'modalidad',
            'subModalidad',
            'categoria'
        ])->where('id_academia', $id_academia)->get();

        $datos = $inscripciones->map(function ($i) {
            return [
                'ID inscripción' => $i->id_inscripcion,
                'Academia' => $i->academia->nombre ?? '—',
                'Atleta' => $i->atleta ? $i->atleta->nombre . " " . $i->atleta->primer_apellido . " " . $i->atleta->segundo_apellido : '—',
                'Evento' => $i->evento->nombre ?? '—',
                'Modalidad' => $i->modalidad->nombre ?? '—',
                'Submodalidad' => $i->subModalidad->nombre ?? '—',
                'Categoría' => ($i->categoria?->peso_min !== null && $i->categoria?->peso_max !== null)
                    ? "Más de {$i->categoria->peso_min} kg & No excede {$i->categoria->peso_max} kg"
                    : '—',
                'Fecha inscripción' => $i->fecha_inscripcion,
                'Estado' => $i->estado,
                'Peso' => $i->peso ?? '—',
                'Código de equipo' => $i->codigo_equipo ?? '—',
                'Rol' => $i->rol ?? '—',
            ];
        });

        $datosAgrupados = $datos->groupBy('Academia');

        $pdf = Pdf::loadView('pdf.inscripciones', ['inscripciones' => $datosAgrupados]);
        return $pdf->download('inscripciones.pdf');
    }


    public function exportPdf()
    {
        $inscripciones = Inscripcion::with(['academia', 'atleta', 'evento', 'modalidad', 'subModalidad', 'categoria'])
            ->get();

        $datos = $inscripciones->map(function ($i) {
            return [
                'ID inscripción' => $i->id_inscripcion,
                'Academia' => $i->academia->nombre ?? '—',
                'Atleta' => $i->atleta ? $i->atleta->nombre . " " . $i->atleta->primer_apellido . " " . $i->atleta->segundo_apellido : '—',
                'Evento' => $i->evento->nombre ?? '—',
                'Modalidad' => $i->modalidad->nombre ?? '—',
                'Submodalidad' => $i->subModalidad->nombre ?? '—',
                'Categoría' => ($i->categoria?->peso_min !== null && $i->categoria?->peso_max !== null)
                    ? "Más de {$i->categoria->peso_min} kg & No excede {$i->categoria->peso_max} kg"
                    : '—',
                'Fecha inscripción' => $i->fecha_inscripcion,
                'Estado' => $i->estado,
                'Peso' => $i->peso ?? '—',
                'Código de equipo' => $i->codigo_equipo ?? '—',
                'Rol' => $i->rol ?? '—',
            ];
        });

        $datosAgrupados = $datos->groupBy('Academia');

        $pdf = Pdf::loadView('pdf.inscripciones', ['inscripciones' => $datosAgrupados]);
        return $pdf->download('inscripciones.pdf');
    }

    /** ===================================================================== ACADEMIA ============================================================================ */

    public function exportAcademiaPdf($id_academia)
    {
        $inscripciones = Inscripcion::with(['academia', 'atleta', 'evento', 'modalidad', 'subModalidad', 'categoria'])
            ->where('id_academia', $id_academia)
            ->get();

        $datos = $inscripciones->map(function ($i) {
            return [
                'ID inscripción' => $i->id_inscripcion,
                'Academia' => $i->academia->nombre ?? '—',
                'Atleta' => $i->atleta ? $i->atleta->nombre . " " . $i->atleta->primer_apellido . " " . $i->atleta->segundo_apellido : '—',
                'Evento' => $i->evento->nombre ?? '—',
                'Modalidad' => $i->modalidad->nombre ?? '—',
                'Submodalidad' => $i->subModalidad->nombre ?? '—',
                'Categoría' => ($i->categoria?->peso_min !== null && $i->categoria?->peso_max !== null)
                    ? "Más de {$i->categoria->peso_min} kg & No excede {$i->categoria->peso_max} kg"
                    : '—',
                'Fecha inscripción' => $i->fecha_inscripcion,
                'Estado' => $i->estado,
                'Peso' => $i->peso ?? '—',
                'Código de equipo' => $i->codigo_equipo ?? '—',
                'Rol' => $i->rol ?? '—',
            ];
        });

        $datosAgrupados = $datos->groupBy('Academia');

        $pdf = Pdf::loadView('pdf.inscripciones', ['inscripciones' => $datosAgrupados]);
        return $pdf->download('inscripciones.pdf');
    }

    public function exportEventoAcademiaPdf($id_evento)
    {


        $usuarioId = request()->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);
        $academia = $usuario->academia;
        $id_academia = $academia->id_academia ?? null;

        // Construimos la consulta base
        $query = Inscripcion::with(['academia', 'atleta', 'evento', 'modalidad', 'subModalidad', 'categoria'])
            ->where('id_academia', $id_academia);

        // Si se recibió un id_evento, filtramos también por él
        if (!is_null($id_evento)) {
            $query->where('id_evento', $id_evento);
        }

        // Ejecutamos la consulta
        $inscripciones = $query->get();

        // Transformamos los datos para el PDF
        $datos = $inscripciones->map(function ($i) {
            return [
                'ID inscripción' => $i->id_inscripcion,
                'Academia' => $i->academia->nombre ?? '—',
                'Atleta' => $i->atleta
                    ? "{$i->atleta->nombre} {$i->atleta->primer_apellido} {$i->atleta->segundo_apellido}"
                    : '—',
                'Evento' => $i->evento->nombre ?? '—',
                'Modalidad' => $i->modalidad->nombre ?? '—',
                'Submodalidad' => $i->subModalidad->nombre ?? '—',
                'Categoría' => ($i->categoria?->peso_min !== null && $i->categoria?->peso_max !== null)
                    ? "Más de {$i->categoria->peso_min} kg & No excede {$i->categoria->peso_max} kg"
                    : '—',
                'Fecha inscripción' => $i->fecha_inscripcion,
                'Estado' => $i->estado,
                'Peso' => $i->peso ?? '—',
                'Código de equipo' => $i->codigo_equipo ?? '—',
                'Rol' => $i->rol ?? '—',
            ];
        });

        $datosAgrupados = $datos->groupBy('Academia');

        $pdf = Pdf::loadView('pdf.inscripciones', ['inscripciones' => $datosAgrupados]);
        return $pdf->download('inscripciones.pdf');
    }
}