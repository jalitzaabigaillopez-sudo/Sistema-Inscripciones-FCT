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
        'por_nacimiento_top' => [],
        'por_sexo' => [],
    ];

    $eventoId = $request->input('id_evento');
    $eventoSeleccionado = $eventoId
        ? Evento::where('id_evento', $eventoId)->first() ?? Evento::find($eventoId)
        : null;

    if (! $eventoSeleccionado) {
        if ($eventoId) session()->flash('error', 'Evento no encontrado');
        return view('admin.estadistica-evento', compact('eventos', 'eventoSeleccionado', 'estadisticas'));
    }

    $inscripciones = Inscripcion::with([
        'atleta',
        'academia',
        'modalidad',
        'submodalidad',
        'grado',
         
    ])->where('id_evento', $eventoSeleccionado->id_evento)->get();

    if ($inscripciones->isEmpty()) {
        session()->flash('info', 'No hay inscripciones para el evento seleccionado');
        return view('admin.estadistica-evento', compact('eventos', 'eventoSeleccionado', 'estadisticas'));
    }

    // Normalizador
    $normalize = fn($val) => $val ? mb_convert_case(trim((string) $val), MB_CASE_TITLE) : null;

    // Resolutores
    $resolveGrado = fn($i) => $normalize(optional($i->grado)->nombre) ?? $normalize($i->grado_nombre) ?? 'Sin grado';
    $resolveAcademia = fn($i) => $normalize(optional($i->academia)->nombre) ?? $normalize($i->academia_nombre) ?? 'Sin academia';
    $resolveModalidad = fn($i) => $normalize(optional($i->modalidad)->nombre) ?? $normalize($i->modalidad_nombre) ?? 'Sin modalidad';
    $resolveSubmodalidad = fn($i) => $normalize(optional($i->submodalidad)->nombre) ?? $normalize($i->submodalidad_nombre) ?? 'Sin submodalidad';
    $resolveCategoria = fn($i) => $normalize(optional($i->categoria)->nombre) ?? $normalize($i->categoria_nombre) ?? 'Sin categoría';
    $resolveRol = fn($i) => $normalize($i->rol) ?? 'Sin rol';

      
    $resolveSexo = function ($i) {
        $raw = optional($i->atleta)->sexo ?? $i->sexo;
        if (! $raw) return 'Sin especificar';
        $val = mb_strtolower(trim($raw));
        return match(true) {
            in_array($val, ['m', 'masculino', 'hombre']) => 'Masculino',
            in_array($val, ['f', 'femenino', 'mujer']) => 'Femenino',
            default => mb_convert_case($raw, MB_CASE_TITLE),
        };
    };

    $ageBuckets = [
        '0-5' => [0,5], '6-10' => [6,10], '11-15' => [11,15],
        '16-20' => [16,20], '21-30' => [21,30], '31-40' => [31,40],
        '41-50' => [41,50], '51+' => [51,150],
    ];

    $resolveEdadBucket = function ($i) use ($ageBuckets) {
        $edad = optional($i->atleta)->edad;
        if (! $edad && optional($i->atleta)->fecha_nacimiento) {
            try {
                $edad = \Carbon\Carbon::parse($i->atleta->fecha_nacimiento)->age;
            } catch (\Throwable $e) {}
        }
        if (! $edad) return 'Sin edad';
        foreach ($ageBuckets as $label => [$min, $max]) {
            if ($edad >= $min && $edad <= $max) return $label;
        }
        return 'Desconocida';
    };

    // Estadísticas base
    $estadisticas['total_inscripciones'] = $inscripciones->count();
    $estadisticas['total_atletas'] = $inscripciones->pluck('id_atleta')->filter()->unique()->count();
    $estadisticas['por_academia'] = $inscripciones->map($resolveAcademia)->countBy()->toArray();


    // Distribuciones
    $estadisticas['por_modalidad'] = $inscripciones->map($resolveModalidad)->countBy()->toArray();
    $estadisticas['por_submodalidad'] = $inscripciones->map($resolveSubmodalidad)->countBy()->toArray();
    $estadisticas['por_grado'] = $inscripciones->map($resolveGrado)->countBy()->toArray();
    $estadisticas['por_categoria'] = $inscripciones->map($resolveCategoria)->countBy()->toArray();
    $estadisticas['por_academia'] = $inscripciones->map($resolveAcademia)->countBy()->toArray();
    $estadisticas['por_edad'] = $inscripciones->map($resolveEdadBucket)->countBy()->toArray();
    $estadisticas['por_sexo'] = $inscripciones->map($resolveSexo)->countBy()->toArray();
    $estadisticas['por_rol'] = $inscripciones->map($resolveRol)->countBy()->toArray();

    $estadisticas['por_academia'] = $inscripciones->groupBy($resolveAcademia)->map(function ($grupo) {
    return [
        'cantidad' => $grupo->count(),
        'monto' => $grupo->sum(fn($i) => $i->monto ?? 0),
    ];
})->toArray();
    // Por año de nacimiento
    
    $estadisticas['por_nacimiento'] = $inscripciones->map(function ($i) {
        $fn = optional($i->atleta)->fecha_nacimiento;
        if (! $fn) return 'Sin año';
        try {
            return \Carbon\Carbon::parse($fn)->format('Y');
        } catch (\Throwable $e) {
            return 'Sin año';
        }
    })->countBy()->toArray();

    ksort($estadisticas['por_nacimiento']);
    $estadisticas['por_nacimiento_top'] = count($estadisticas['por_nacimiento']) > 100
        ? array_slice($estadisticas['por_nacimiento'], -100, 100, true)
        : $estadisticas['por_nacimiento'];

    $mascarar = fn($valor) => in_array($valor, ['Sin grado', 'Sin modalidad', 'Sin submodalidad', 'Sin categoría', 'Sin academia'])
    ? 'No especificado'
    : $valor;


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


