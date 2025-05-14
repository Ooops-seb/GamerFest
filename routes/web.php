<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\InscripcionIndividualController;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


//Platform Routes
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito');
});


// EQUIPO
Route::post('/equipo_por_juego', [EquipoController::class, 'equipo_por_juego']);
Route::get('/equipos', [EquipoController::class, 'index']);
// Route::get('/mis_equipos', [DashboardController::class, 'mis_equipos'])->name('mis_equipos');
Route::get('/equipos/{id}', [EquipoController::class, 'show']);
Route::get('/equipos/{id}/miembros', [EquipoController::class, 'getMiembros']);
Route::post('/equipos', [EquipoController::class, 'store']);
Route::post('/equipo_por_juego', [EquipoController::class, 'equipo_por_juego']);
Route::delete('/equipos/{id}', [EquipoController::class, 'destroy']);
Route::put('/equipos/{id}', [EquipoController::class, 'update']);


Route::post('/get_inscripciones_by_game', [InscripcionIndividualController::class, 'get_inscripciones_by_game']);
Route::post('/guardar_all_inscripciones', [InscripcionIndividualController::class, 'guardar_all_inscripciones'])->name('inscripciones.store');
Route::get('/get_mis_inscripciones', [InscripcionIndividualController::class, 'get_mis_inscripciones'])->name('inscripciones');



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';