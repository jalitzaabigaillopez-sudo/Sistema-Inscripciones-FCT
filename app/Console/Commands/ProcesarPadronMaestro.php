<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PadronNacimiento;
use Carbon\Carbon;

class ProcesarPadronMaestro extends Command
{
    protected $signature = 'procesar:padron';
    protected $description = 'Lee el padrón electoral y obtiene los datos principales de cada registro.';

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

        while (($line = fgets($handle)) !== false) {
            $count++;

            // Evitar líneas cortas o vacías
            if (strlen($line) < 133) {
                continue;
            }

            $line = mb_convert_encoding($line, 'UTF-8', 'Windows-1252');
            $fechaNacimiento = null;

            $cedula = trim(substr($line, 0, 9));
            $fechaNacimientoRaw = trim(substr($line, 37, 8));
            $primerApellido = trim(substr($line, 68, 7));
            $segundoApellido = trim(substr($line, 94, 8));
            $nombre = trim(substr($line, 120, 48));

            // Convertir a minúsculas con soporte UTF-8
            $primerApellido = mb_strtolower($primerApellido, 'UTF-8');
            $segundoApellido = mb_strtolower($segundoApellido, 'UTF-8');
            $nombre = mb_strtolower($nombre, 'UTF-8');

            $primerApellido = preg_replace('/\s+/', ' ', trim($primerApellido));
            $segundoApellido = preg_replace('/\s+/', ' ', trim($segundoApellido));
            $nombre = preg_replace('/\s+/', ' ', trim($nombre));

            try {
                $fechaNacimiento = Carbon::createFromFormat('Ymd', $fechaNacimientoRaw)->format('Y-m-d');
            } catch (\Exception $e) {
                $fechaNacimiento = null; // o manejar el error como prefieras
            }

            // toString
            $this->line("[$count] $cedula | $fechaNacimiento | $primerApellido | $segundoApellido | $nombre");

            //Guardar en bd
            PadronNacimiento::create([
                'identificacion' => $cedula,
                'primer_apellido' => $primerApellido,
                'segundo_apellido' => $segundoApellido,
                'nombre' => $nombre,
                'fecha_nacimiento' => $fechaNacimiento,
            ]);

            // Para pruebas, limitar líneas:
            // if ($count >= 20) break;
        }

        fclose($handle);
        $this->info("Procesamiento finalizado. Total líneas: {$count}");
        return 0;
    }
}
