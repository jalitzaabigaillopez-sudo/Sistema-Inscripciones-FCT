<?php

namespace App\Http\Controllers;

use App\Helpers\RoleGate;
use Illuminate\Http\Request;
use App\Models\PadronNacimiento;
use App\Models\Categoria;
use App\Services\SessionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PadronNacimientoController extends Controller
{
    public function __construct(Request $request)
    {
        if (!SessionService::checkSession($request)) {
            redirect()->route('login')->send();
        }
    }

    public function index()
    {
        RoleGate::requireAdmin();
        return view('admin.padron.padronNacimientos');
    }

    public function subirArchivo(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimetypes:text/plain|max:3072000', // 3 GB
        ]);

        $archivo = $request->file('archivo');

        // Ruta dentro de storage/app
        $rutaDestino = storage_path('app/private/archivos_txt');

        if (!file_exists($rutaDestino)) {
            mkdir($rutaDestino, 0777, true);
        }

        $nuevoNombre = 'padron_semanal' . '.txt';

        // Streams para copiar archivos muy grandes
        $stream = fopen($archivo->getRealPath(), 'r');
        $destino = fopen($rutaDestino . '/' . $nuevoNombre, 'w');

        stream_copy_to_stream($stream, $destino);

        fclose($stream);
        fclose($destino);

        $this->procesarPadron();

        return "La lectura de este archivo se ha realizado correctamente. Se creo una copia en storage/private/archivos_txt como: $nuevoNombre";
    }


    public function procesarPadron()
    {
        $ruta = storage_path('app/private/archivos_txt/padron_semanal.txt');

        if (!file_exists($ruta)) {
            $msg = "El archivo no existe: {$ruta}";
            Log::error($msg);
            if (app()->runningInConsole())
                echo $msg . PHP_EOL;
            return ["error" => $msg];
        }

        $handle = fopen($ruta, 'r');

        if (!$handle) {
            $msg = "No se pudo abrir el archivo: {$ruta}";
            Log::error($msg);
            if (app()->runningInConsole())
                echo $msg . PHP_EOL;
            return ["error" => $msg];
        }

        // Archivo de errores 
        $errorFile = 'private/errores_padron_' . date('Ymd_His') . '.log';

        $registros = [];
        $lineNumber = 0;

        while (($line = fgets($handle)) !== false) {
            $lineNumber++;

            if (strlen($line) < 133) {
                continue;
            }

            // Convertir encoding (por si viene en Windows-1252)
            $line = mb_convert_encoding($line, 'UTF-8', 'Windows-1252');

            try {
                $cedula = trim(substr($line, 0, 9));
                $fechaRaw = trim(substr($line, 37, 8));
                $primerApellido = trim(substr($line, 57, 25));
                $segundoApellido = trim(substr($line, 83, 25));
                $nombre = trim(substr($line, 109, 49));

                // Limpieza de espacios extra y normalizar
                $primerApellido = preg_replace('/\s+/', ' ', mb_strtolower($primerApellido, 'UTF-8'));
                $segundoApellido = preg_replace('/\s+/', ' ', mb_strtolower($segundoApellido, 'UTF-8'));
                $nombre = preg_replace('/\s+/', ' ', mb_strtolower($nombre, 'UTF-8'));

                // Convertir fecha YYYYMMDD -> YYYY-MM-DD
                $fechaNacimiento = null;
                if (preg_match('/^\d{8}$/', $fechaRaw)) {
                    $fechaNacimiento = Carbon::createFromFormat('Ymd', $fechaRaw)->format('Y-m-d');
                }

                // Guardar en BD 
                PadronNacimiento::create([
                    'identificacion' => $cedula,
                    'primer_apellido' => $primerApellido,
                    'segundo_apellido' => $segundoApellido,
                    'nombre' => $nombre,
                    'fecha_nacimiento' => $fechaNacimiento,
                ]);

                // Imprimir línea procesada en consola (opcional)
                if (app()->runningInConsole()) {
                    echo "OK  [{$lineNumber}] {$cedula} | {$fechaNacimiento} | {$primerApellido} {$segundoApellido} {$nombre}" . PHP_EOL;
                }

            } catch (\Throwable $e) {
                // Preparar información del error
                $err = [
                    'linea' => $lineNumber,
                    'cedula' => isset($cedula) ? $cedula : null,
                    'fecha_raw' => isset($fechaRaw) ? $fechaRaw : null,
                    'primer_apellido' => isset($primerApellido) ? $primerApellido : null,
                    'segundo_apellido' => isset($segundoApellido) ? $segundoApellido : null,
                    'nombre' => isset($nombre) ? $nombre : null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ];

                // Guardar en array (por si querés devolverlo)
                $registros[] = $err;

                // Loguear
                Log::error('Error procesando padrón', $err);

              
                try {
                    $lineToWrite = json_encode($err, JSON_UNESCAPED_UNICODE) . PHP_EOL;
                    Storage::append($errorFile, trim($lineToWrite));
                } catch (\Throwable $ee) {
                  
                    Log::error('No se pudo escribir el archivo de errores: ' . $ee->getMessage());
                }

                if (app()->runningInConsole()) {
                    echo "ERROR [Linea {$lineNumber}] " . $e->getMessage() . PHP_EOL;
                }

                continue;
            }
        }

        fclose($handle);

        if (app()->runningInConsole()) {
            echo "Proceso finalizado. Errores detectados: " . count($registros) . PHP_EOL;
            echo "Archivo de errores guardado en: storage/app/{$errorFile}" . PHP_EOL;
        }

        return $registros;
    }



    public function buscarPersona(Request $request)
    {

        $cedula = $request->input('cedula');
        $sexo = $request->input('sexo');
        $persona = PadronNacimiento::where('identificacion', $cedula)->first();

        if ($persona) {
            $year = (int) \Carbon\Carbon::parse($persona->fecha_nacimiento)->year;

            $pesos = Categoria::where('division', $this->categoriaPorNacimiento($year))->where('sexo', $sexo)->get();

            return response()->json([
                'nombre' => $persona->nombre,
                'primer_apellido' => $persona->primer_apellido,
                'segundo_apellido' => $persona->segundo_apellido,
                'fecha_nacimiento' => $persona->fecha_nacimiento,
                'division' => $this->categoriaPorNacimiento($year),
                'sexo' => $sexo,
                'pesos' => $pesos
            ]);
        }
        return response()->json(null);
    }

    function categoriaPorNacimiento($year)
    {
        if ($year >= 1930 && $year <= 1988) {
            return "EJECUTIVO";
        } elseif ($year >= 1989 && $year <= 2006) {
            return "SENIOR";
        } elseif ($year >= 2007 && $year <= 2009) {
            return "JUNIOR";
        } elseif ($year >= 2010 && $year <= 2012) {
            return "CADETE";
        } elseif ($year >= 2013 && $year <= 2014) {
            return "PRE CADETE";
        } elseif ($year >= 2015 && $year <= 2016) {
            return "INFANTIL A";
        } elseif ($year >= 2017 && $year <= 2018) {
            return "INFANTIL B";
        } elseif ($year >= 2019 && $year <= 2023) {
            return "PEWEE";
        }

        return "";
    }

    public function buscarGeneral(Request $request)
    {
        RoleGate::requireAdmin();

        try {
            $query = PadronNacimiento::select(
                'identificacion',
                'nombre',
                'primer_apellido',
                'segundo_apellido',
                'fecha_nacimiento'
            );

            // Búsqueda por cédula (instantánea)
            if ($request->identificacion) {
                return response()->json(
                    PadronNacimiento::where('identificacion', $request->identificacion)
                        ->limit(1)
                        ->get()
                );
            }

            // Evitar búsquedas enormes por una sola letra
            if ($request->nombre && strlen($request->nombre) < 2) {
                return response()->json([]);
            }
            if ($request->primer_apellido && strlen($request->primer_apellido) < 2) {
                return response()->json([]);
            }
            if ($request->segundo_apellido && strlen($request->segundo_apellido) < 2) {
                return response()->json([]);
            }

            // Filtros usando índices (texto%)
            if ($request->nombre) {
                $query->where('nombre', 'LIKE', $request->nombre . '%');
            }

            if ($request->primer_apellido) {
                $query->where('primer_apellido', 'LIKE', $request->primer_apellido . '%');
            }

            if ($request->segundo_apellido) {
                $query->where('segundo_apellido', 'LIKE', $request->segundo_apellido . '%');
            }

            // Siempre limitar para no saturar memoria
            $query->orderBy('identificacion');

            return response()->json($query->limit(50)->get());

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
