<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PadronNacimiento;
use App\Models\Categoria;
use App\Services\SessionService;

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
        return view('admin/padronNacimientos');
    }

    public function subirArchivo(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimetypes:text/plain|max:3072000', // 3 GB
        ]);

        $archivo = $request->file('archivo');

        // Carpeta simulando un servidor FTP
        $rutaDestino = public_path('ftp_uploads');

        if (!file_exists($rutaDestino)) {
            mkdir($rutaDestino, 0777, true);
        }

        // Mover el archivo (sin usar move() si es grande)
        $stream = fopen($archivo->getRealPath(), 'r+');
        $destino = fopen($rutaDestino . '/' . $archivo->getClientOriginalName(), 'w+');

        stream_copy_to_stream($stream, $destino);
        fclose($stream);
        fclose($destino);

        return "Archivo cargado exitosamente (simulación FTP)";
    }

    function procesarPadron()
    {
        $path = storage_path('app/private/archivos_txt/MAESTRO_NACIMIENTOS_PLANO_SEPTIEMBRE 2025.txt'); 

        if (!file_exists($path)) {
            throw new \Exception("El archivo no existe: $path");
        }

        $handle = fopen($path, 'r');

        if (!$handle) {
            throw new \Exception("No se pudo abrir el archivo.");
        }

        $lineNumber = 0;

        while (($line = fgets($handle)) !== false) {
            $lineNumber++;

            // Asegura longitud mínima
            if (strlen($line) < 9) {
                continue;
            }

            // Extrae los primeros 9 caracteres (cédula)
            $cedula = substr($line, 0, 9);

            // TODO


            // LOG
            echo "Línea {$lineNumber}: {$cedula}\n";
        }

        fclose($handle);
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
}
