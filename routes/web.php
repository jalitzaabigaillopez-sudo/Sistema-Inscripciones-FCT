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

//####################################### SOLO ACADEMIA #################################################
/**
 * Ruta para completar pre-registro
 */
Route::get('/activar-cuenta/{id}', [AcademiaController::class, 'vista_activarCuenta'])->name('activar.cuenta')->middleware('signed'); ;
Route::post('/activar', [AcademiaController::class, 'activarCuenta'])->name('cuentaAcademia.process');

//####################################### AMBOS ROLES #################################################
/**
 * Rutas cambio de contraseña
 */

Route::get('/cambiar-contraseña/{id}', [PasswordController::class, 'vistaCambiarContraseña'])->name('vista.cambiarContraseña')->middleware('signed'); 
Route::post('/cambiar-contraseña', [PasswordController::class, 'cambiarContraseña'])->name('cambiar.contraseña');

Route::post('/recuperar-contraseña', [PasswordController::class, 'correoCambiarContraseña'])->name('correo.cambiarContraseña');