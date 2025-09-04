<?php

namespace App\Http\Controllers;

use App\Models\Academia;
use Illuminate\Http\Request;
use App\Models\Atleta;
use App\Models\Categoria;
use App\Models\Grado;
use App\Models\PadronNacimiento;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AtletasController extends Controller
{

    public function index(Request $request)
    {
        $data = Atleta::all();
        $grados = Grado::all();
        $categorias = Categoria::all();
        $academias = Academia::all();
        return view('catalogos.atletas.index', compact('data', 'grados', 'categorias', 'academias'));
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

    public function insertarAtleta(Request $request)
    {

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
            'id_academia' => 'required|integer', //id_de academia actual "registrante"
        ]);

        // Verificar que no exista el atleta
        $atleta = Atleta::where('identificacion', $validateData['identificacion'])->first();
        if ($atleta) {
            return response()->json(['error' => 'Este atleta ya se encuentra registrado'], 401);
        }

        // Verificar que exista la cedula si es nacional
        $padronNacimiento = PadronNacimiento::where('identificacion', $validateData['identificacion'])->first();
        if (!$padronNacimiento) {
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

    public function obtenerAtletasPorRol(Request $request)
    {
        // nombre(variable) "tipo" viene de ajax
        $tipo = $request->tipo;
        $idAcademia = $request->id_academia;
        $atletas = Atleta::where('rol', $tipo)->where('id_academia', $idAcademia)->get();

        return response()->json($atletas);
    }

    public function datosAtleta($id)
    {
        $atleta = Atleta::with('grados', 'academias', 'categorias')->findOrFail($id);
        return response()->json($atleta);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Atleta::find($id);
        return view('catalogos.atletas.index', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $messages = [
                'tipo_identificacion.required' => 'El tipo de identificación es obligatorio.',
                'identificacion.required' => 'La identificación es obligatoria.',
                'identificacion.string' => 'La identificación debe ser una cadena de texto.',
                'identificacion.max' => 'La identificación no puede tener más de :max caracteres.',
                'identificacion.unique' => 'La identificación ya está en uso.',
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de texto.',
                'nombre.max' => 'El nombre no puede tener más de :max caracteres.',
                'primer_apellido.required' => 'El primer apellido es obligatorio.',
                'primer_apellido.string' => 'El primer apellido debe ser una cadena de texto.',
                'primer_apellido.max' => 'El primer apellido no puede tener más de :max caracteres.',
                'segundo_apellido.string' => 'El segundo apellido debe ser una cadena de texto.',
                'segundo_apellido.max' => 'El segundo apellido no puede tener más de :max caracteres.',
                'rol.required' => 'El rol es obligatorio.',
                'rol.string' => 'El rol debe ser una cadena de texto.',
                'sexo.required' => 'El sexo es obligatorio.',
                'sexo.string' => 'El sexo debe ser una cadena de texto.',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
                'estado.required' => 'El estado es obligatorio.',
                'estado.string' => 'El estado debe ser una cadena de texto.',
                'id_grado.required' => 'El grado es obligatorio.',
                'id_grado.integer' => 'El grado debe ser un número entero.',
                'id_academia.required' => 'La academia es obligatoria.',
                'id_academia.integer' => 'La academia debe ser un número entero.',
                'imagen.image' => 'El archivo debe ser una imagen.',
                'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif.',
                'imagen.max' => 'La imagen no puede pesar más de 10 MB.',
            ];

            $atleta = Atleta::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'tipo_identificacion' => 'required|string',
                'identificacion' => 'required|string|max:30|unique:atletas,identificacion,' . $id . ',id_atleta',
                'nombre' => 'required|string|max:255',
                'primer_apellido' => 'required|string|max:255',
                'segundo_apellido' => 'nullable|string|max:255',
                'rol' => 'required|string',
                'sexo' => 'required|string',
                'fecha_nacimiento' => 'required|date',
                'estado' => 'required|string',
                'id_grado' => 'required|integer',
                'id_academia' => 'required|integer',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
                'remove_imagen' => 'nullable|in:0,1',
            ], $messages);

            if ($validator->fails()) {
                Log::warning('Validación fallida en update: ' . json_encode($validator->errors()));
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update
            $atleta->tipo_identificacion = $request->tipo_identificacion;
            $atleta->identificacion = $request->identificacion;
            $atleta->nombre = $request->nombre;
            $atleta->primer_apellido = $request->primer_apellido;
            $atleta->segundo_apellido = $request->segundo_apellido;
            $atleta->rol = $request->rol;
            $atleta->sexo = $request->sexo;
            $atleta->fecha_nacimiento = $request->fecha_nacimiento;
            $atleta->estado = $request->estado;
            $atleta->id_grado = $request->id_grado;
            $atleta->id_academia = $request->id_academia;

            if ($request->input('remove_imagen') === '1') {
                Log::info('Eliminando imagen del atleta: ' . $atleta->imagen);
                if ($atleta->imagen) {
                    Storage::disk('public')->delete($atleta->imagen);
                    $atleta->imagen = null;
                }
            } elseif ($request->hasFile('imagen')) {
                Log::info('Subiendo nueva imagen en update: ' . $request->file('imagen')->getClientOriginalName());
                if ($atleta->imagen) {
                    Storage::disk('public')->delete($atleta->imagen);
                }
                $path = $request->file('imagen')->store('perfiles', 'public');
                $atleta->imagen = $path;
                Log::info('Imagen guardada en update: ' . $path);
            }

            $atleta->save();

            return response()->json([
                'success' => true,
                'message' => 'Atleta actualizado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error en update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Atleta::find($id);

        $item->delete();

        return back();
    }
}
