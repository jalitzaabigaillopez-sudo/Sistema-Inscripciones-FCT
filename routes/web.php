<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\DBController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AcademiaController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PadronNacimientoController;

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
 * Rutas para pruebas all
 */
Route::get('/insertUser', [DBController::class, 'insertUser']);
Route::get('/selectUser', [DBController::class, 'selectUser']);
Route::get('/pre_registroAcademia', [DBController::class, 'pre_registroAcademia']);
Route::get('/pre_registroAcademia1', [DBController::class, 'pre_registroAcademia1']);
Route::get('/prueba', function () {
    return view('prueba');
});

Route::get('/pruebaInscripciones', function () {
    return view('pruebaInscripciones');
});

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


//####################################### SOLO ADMINISTRADOR ###########################################
// Dashboard principal
Route::view('/adminDash', 'admin.dashboard')->name('adminDash');

// Catálogos generales
Route::view('/catalogos/academias', 'catalogos.academias.index')->name('academias.index');
Route::view('/catalogos/academias/create', 'catalogos.academias.create')->name('academias.create');
Route::view('/catalogos/academias/edit', 'catalogos.academias.edit')->name('academias.edit');
Route::view('/catalogos/academias/show', 'catalogos.academias.show')->name('academias.show');

Route::view('/catalogos/atletas', 'catalogos.atletas.index')->name('atletas.index');
Route::view('/catalogos/atletas/create', 'catalogos.atletas.create')->name('atletas.create');
Route::view('/catalogos/atletas/edit', 'catalogos.atletas.edit')->name('atletas.edit');
Route::view('/catalogos/atletas/show', 'catalogos.atletas.show')->name('atletas.show');

Route::view('/catalogos/categorias', 'catalogos.categorias.index')->name('categorias.index');
Route::view('/catalogos/categorias/create', 'catalogos.categorias.create')->name('categorias.create');
Route::view('/catalogos/categorias/edit', 'catalogos.categorias.edit')->name('categorias.edit');
Route::view('/catalogos/categorias/show', 'catalogos.categorias.show')->name('categorias.show');

Route::view('/catalogos/torneos', 'catalogos.torneos.index')->name('torneos.index');
Route::view('/catalogos/torneos/create', 'catalogos.torneos.create')->name('torneos.create');
Route::view('/catalogos/torneos/edit', 'catalogos.torneos.edit')->name('torneos.edit');
Route::view('/catalogos/torneos/show', 'catalogos.torneos.show')->name('torneos.show');

Route::view('/catalogos/usuarios', 'catalogos.usuarios.index')->name('usuarios.index');
Route::view('/catalogos/usuarios/create', 'catalogos.usuarios.create')->name('usuarios.create');
Route::view('/catalogos/usuarios/edit', 'catalogos.usuarios.edit')->name('usuarios.edit');
Route::view('/catalogos/usuarios/show', 'catalogos.usuarios.show')->name('usuarios.show');

    Route::view('/catalogos/pesos', 'catalogos.pesos.index')->name('pesos.index');
    Route::view('/catalogos/pesos/create', 'catalogos.pesos.create')->name('pesos.create');
    Route::view('/catalogos/pesos/edit', 'catalogos.pesos.edit')->name('pesos.edit');
    Route::view('/catalogos/pesos/show', 'catalogos.pesos.show')->name('pesos.show');

   Route::view('/catalogos/modalidades', 'catalogos.modalidades.index')->name('modalidades.index');
   Route::view('/catalogos/modalidades/create', 'catalogos.modalidades.create')->name('modalidades.create');
   Route::view('/catalogos/modalidades/edit', 'catalogos.modalidades.edit')->name('modalidades.edit');
   Route::view('/catalogos/modalidades/show', 'catalogos.modalidades.show')->name('modalidades.show');


   Route::view('/catalogos/inscripciones', 'catalogos.inscripciones.index')->name('inscripciones.index');
   Route::view('/catalogos/inscripciones/create', 'catalogos.inscripciones.create')->name('inscripciones.create');
   Route::view('/catalogos/inscripciones/edit', 'catalogos.inscripciones.edit')->name('inscripciones.edit');
   Route::view('/catalogos/inscripciones/show', 'catalogos.inscripciones.show')->name('inscripciones.show');

   
Route::post('/admin/profile/update', function () {
    // Lógica para actualizar el perfil del admin                               
    $data = request()->all();
    // Actualiza el perfil según tu lógica
    return redirect()->route('perfil')->with('success', 'Perfil actualizado correctamente.');
})->name('admin.profile.update');



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

Route::post('/buscar-datos', [PadronNacimientoController::class, 'buscarPersona']);