<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PadronNacimiento;
use App\Models\Categoria;

class PadronNacimientoController extends Controller
{
    public function buscarPersona(Request $request)
    {

    $cedula = $request->input('cedula');
    $sexo   = $request->input('sexo');
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

    function categoriaPorNacimiento($year) {
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
