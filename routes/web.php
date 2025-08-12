<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\DBController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('sections/login');
});

/**
 * Rutas de login
 */
Route::post('/login-process', [AuthController::class, 'verificarUsuario'])->name('login.process');// verificar credenciales

/**
 * Rutas para pruebas
 */
Route::get('/insert', [DBController::class, 'insert']);
Route::get('/select', [DBController::class, 'select']);
