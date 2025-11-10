<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

            $cedula = trim(substr($line, 0, 9));
            $fechaNacimiento = trim(substr($line, 37, 8));
            $primerApellido = trim(substr($line, 68, 7));
            $segundoApellido = trim(substr($line, 94, 8));
            $nombre = trim(substr($line, 120, 13));

            // toString
            $this->line("[$count] $cedula | $fechaNacimiento | $primerApellido | $segundoApellido | $nombre");

            // Para pruebas, limitar líneas:
            // if ($count >= 1000) break;
        }

        fclose($handle);
        $this->info("Procesamiento finalizado. Total líneas: {$count}");
        return 0;
    }
}
