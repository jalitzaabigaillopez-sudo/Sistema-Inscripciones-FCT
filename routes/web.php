<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\DBController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AcademiaController;
use App\Http\Controllers\AtletasController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\GradosController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\ModalidadesController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PadronNacimientoController;
use App\Http\Controllers\UsuariosController;
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
Route::get('/pre_registroAcademia', [DBController::class, 'pre_registroAcademia']);
Route::get('/pre_registroAcademia1', [DBController::class, 'pre_registroAcademia1']);
Route::get('/prueba', function () {
    return view('prueba');
})->name('prueba');


//####################################### SOLO ACADEMIA #################################################
/**
 * Ruta para completar pre-registro
 */
Route::get('/activar-cuenta/{id}', [AcademiaController::class, 'vista_activarCuenta'])->name('activar.cuenta');
Route::post('/activar', [AcademiaController::class, 'activarCuenta'])->name('cuentaAcademia.process');


//prueba frontend

// Ruta principal del dashboard
Route::get('/adminDash', function () {
    return view('admin.dashboard');
})->name('adminDash');



// Ruta para perfil
Route::get('/perfil', function () {
    return view('admin.perfil');
})->name('perfil');



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

// Vista de inscripción desde el dashboard
Route::view('/academias/inscripcion', 'academia.inscripcionEvento')->name('academias.inscripcion');





//####################################### SOLO ADMINISTRADOR ###########################################
// Dashboard principal
Route::view('/adminDash', 'admin.dashboard')->name('adminDash');

// Catálogos generales

Route::resource('/academias', AcademiaController::class);

Route::resource('/atletas', AtletasController::class);
Route::get('/atletas/{id}/datos', [AtletasController::class, 'datosAtleta']);

Route::resource('/categorias', CategoriaController::class);

Route::resource('/eventos', EventosController::class);

Route::resource('/usuarios', UsuariosController::class);

Route::resource('/grados', GradosController::class);

Route::resource('/modalidades', ModalidadesController::class);

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


