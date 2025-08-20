<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\DBController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AcademiaController;
use App\Http\Controllers\PasswordController;

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
Route::get('/dashboard', [Controller::class, 'index'])->name('dashboard');

/**
 * Rutas para pruebas
 */
Route::get('/insertUser', [DBController::class, 'insertUser']);
Route::get('/selectUser', [DBController::class, 'selectUser']);
Route::get('/pre_registroAcademia', [DBController::class, 'pre_registroAcademia']);
Route::get('/pre_registroAcademia1', [DBController::class, 'pre_registroAcademia1']);

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

// Ruta para inscripción
Route::get('/inscripcion', function () {
    return view('admin.inscripcion');
})->name('inscripcion');

// Ruta para perfil
Route::get('/perfil', function () {
    return view('admin.perfil');
})->name('perfil');


// Ruta para eventos
Route::get('/eventos', function () {
    return view('eventos');
})->name('eventos');

// Ruta para estadísticas
Route::get('/estadisticas', function () {
    return view('estadisticas');
})->name('estadisticas');

// Ruta para verificación de peso
Route::get('/peso', function () {
    return view('peso');
})->name('peso');

// Ruta para ranking nacional
Route::get('/ranking', function () {
    return view('ranking');
})->name('ranking');

// Ruta para catálogos generales
Route::get('/catalogos', function () {
    return view('admin.catalogos');
})->name('catalogos');

Route::get('/dashboard-academias', function () {
    return view('academia.dashboard-academia');
})->name('dashboard.academias');

// Vista de catálogo de atletas
Route::get('/atletas', function () {
    return view('academia.atletas');
})->name('atletas');

// Vista de perfil de academia
Route::get('/perfil-academia', function () {
    return view('academia.perfil-academia');
})->name('perfil.academia');

// Vista de prueba restablecer contra
Route::get('/restablecerContrasena', function () {
    return view('academia.restablecerContrasena');
})->name('restablecerContraseña');


//####################################### AMBOS ROLES #################################################
/**
 * Rutas cambio de contraseña
 */

// Route::get('/cambiar-contraseña/{id}', [PasswordController::class, 'vistaCambiarContraseña'])->name('vista.cambiarContraseña')->middleware('signed'); 
Route::get('/cambiar-contraseña/{id}', [PasswordController::class, 'vistaCambiarContraseña'])->name('vista.cambiarContraseña');
Route::post('/cambiar-contraseña', [PasswordController::class, 'cambiarContraseña'])->name('cambiar.contraseña');

Route::post('/recuperar-contraseña', [PasswordController::class, 'correoCambiarContraseña'])->name('correo.cambiarContraseña');

Route::get('/cambiar-contraseña-vencida/{id}', [PasswordController::class, 'vistaCambiarContraseñaVencida'])->name('vista.cambiarContraseñaVencida')->middleware('signed');
Route::post('/cambiar-contraseña-vencida', [PasswordController::class, 'cambiarContraseñaVencida'])->name('cambiar.contraseñaVencida');