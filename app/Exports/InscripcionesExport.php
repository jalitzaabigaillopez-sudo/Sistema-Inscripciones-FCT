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

    public function startCell(): string
    {
        // Si hay academia o evento, dejamos la primera fila para los encabezados
        return ($this->id_academia || $this->id_evento) ? 'A2' : 'A1';
    }

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

        $prioridadTipo = [
            'entrenador' => 1,
            'asistente' => 2,
            'atleta' => 3,
        ];

        $prioridadModalidad = [
            'combate' => 1,
            'poomsae' => 2,
            'freestyle' => 3,
        ];

        $datos = $inscripciones->map(function ($i) {
            $fila = [
                'ID inscripción' => $i->id_inscripcion,
                'Atleta' => $i->atleta
                    ? "{$i->atleta->nombre} {$i->atleta->primer_apellido} {$i->atleta->segundo_apellido}"
                    : '—',
                'Modalidad' => ucfirst(strtolower($i->modalidad->nombre ?? '—')),
                'Submodalidad' => ucfirst(strtolower($i->subModalidad->nombre ?? '—')),
                'Categoría' => ($i->categoria?->peso_min !== null && $i->categoria?->peso_max !== null)
                    ? "Más de {$i->categoria->peso_min} kg y no excede {$i->categoria->peso_max} kg"
                    : '—',
                'Fecha inscripción' => $i->fecha_inscripcion,
                'Estado' => ucfirst(strtolower($i->estado ?? '—')),
                'Peso' => $i->peso ?? '—',
                'Código de equipo' => $i->codigo_equipo ?? '—',
                'Rol' => ucfirst(strtolower($i->rol ?? '—')),
            ];

            // Solo incluir "Evento" si no hay filtro de id_evento
            if (!$this->id_evento) {
                $fila = array_slice($fila, 0, 2, true)
                    + ['Evento' => $i->evento->nombre ?? '—']
                    + array_slice($fila, 2, null, true);
            }

            return $fila;
        });

        $datosOrdenados = $datos->sort(function ($a, $b) use ($prioridadTipo, $prioridadModalidad) {
            $rolA = strtolower($a['Rol']);
            $rolB = strtolower($b['Rol']);
            $valorTipoA = $prioridadTipo[$rolA] ?? 999;
            $valorTipoB = $prioridadTipo[$rolB] ?? 999;

            if ($valorTipoA !== $valorTipoB) {
                return $valorTipoA <=> $valorTipoB;
            }

            if ($rolA === 'atleta' && $rolB === 'atleta') {
                $modA = strtolower($a['Modalidad']);
                $modB = strtolower($b['Modalidad']);
                $valorModA = $prioridadModalidad[$modA] ?? 999;
                $valorModB = $prioridadModalidad[$modB] ?? 999;
                return $valorModA <=> $valorModB;
            }

            return 0;
        });

        return new Collection($datosOrdenados->values());
    }

    public function headings(): array
    {
        // Encabezados base
        $encabezados = [
            'ID inscripción',
            'Atleta',
            'Modalidad',
            'Submodalidad',
            'Categoría',
            'Fecha inscripción',
            'Estado',
            'Peso',
            'Código de equipo',
            'Rol'
        ];

        // Solo agregar "Evento" si no se filtró por uno
        if (!$this->id_evento) {
            array_splice($encabezados, 2, 0, ['Evento']);
        }

        $encabezadoExtra = [];

        // Si hay academia o evento, se agrega una fila superior
        if ($this->id_academia) {
            $nombreAcademia = \App\Models\Academia::find($this->id_academia)->nombre ?? 'Academia desconocida';
            $encabezadoExtra[] = [$nombreAcademia];
        }

        if ($this->id_evento) {
            $nombreEvento = \App\Models\Evento::find($this->id_evento)->nombre ?? 'Evento desconocido';
            $encabezadoExtra[] = [$nombreEvento];
        }

        // Si hay encabezados extra, los agregamos antes de la fila principal
        if (!empty($encabezadoExtra)) {
            return array_merge($encabezadoExtra, [$encabezados]);
        }

        return $encabezados;
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
        ])
            ->where('id_evento', $id_evento)
            ->get();

        $datos = $inscripciones->map(function ($i) {
            return [
                'ID inscripción' => $i->id_inscripcion,
                'Academia' => $i->academia->nombre ?? '—',
                'Atleta' => $i->atleta
                    ? $i->atleta->nombre . " " . $i->atleta->primer_apellido . " " . $i->atleta->segundo_apellido
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

        // Prioridades iguales a la función JS
        $prioridadTipo = [
            'ENTRENADOR' => 1,
            'ASISTENTE' => 2,
            'ATLETA' => 3,
        ];

        $prioridadModalidad = [
            'COMBATE' => 1,
            'POOMSAE' => 2,
            'FREESTYLE' => 3,
        ];

        // Orden personalizado
        $datosOrdenados = $datos->sort(function ($a, $b) use ($prioridadTipo, $prioridadModalidad) {
            $rolA = strtoupper($a['Rol']);
            $rolB = strtoupper($b['Rol']);

            $valorRolA = $prioridadTipo[$rolA] ?? 999;
            $valorRolB = $prioridadTipo[$rolB] ?? 999;

            // Primero ordena por tipo
            if ($valorRolA !== $valorRolB) {
                return $valorRolA - $valorRolB;
            }

            // Si ambos son atletas, ordenar por modalidad
            if ($rolA === 'ATLETA' && $rolB === 'ATLETA') {
                $modA = strtoupper($a['Modalidad']);
                $modB = strtoupper($b['Modalidad']);

                $valorModA = $prioridadModalidad[$modA] ?? 999;
                $valorModB = $prioridadModalidad[$modB] ?? 999;

                return $valorModA - $valorModB;
            }

            return 0;
        });

        // Finalmente agrupar por academia
        $datosAgrupados = $datosOrdenados->groupBy('Academia');

        $pdf = Pdf::loadView('pdf.inscripcionesEventosAdministrador', [
            'inscripciones' => $datosAgrupados
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('inscripciones-evento.pdf');
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
        ])
            ->where('id_academia', $id_academia)
            ->get();

        // Definir prioridad de rol y modalidad
        $prioridadTipo = [
            'entrenador' => 1,
            'asistente' => 2,
            'atleta' => 3,
        ];

        $prioridadModalidad = [
            'combate' => 1,
            'poomsae' => 2,
            'freestyle' => 3,
        ];

        // Mapear datos
        $datos = $inscripciones->map(function ($i) {
            return [
                'id_inscripcion' => $i->id_inscripcion,
                'academia' => $i->academia->nombre ?? '—',
                'evento' => $i->evento->nombre ?? '—',
                'modalidad' => ucfirst(strtolower($i->modalidad->nombre ?? '—')),
                'submodalidad' => ucfirst(strtolower($i->subModalidad->nombre ?? '—')),
                'categoria' => ($i->categoria?->peso_min !== null && $i->categoria?->peso_max !== null)
                    ? "Más de {$i->categoria->peso_min} kg y no excede {$i->categoria->peso_max} kg"
                    : '—',
                'atleta' => $i->atleta
                    ? "{$i->atleta->nombre} {$i->atleta->primer_apellido} {$i->atleta->segundo_apellido}"
                    : '—',
                'fecha_inscripcion' => $i->fecha_inscripcion,
                'estado' => ucfirst(strtolower($i->estado ?? '—')),
                'peso' => $i->peso ?? '—',
                'codigo_equipo' => $i->codigo_equipo ?? '—',
                'rol' => ucfirst(strtolower($i->rol ?? '—')),
            ];
        });

        // Ordenar según prioridad de rol y modalidad 
        $datosOrdenados = $datos->sort(function ($a, $b) use ($prioridadTipo, $prioridadModalidad) {
            $rolA = strtolower($a['rol']);
            $rolB = strtolower($b['rol']);

            $valorTipoA = $prioridadTipo[$rolA] ?? 999;
            $valorTipoB = $prioridadTipo[$rolB] ?? 999;

            // Primero por tipo
            if ($valorTipoA !== $valorTipoB) {
                return $valorTipoA <=> $valorTipoB;
            }

            // Si ambos son atletas, comparar por modalidad
            if ($rolA === 'atleta' && $rolB === 'atleta') {
                $modA = strtolower($a['modalidad']);
                $modB = strtolower($b['modalidad']);

                $valorModA = $prioridadModalidad[$modA] ?? 999;
                $valorModB = $prioridadModalidad[$modB] ?? 999;

                return $valorModA <=> $valorModB;
            }

            // Si son del mismo tipo y no atletas, mantener orden
            return 0;
        });

        $datosAgrupados = $datosOrdenados->groupBy('academia');

        $pdf = Pdf::loadView('pdf.inscripcionesAcademiasAdministrador', ['inscripciones' => $datosAgrupados])
            ->setPaper('a4', 'landscape');

        return $pdf->download('inscripciones-academia.pdf');
    }



    public function exportPdf()
    {
        $inscripciones = Inscripcion::with(['academia', 'atleta', 'evento', 'modalidad', 'subModalidad', 'categoria'])
            ->get();

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
                    ? "Más de {$i->categoria->peso_min} kg y no excede {$i->categoria->peso_max} kg"
                    : '—',
                'Fecha inscripción' => $i->fecha_inscripcion,
                'Estado' => $i->estado,
                'Peso' => $i->peso ?? '—',
                'Código de equipo' => $i->codigo_equipo ?? '—',
                'Rol' => $i->rol ?? '—',
            ];
        });

        // Prioridades como en la función JS
        $prioridadTipo = [
            'ENTRENADOR' => 1,
            'Entrenador' => 1,
            'ASISTENTE' => 2,
            'Asistente' => 2,
            'ATLETA' => 3,
            'Atleta' => 3,
        ];

        $prioridadModalidad = [
            'COMBATE' => 1,
            'Combate' => 1,
            'POOMSAE' => 2,
            'Poomsae' => 2,
            'FREESTYLE' => 3,
            'Freestyle' => 3,
        ];

        // Ordenar según el mismo criterio JS
        $datosOrdenados = $datos->sort(function ($a, $b) use ($prioridadTipo, $prioridadModalidad) {
            $tipoA = strtoupper($a['Rol']);
            $tipoB = strtoupper($b['Rol']);
            $valorTipoA = $prioridadTipo[$tipoA] ?? 999;
            $valorTipoB = $prioridadTipo[$tipoB] ?? 999;

            // Primero por tipo
            if ($valorTipoA !== $valorTipoB) {
                return $valorTipoA <=> $valorTipoB;
            }

            // Si ambos son atletas, ordenar por modalidad
            if (strtoupper($tipoA) === 'ATLETA' && strtoupper($tipoB) === 'ATLETA') {
                $modA = strtoupper($a['Modalidad']);
                $modB = strtoupper($b['Modalidad']);
                $valorModA = $prioridadModalidad[$modA] ?? 999;
                $valorModB = $prioridadModalidad[$modB] ?? 999;
                return $valorModA <=> $valorModB;
            }

            return 0;
        });

        $datosAgrupados = $datosOrdenados->groupBy('Academia');

        $pdf = Pdf::loadView('pdf.inscripciones', [
            'inscripciones' => $datosAgrupados
        ])->setPaper('a4', 'landscape');

        return $pdf->download('inscripciones.pdf');
    }


    /** ===================================================================== ACADEMIA ============================================================================ */

    public function exportAcademiaPdf($id_academia)
    {
        $inscripciones = Inscripcion::with([
            'academia',
            'atleta',
            'evento',
            'modalidad',
            'subModalidad',
            'categoria'
        ])
            ->where('id_academia', $id_academia)
            ->get();

        $datos = $inscripciones->map(function ($i) {
            return [
                'ID inscripción' => $i->id_inscripcion,
                'Academia' => $i->academia->nombre ?? '—',
                'Atleta' => $i->atleta
                    ? $i->atleta->nombre . " " . $i->atleta->primer_apellido . " " . $i->atleta->segundo_apellido
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

        // Prioridad de tipo (Rol)
        $prioridadTipo = [
            'ENTRENADOR' => 1,
            'ASISTENTE' => 2,
            'ATLETA' => 3,
        ];

        // Prioridad de modalidad (solo para atletas)
        $prioridadModalidad = [
            'COMBATE' => 1,
            'POOMSAE' => 2,
            'FREESTYLE' => 3,
        ];

        // Ordena según la lógica JS (Rol → Modalidad)
        $datosOrdenados = $datos->sort(function ($a, $b) use ($prioridadTipo, $prioridadModalidad) {
            $rolA = strtoupper($a['Rol']);
            $rolB = strtoupper($b['Rol']);

            $valorRolA = $prioridadTipo[$rolA] ?? 999;
            $valorRolB = $prioridadTipo[$rolB] ?? 999;

            // Primero ordena por tipo
            if ($valorRolA !== $valorRolB) {
                return $valorRolA - $valorRolB;
            }

            // Si ambos son atletas, ordenar por modalidad
            if ($rolA === 'ATLETA' && $rolB === 'ATLETA') {
                $modA = strtoupper($a['Modalidad']);
                $modB = strtoupper($b['Modalidad']);

                $valorModA = $prioridadModalidad[$modA] ?? 999;
                $valorModB = $prioridadModalidad[$modB] ?? 999;

                return $valorModA - $valorModB;
            }

            return 0;
        });

        // Agrupa por academia
        $datosAgrupados = $datosOrdenados->groupBy('Academia');

        $pdf = Pdf::loadView('pdf.inscripciones', [
            'inscripciones' => $datosAgrupados
        ])->setPaper('a4', 'landscape');

        return $pdf->download('inscripciones.pdf');
    }



    public function exportEventoAcademiaPdf($id_evento)
    {
        $usuarioId = request()->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);
        $academia = $usuario->academia;
        $id_academia = $academia->id_academia ?? null;

        $query = Inscripcion::with(['academia', 'atleta', 'evento', 'modalidad', 'subModalidad', 'categoria'])
            ->where('id_academia', $id_academia);

        if (!is_null($id_evento)) {
            $query->where('id_evento', $id_evento);
        }

        $inscripciones = $query->get();

        if ($inscripciones->isEmpty()) {
            return back()->with('error', 'No hay inscripciones registradas para este evento.');
        }

        $nombreEvento = $inscripciones->first()->evento->nombre ?? 'Evento desconocido';

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
                    ? "Más de {$i->categoria->peso_min} kg y no excede {$i->categoria->peso_max} kg"
                    : '—',
                'Fecha inscripción' => $i->fecha_inscripcion,
                'Estado' => $i->estado,
                'Peso' => $i->peso ?? '—',
                'Código de equipo' => $i->codigo_equipo ?? '—',
                'Rol' => $i->rol ?? '—',
            ];
        });

        $prioridadTipo = [
            'ENTRENADOR' => 1,
            'Asistente' => 2,
            'ATLETA' => 3,
            'Atleta' => 3,
        ];

        $prioridadModalidad = [
            'COMBATE' => 1,
            'Combate' => 1,
            'POOMSAE' => 2,
            'Poomsae' => 2,
            'FREESTYLE' => 3,
            'Freestyle' => 3,
        ];

        $datosOrdenados = $datos->sort(function ($a, $b) use ($prioridadTipo, $prioridadModalidad) {
            $tipoA = strtoupper($a['Rol']);
            $tipoB = strtoupper($b['Rol']);
            $valorTipoA = $prioridadTipo[$tipoA] ?? 999;
            $valorTipoB = $prioridadTipo[$tipoB] ?? 999;

            if ($valorTipoA !== $valorTipoB) {
                return $valorTipoA <=> $valorTipoB;
            }

            if (strtoupper($tipoA) === 'ATLETA' && strtoupper($tipoB) === 'ATLETA') {
                $modA = strtoupper($a['Modalidad']);
                $modB = strtoupper($b['Modalidad']);
                $valorModA = $prioridadModalidad[$modA] ?? 999;
                $valorModB = $prioridadModalidad[$modB] ?? 999;
                return $valorModA <=> $valorModB;
            }

            return 0;
        });

        $datosAgrupados = $datosOrdenados->groupBy('Academia');

        $pdf = Pdf::loadView('pdf.inscripcionesEventos', [
            'inscripciones' => $datosAgrupados,
            'nombreEvento' => $nombreEvento,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('reporte_de_evento.pdf');
    }

}