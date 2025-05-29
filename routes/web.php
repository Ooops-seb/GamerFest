<?php

use App\Http\Controllers\AdModelController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\InscripcionIndividualController;
use App\Http\Controllers\ReportesController;



Route::get('/', [WelcomeController::class, 'index'])->name('home');

//Platform Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito');
});



// EQUIPO
Route::post('/equipo_por_juego', [EquipoController::class, 'equipo_por_juego']);
Route::get('/equipos', [EquipoController::class, 'index']);
Route::get('/equipos/{id}', [EquipoController::class, 'show']);
Route::get('/equipos/{id}/miembros', [EquipoController::class, 'getMiembros']);
Route::post('/equipos', [EquipoController::class, 'store']);
Route::post('/equipo_por_juego', [EquipoController::class, 'equipo_por_juego']);
Route::delete('/equipos/{id}', [EquipoController::class, 'destroy']);
Route::put('/equipos/{id}', [EquipoController::class, 'update']);


Route::post('/get_inscripciones_by_game', [InscripcionIndividualController::class, 'get_inscripciones_by_game']);
Route::post('/report_participantes_by_game', [ReportesController::class, 'report_participantes_by_game']); 
Route::post('/guardar_all_inscripciones', [InscripcionIndividualController::class, 'guardar_all_inscripciones'])->name('inscripciones.store');

Route::get('/ads', [AdModelController::class, 'getAds']);
// Route::get('/ads', [AdModelController::class, 'index'])->name('ads.index');

require __DIR__ . '/dashboard.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/api.php';