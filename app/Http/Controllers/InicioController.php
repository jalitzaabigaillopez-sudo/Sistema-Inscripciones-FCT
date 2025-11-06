<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Academia;
use App\Models\Atleta;
use App\Models\Inscripcion;
use App\Models\Evento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\SessionService;

class InicioController extends Controller
{

    public function __construct(Request $request)
    {
        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }
    }
    public function estadisticasEventos(Request $request)
{
    $eventos = Evento::orderBy('nombre')->get();

    $estadisticas = [
        'total_inscripciones' => 0,
        'total_atletas' => 0,
        'total_academias' => 0,
        'por_modalidad' => [],
        'por_submodalidad' => [],
        'por_grado' => [],
        'por_categoria' => [],
        'por_academia' => [],
        'por_edad' => [],
        'por_nacimiento' => [],
        'por_sexo' => [],
    ];

    $eventoSeleccionado = null;
    $eventoId = $request->input('id_evento', $request->input('evento_id'));

    if ($eventoId) {
        $eventoSeleccionado = Evento::where('id_evento', $eventoId)->first() ?? Evento::find($eventoId);

        if (!$eventoSeleccionado) {
            session()->flash('error', 'Evento no encontrado');
            return view('admin.estadistica-evento', compact('eventos', 'eventoSeleccionado', 'estadisticas'));
        }

        $valor = $eventoSeleccionado->id_evento ?? $eventoId;

        $inscripciones = Inscripcion::with([
                'atleta',
                'academia',
                'modalidad',
                'submodalidad',
                'grado',
                'evento.categoria',
                'evento.division.categoria',
            ])
            ->where('id_evento', $valor)
            ->get();

        if ($inscripciones->isNotEmpty()) {
            $estadisticas['total_inscripciones'] = $inscripciones->count();
            $estadisticas['total_atletas'] = $inscripciones->pluck('id_atleta')->filter()->unique()->count();
            $estadisticas['total_academias'] = $inscripciones->pluck('id_academia')->filter()->unique()->count();

            $estadisticas['por_modalidad'] = $inscripciones
                ->map(fn($i) => $i->modalidad->nombre ?? ($i->modalidad_nombre ?? 'Sin modalidad'))
                ->countBy()
                ->toArray();

            $estadisticas['por_submodalidad'] = $inscripciones
                ->map(fn($i) => $i->submodalidad->nombre ?? ($i->submodalidad_nombre ?? 'Sin submodalidad'))
                ->countBy()
                ->toArray();

            $estadisticas['por_grado'] = $inscripciones
                ->map(fn($i) => $i->grado->nombre ?? ($i->grado_nombre ?? 'Sin grado'))
                ->countBy()
                ->toArray();

           $estadisticas['por_categoria'] = $inscripciones
                ->map(function ($i) {
                    // intentos en orden de prioridad; data_get gestiona nulls sin lanzar
                    $eventoCat = data_get($i, 'evento.categoria.nombre');
                    if (!empty($eventoCat)) return $eventoCat;

                    $divisionCat = data_get($i, 'evento.division.categoria.nombre');
                    if (!empty($divisionCat)) return $divisionCat;

                    $atletaCat = data_get($i, 'atleta.categoria.nombre');
                    if (!empty($atletaCat)) return $atletaCat;

                    // campos planos en la inscripción (si existen)
                    if (!empty($i->categoria_nombre)) return $i->categoria_nombre;
                    if (!empty($i->categoria_id)) return 'ID ' . $i->categoria_id;

                    return 'Sin categoría';
                })
                ->countBy()
                ->toArray();

            $estadisticas['por_academia'] = $inscripciones
                ->map(fn($i) => $i->academia->nombre ?? ($i->academia_nombre ?? 'Sin academia'))
                ->countBy()
                ->toArray();

            $ageBuckets = [
                '0-5'   => [0, 5],
                '6-10'  => [6, 10],
                '11-15' => [11, 15],
                '16-20' => [16, 20],
                '21-30' => [21, 30],
                '31-40' => [31, 40],
                '41-50' => [41, 50],
                '51+'   => [51, 150],
            ];

            $estadisticas['por_edad'] = $inscripciones
                ->map(function ($i) use ($ageBuckets) {
                    $edad = null;
                    if (!empty(optional($i->atleta)->edad)) {
                        $edad = (int) optional($i->atleta)->edad;
                    } elseif (!empty(optional($i->atleta)->fecha_nacimiento)) {
                        try {
                            $edad = \Carbon\Carbon::parse($i->atleta->fecha_nacimiento)->age;
                        } catch (\Throwable $e) {
                            $edad = null;
                        }
                    }
                    if ($edad === null) return 'Sin edad';
                    foreach ($ageBuckets as $label => [$min, $max]) {
                        if ($edad >= $min && $edad <= $max) return $label;
                    }
                    return 'Desconocida';
                })
                ->countBy()
                ->toArray();

            $estadisticas['por_nacimiento'] = $inscripciones
                ->map(fn($i) => optional($i->atleta)->fecha_nacimiento ? date('Y', strtotime($i->atleta->fecha_nacimiento)) : 'Sin año')
                ->countBy()
                ->toArray();

            $years = $estadisticas['por_nacimiento'] ?? [];
            ksort($years);
            $estadisticas['por_nacimiento_top'] = count($years) > 100
                ? array_slice($years, -100, 100, true)
                : $years;

            $estadisticas['por_sexo'] = $inscripciones
                ->map(function($i) {
                    $raw = optional($i->atleta)->sexo ?? ($i->sexo ?? null);
                    $val = is_string($raw) ? trim(mb_strtolower($raw)) : null;

                    if (in_array($val, ['m', 'masculino', 'hombre'])) return 'Masculino';
                    if (in_array($val, ['f', 'femenino', 'mujer'])) return 'Femenino';
                    if (empty($val)) return 'Sin especificar';
                    return mb_strtoupper($raw);
                })
                ->countBy()
                ->toArray();
        } else {
            session()->flash('info', 'No hay inscripciones para el evento seleccionado');
        }
    }

    return view('admin.estadistica-evento', compact('eventos', 'eventoSeleccionado', 'estadisticas'));
}




    public function index(Request $request)
    {

        $usuarioId = $request->session()->get('usuario');
        $usuario = Usuario::find($usuarioId);
        $today = \Carbon\Carbon::today();

        if ($usuario->rol != 'academia') {

            // ================= ADMIN =================
            $usersCount = Usuario::count();
            $academiesCount = Academia::count();
            $atletasCount = Atleta::count();
            $inscripcionesCount = Inscripcion::count();
            $eventosCount = Evento::where('estado', 'Activo')->count();

            // Próximos eventos (activos y que aún no han terminado)
            $proximosEventos = Evento::where('estado', 'Activo')
                ->where(function ($q) use ($today) {
                    $q->whereDate('fecha_inicio', '>=', $today)
                        ->orWhereDate('fecha_final', '>=', $today);
                })
                ->orderBy('fecha_inicio', 'asc')
                ->take(5)
                ->get();

            // ================== EVENTOS POR MES ==================
            $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            $eventosMesDB = Evento::selectRaw('MONTH(fecha_inicio) as mes, COUNT(*) as total')
                ->groupBy('mes')
                ->orderBy('mes')
                ->pluck('total', 'mes')
                ->toArray();

            // Asegurar 12 meses (llenar con 0 si no hay eventos)
            $eventosPorMes = [];
            for ($i = 1; $i <= 12; $i++) {
                $eventosPorMes[] = $eventosMesDB[$i] ?? 0;
            }

            // ================== DISTRIBUCIÓN POR GÉNERO ==================
            $generoDistribucion = [
                Atleta::whereIn('sexo', ['Masculino', 'M'])->count(),
                Atleta::whereIn('sexo', ['Femenino', 'F'])->count()
            ];
            // ================== ESTADÍSTICAS DE EVENTOS ==================


            // ================== RETORNO A LA VISTA ==================
            return view('admin/dashboard', compact(
                'usuario',
                'usersCount',
                'academiesCount',
                'atletasCount',
                'inscripcionesCount',
                'eventosCount',
                'proximosEventos',
                'meses',
                'eventosPorMes',
                'generoDistribucion',
            ));
        }
        // ================= ACADEMIA =================
        // Aquí tu código para academia, si lo quieres también puedo revisarlo


        // ACADEMIA
        $academia = $usuario->academia;
        $id_academia = $academia->id_academia ?? null;
        $nombre_academia = $academia->nombre ?? null;


        //$eventosInscritos = Inscripcion::where('id_academia', $academia->id_academia)->count();
        $totalAtletas = Atleta::where('id_academia', $academia->id_academia)->count();

        // Avance de eventos (porcentaje)
        $totalEventos = Evento::count();
        //$avanceEventos = $totalEventos > 0 ? round(($eventosInscritos / $totalEventos) * 100) : 0;

        // Eventos únicos inscritos por la academia
        $eventosInscritos = Inscripcion::where('id_academia', $academia->id_academia)
            ->distinct('id_evento')
            ->count('id_evento');

        // Total de eventos disponibles (puedes filtrar por estado si lo deseas)
        $totalEventos = Evento::count(); // o Evento::where('estado', 'Activo')->count();

        // Porcentaje de avance institucional
        $avanceEventos = $totalEventos > 0
            ? round(($eventosInscritos / $totalEventos) * 100)
            : 0;


        // Próximos eventos
        $proximosEventos = Evento::where('estado', 'Activo')
            ->where(function ($q) use ($today) {
                $q->whereDate('fecha_inicio', '>=', $today)
                    ->orWhereDate('fecha_final', '>=', $today);
            })
            ->orderBy('fecha_inicio', 'asc')
            ->get();



        // =================== DATOS PARA GRÁFICOS ===================

        // =======================================
        // GRÁFICOS
        // =======================================

        // 1️⃣ Categorías de edad (basadas en fecha_nacimiento)
        $categorias = ['Infantil', 'Cadete', 'Junior', 'Adulto', 'Master'];



        $inscripciones = [
            Atleta::where('id_academia', $academia->id_academia)
                ->whereRaw('TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 0 AND 11')->count(), // Infantil
            Atleta::where('id_academia', $academia->id_academia)
                ->whereRaw('TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 12 AND 14')->count(), // Cadete
            Atleta::where('id_academia', $academia->id_academia)
                ->whereRaw('TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 15 AND 17')->count(), // Junior
            Atleta::where('id_academia', $academia->id_academia)
                ->whereRaw('TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 18 AND 29')->count(), // Adulto
            Atleta::where('id_academia', $academia->id_academia)
                ->whereRaw('TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) >= 30')->count(), // Master
        ];

        // 2️⃣ Distribución de grados (usando la tabla grados)
        $grados = DB::table('grados')
            ->join('atletas', 'grados.id_grado', '=', 'atletas.id_grado')
            ->where('atletas.id_academia', $academia->id_academia)
            ->select('grados.nombre as grado', DB::raw('COUNT(*) as total'))
            ->groupBy('grados.nombre')
            ->pluck('total', 'grado')
            ->toArray();

        $gradosLabels = array_keys($grados); // ['Blanco', 'Amarillo', 'Verde', ...]
        $gradosCount = array_values($grados); // [10, 5, 7, ...]

        $coloresGrados = [];
        foreach ($gradosLabels as $grado) {
            $coloresGrados[] = match (strtolower($grado)) {
                'blanco', 'blanca' => '#ffffff',
                'amarillo', 'amarilla' => '#ffc107',
                'verde' => '#28a745',
                'azul' => '#0d6efd',
                'rojo' => '#dc3545',
                'negro' => '#212529',
                default => '#6c757d', // gris para grados desconocidos
            };
        }



        // =========================================================

        return view('academia/dashboard-academia', compact(
            'usuario',
            'academia',
            'eventosInscritos',
            'totalAtletas',
            'avanceEventos',
            'proximosEventos',
            'categorias',
            'inscripciones',
            'grados',
            'gradosLabels',
            'gradosCount',
            'coloresGrados',
        ));
    }

}


