<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PadronNacimiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProcesarPadronMaestro extends Command
{
    protected $signature = 'procesar:padron';
    protected $description = 'Lee el padrón electoral y obtiene los datos principales de cada registro.';

    // Configuraciones internas
    protected $minLineLength = 133;
    protected $errorFlushSize = 10000; // cada cuántos errores volcar a disco (ajustable)
    protected $errorFilePath = ''; // se inicializa en el constructor

    public function __construct()
    {
        parent::__construct();
        // Inicializar aquí propiedades que requieren llamadas a funciones
        $this->errorFilePath = 'private/errores_padron_' . date('Ymd_His') . '.json';
    }

    public function handle()
    {
        $path = storage_path('app/private/archivos_txt/MAESTRO_NACIMIENTOS_PLANO_SEPTIEMBRE 2025.txt');

        if (!file_exists($path)) {
            $this->error("El archivo no existe: $path");
            return 1;
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            $this->error("No se pudo abrir el archivo.");
            return 1;
        }

        $count = 0;
        $errores = [];
        $inserted = 0;
        $skippedShortLines = 0;

        while (($line = fgets($handle)) !== false) {
            $count++;

            if (strlen($line) < $this->minLineLength) {
                $skippedShortLines++;
                continue;
            }

            $line = mb_convert_encoding($line, 'UTF-8', 'Windows-1252');

            $cedula = null;
            $fechaNacimientoRaw = null;
            $fechaNacimiento = null;
            $primerApellido = null;
            $segundoApellido = null;
            $nombre = null;

            try {
                $cedula           = trim(substr($line, 0, 9));
                $fechaNacimientoRaw = trim(substr($line, 37, 8));
                $primerApellido   = trim(substr($line, 68, 7));
                $segundoApellido  = trim(substr($line, 94, 8));
                $nombre           = trim(substr($line, 120, 48));

                $primerApellido = mb_strtolower($primerApellido, 'UTF-8');
                $segundoApellido = mb_strtolower($segundoApellido, 'UTF-8');
                $nombre = mb_strtolower($nombre, 'UTF-8');

                $primerApellido = preg_replace('/\s+/', ' ', trim($primerApellido));
                $segundoApellido = preg_replace('/\s+/', ' ', trim($segundoApellido));
                $nombre = preg_replace('/\s+/', ' ', trim($nombre));

                if (!empty($fechaNacimientoRaw) && preg_match('/^\d{8}$/', $fechaNacimientoRaw)) {
                    try {
                        $fechaNacimiento = Carbon::createFromFormat('Ymd', $fechaNacimientoRaw)->format('Y-m-d');
                    } catch (Exception $e) {
                        $fechaNacimiento = null;
                    }
                } else {
                    $fechaNacimiento = null;
                }

                // Imprimir (comentar si es demasiado ruido)
                $this->line("[$count] {$cedula} | {$fechaNacimiento} | {$primerApellido} | {$segundoApellido} | {$nombre}");

                try {
                    PadronNacimiento::create([
                        'identificacion' => $cedula,
                        'primer_apellido' => $primerApellido,
                        'segundo_apellido' => $segundoApellido,
                        'nombre' => $nombre,
                        'fecha_nacimiento' => $fechaNacimiento,
                    ]);
                    $inserted++;
                } catch (Exception $e) {
                    $errores[] = [
                        'line' => $count,
                        'cedula' => $cedula,
                        'fecha_nacimiento_raw' => $fechaNacimientoRaw,
                        'fecha_nacimiento' => $fechaNacimiento,
                        'primer_apellido' => $primerApellido,
                        'segundo_apellido' => $segundoApellido,
                        'nombre' => $nombre,
                        'error' => 'DB_INSERT_FAILED',
                        'message' => $e->getMessage(),
                    ];
                }
            } catch (Exception $e) {
                $errores[] = [
                    'line' => $count,
                    'cedula' => $cedula,
                    'fecha_nacimiento_raw' => $fechaNacimientoRaw,
                    'primer_apellido' => $primerApellido,
                    'segundo_apellido' => $segundoApellido,
                    'nombre' => $nombre,
                    'error' => 'PARSING_FAILED',
                    'message' => $e->getMessage(),
                ];
            }

            if (count($errores) >= $this->errorFlushSize) {
                $this->flushErrorsToDisk($errores);
                $errores = [];
            }

            unset($line);
        }

        fclose($handle);

        if (!empty($errores)) {
            $this->flushErrorsToDisk($errores);
            $errores = [];
        }

        $this->info("Procesamiento finalizado. Total líneas leídas: {$count}");
        $this->info("Insertados en BD: {$inserted}");
        $this->info("Líneas cortas/saltadas: {$skippedShortLines}");
        $this->info("Archivos de errores (JSON) guardados en storage/app/{$this->errorFilePath}");

        return 0;
    }

    protected function flushErrorsToDisk(array $errores)
    {
        $dir = dirname($this->errorFilePath);

        // Si dirname devolvió '.' (ruta relativa simple), normalizamos al directorio 'private'
        if ($dir === '.' || $dir === '') {
            $dir = 'private';
        }

        if (!Storage::exists($dir)) {
            Storage::makeDirectory($dir);
        }

        $content = '';
        foreach ($errores as $err) {
            $content .= json_encode($err, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        }

        Storage::append($this->errorFilePath, trim($content));
        $this->line("Volcados " . count($errores) . " errores a: storage/app/{$this->errorFilePath}");
    }
}
