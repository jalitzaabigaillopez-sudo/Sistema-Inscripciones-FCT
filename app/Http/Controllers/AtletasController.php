<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atleta;
use App\Models\PadronNacimiento;

class AtletasController extends Controller
{

     public function index()
    {
        $data = Atleta::all();
        return view('catalogos.atletas.index', compact('data'));
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
    
    public function insertarAtleta(Request $request){

        $validateData = $request->validate([
                'tipo_identificacion' => 'required|string|in:nacional,otro',
                'identificacion' => 'required|string|max:30',
                // 'primer_apellido' => 'required|string|max:255',
                // 'segundo_apellido' => 'required|string|max:255',
                // 'nombre' => 'required|string|max:255',
                'rol' => 'required|string|in:entrenador,asistente. atleta',
                'sexo' => 'required|string|in:Femenino,Masculino',
                // 'fecha_nacimiento' => 'required|date',
                'estado' => 'require|string|in:activo,inactivo',        
                
                //'id_categoria' => 'required|integer',// 
                'id_grado' => 'required|integer',
                // 'id_padron_nacimiento' => 'required|integer',
                'id_academia' => 'required|integer',//id_de academia actual "registrante"
        ]); 

        // Verificar que no exista el atleta
        $atleta = Atleta::where('identificacion', $validateData['identificacion'])->first();
        if ($atleta) {
            return response()->json(['error' => 'Este atleta ya se encuentra registrado'], 401);
        }

        // Verificar que exista la cedula si es nacional
        $padronNacimiento = PadronNacimiento::where('identificacion', $validateData['identificacion'])->first();
        if (!$identificacion) {
            return response()->json(['error' => 'Este numero de cedula no esta registrado'], 401);
        }

        // Asignar categoria
        

        // Crear Atleta
        $atleta = Atleta::create([
        'tipo_identificacion' => $validateData['tipo_identificacion'],
        'identificacion' => $validateData['identificacion'],
        'primer_apellido' => $padronNacimiento->primer_apellido,
        'segundo_apellido' => $padronNacimiento->segundo_apellido,
        'nombre' => $padronNacimiento->nombre,
        'rol' => $validateData['rol'],
        'sexo' => $validateData['sexo'],
        'fecha_nacimiento' => $padronNacimiento->fecha_nacimiento,
        'estado' => $validateData['estado'],

        'id_padron_nacimiento' => $padronNacimiento->id_padron_nacimiento,
        'id_grado' => $validateData['id_grado'],
        'id_academia' => $validateData['id_academia'],
        ]);
        $atleta->save();
    }

     public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
