<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DBController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AcademiaController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PadronNacimientoController;
use App\Http\Controllers\AtletasController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\SubModalidadController;
use App\Http\Controllers\ModalidadController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\GradosController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ModalidadesController;
use App\Http\Controllers\UsuariosController;

use App\Http\Controllers\RegistroAtletasController;
use Illuminate\Types\Relations\Role;


//####################################### SOLO ADMINISTRADOR ###########################################
/**
 * Rutas de login
 */
Route::get('/', function () {
    return view('sections/login');
})->name('login');
Route::post('/login-process', [AuthController::class, 'verificarUsuario'])->name('login.process');// verificar credenciales
Route::post('/logout-process', [AuthController::class, 'cerrarSesion'])->name('logout.process');// cerrar sesion


/**
 * DashBoard
 */
Route::get('/dashboard', [InicioController::class, 'index'])->name('dashboard');

/**
 * Rutas para pruebas all
 */
Route::get('/insertUser', [DBController::class, 'insertUser']);
Route::get('/selectUser', [DBController::class, 'selectUser']);
Route::post('/pre_registroAcademia', [AcademiaController::class, 'pre_registroAcademia'])->name('pre.registro.academia');


//####################################### SOLO ACADEMIA #######################################################################################
/**
 * =============================================================================================================================================
 * Rutas Perfil
 */
// Route::get('/getProfile', [AcademiaController::class, 'getProfile']);


/**
 * Ruta para completar pre-registro
 */
Route::get('/activar-cuenta/{id}', [AcademiaController::class, 'vista_activarCuenta'])->name('activar.cuenta');
Route::post('/activar', [AcademiaController::class, 'activarCuenta'])->name('cuentaAcademia.process');

/**
 * =============================================================================================================================================
 * Rutas inscripciones
 */
Route::get('/nuevaInscripcion', [InscripcionController::class, 'vistaInscripcionesAcademia'])->name('inscripcion.academia');
Route::get('/misInscripciones', [InscripcionController::class, 'vistaMisInscripcionesAcademia'])->name('misInscripciones');
Route::post('/inscripcionAtleta', [InscripcionController::class, 'inscribirAtleta']);
Route::post('/modificarInscripcionAtleta', [InscripcionController::class, 'modificarInscripcionAtleta']);
Route::post('/eliminarInscripcionAtleta', [InscripcionController::class, 'eliminarInscripcionAtleta']);
Route::get('/editarInscripcion/{id_evento}', [InscripcionController::class, 'editarInscripcion'])->name('editar.inscripcion');
Route::post('/eliminarInscripcion', [InscripcionController::class, 'eliminarInscripcion']);
Route::post('/procesarInscripcion', [InscripcionController::class, 'confirmarInscripcion']);

/**
 * =============================================================================================================================================
 */
Route::post('/obtenerModalidades', [ModalidadController::class, 'obtenerModalidades']);
Route::post('/obtenerSubModalidades', [SubModalidadController::class, 'obtenerSubModalidades']);
Route::post('/obtenerCategorias', [CategoriaController::class, 'obtenerCategorias']);

/**
 * =============================================================================================================================================
 * Rutas atletas
 */
Route::get('/atletas-academia', [AtletasController::class, 'indexAtltetasAcademia'])->name('gestion.atletas');

//==============================================================================================================================================


//prueba frontend

// Ruta principal del dashboard
Route::get('/adminDash', function () {
    return view('admin.dashboard');
})->name('adminDash');



// Ruta para perfil
// Route::get('/perfil', function () {
//     return view('admin.perfil');
// })->name('perfil');

Route::get('/perfil', [AuthController::class, 'perfil'])->name('perfil');
Route::put('/perfil/{id}', [AuthController::class, 'actualizarPerfil'])->name('perfil.update');

// Route::get('/perfilAcademia', [AuthController::class, 'perfilAcademia'])->name('perfilAcademia');
// Route::put('/perfilAcademia/{id}', [AuthController::class, 'actualizarPerfil'])->name('perfil.update');


// Ruta para estadísticas
Route::get('/estadisticas', function () {
    return view('estadisticas');
})->name('estadisticas');



// Ruta para ranking nacional
Route::get('/ranking', function () {
    return view('ranking');
})->name('ranking');



// Dashboard de academias
Route::view('/dashboard-academias', 'academia.dashboard-academia')->name('dashboard.academias');
// Inscripción a eventos
Route::view('/inscripcion-eventos', 'academia.inscripcionEvento')->name('academia.inscripcionEvento');
// perfil de academia
Route::view('/perfil-academia', 'academia.perfilAcademia')->name('academia.perfil-academia');
//mis inscripciones
Route::view('/mis-inscripciones', 'academia.misInscripciones')->name('academia.misInscripciones');
//registro de atletas


Route::resource('/registro-atletas', RegistroAtletasController::class);

Route::post('/preregistro-academia', [AcademiaController::class, 'pre_registroAcademia'])->name('academia.preregistro.process');
//####################################### SOLO ADMINISTRADOR ###########################################
// Dashboard principal
Route::view('/adminDash', 'admin.dashboard')->name('adminDash');

//Editar perfil
// Route::get('/perfil', function () {
//     return view('admin.perfil-admin');
// })->name('perfil');

// Catálogos generales
Route::resource('/academias', AcademiaController::class);

Route::get('/cantones/{provinciaId}', [AcademiaController::class, 'getCantones'])->name('cantones.get');
Route::get('/distritos/{cantonId}', [AcademiaController::class, 'getDistritos'])->name('distritos.get');

Route::get('/atletas', [AtletasController::class, 'index'])->name('atletas.index');
Route::resource('/atletas', AtletasController::class);
Route::get('/atletas/{id}/datos', [AtletasController::class, 'datosAtleta']);
Route::get('/buscar-padron/{identificacion}', [AtletasController::class, 'buscarPadron']);
// Calcular división según fecha
Route::get('/calcular-division/{fecha}', [AtletasController::class, 'calcularDivision']);

Route::resource('/submodalidades', SubModalidadController::class);

/**
 * 
 */
Route::get('/adminEditarInscripcion/{id_evento}/{id_academia}', [InscripcionController::class, 'administradorEditarInscripcion'])->name('admin.editar.inscripcion');
Route::get('/adminEliminarInscripcion/{id_evento}/{id_academia}', [InscripcionController::class, 'administradorEliminarInscripcion'])->name('admin.eliminar.inscripcion');
Route::post('/adminInscripcionAtleta', [InscripcionController::class, 'AdministradorInscribirAtleta']);


Route::resource('/categorias', CategoriaController::class);
Route::get('/categorias/{id}/datos', [CategoriaController::class, 'edit']);


Route::resource('/eventos', EventosController::class);

Route::resource('/usuarios', UsuariosController::class);

Route::resource('/grados', GradosController::class);
Route::get('/grados/{id}/datos', [GradosController::class, 'edit']);

Route::resource('/modalidades', ModalidadesController::class);
Route::get('/modalidades/{id}/datos', [ModalidadesController::class, 'edit']);


Route::resource('/inscripciones', InscripcionController::class);



Route::post('/admin/profile/update', function () {
    // Lógica para actualizar el perfil del admin                               
    $data = request()->all();
    // Actualiza el perfil según tu lógica
    return redirect()->route('perfil')->with('success', 'Perfil actualizado correctamente.');
})->name('admin.profile.update');


// Vista de prueba restablecer contra
Route::get('/restablecerContrasena', function () {
    return view('academia.restablecerContrasena');
})->name('restablecerContraseña');



//####################################### AMBOS ROLES #################################################
/**
 * Rutas cambio de contraseña
 */

Route::get('/cambiar-contraseña/{id}', [PasswordController::class, 'vistaCambiarContraseña'])->name('vista.cambiarContraseña')->middleware('signed');
Route::get('/cambiar-contraseña/{id}', [PasswordController::class, 'vistaCambiarContraseña'])->name('vista.cambiarContraseña');
Route::post('/cambiar-contraseña', [PasswordController::class, 'cambiarContraseña'])->name('cambiar.contraseña');

Route::post('/recuperar-contraseña', [PasswordController::class, 'correoCambiarContraseña'])->name('correo.cambiarContraseña');

Route::get('/cambiar-contraseña-vencida/{id}', [PasswordController::class, 'vistaCambiarContraseñaVencida'])->name('vista.cambiarContraseñaVencida')->middleware('signed');
Route::post('/cambiar-contraseña-vencida', [PasswordController::class, 'cambiarContraseñaVencida'])->name('cambiar.contraseñaVencida');

Route::post('/buscar-datos', [PadronNacimientoController::class, 'buscarPersona']);

/**
 * Rutas dashboard
 */
Route::get('/preregistro', function () {
    return view('sections/preregistro');
})->name('preregistro');

/**
 * Rutas API
 */

Route::get('/events', [EventosController::class, 'api']);    


