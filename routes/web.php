<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\InscripcionIndividualController;


// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');


//Platform Routes
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('another', function () {
    return Inertia::render('Another');
})->middleware(['auth', 'verified'])->name('another');

Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Route::get('/carrito', [CarritoController::class, 'index'])
//     ->middleware('auth')
//     ->name('carrito');
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito');

Route::post('/equipo_por_juego', [EquipoController::class, 'equipo_por_juego']);

Route::post('/get_inscripciones_by_game', [InscripcionIndividualController::class, 'get_inscripciones_by_game']); 
Route::post('/guardar_all_inscripciones', [\App\Http\Controllers\InscripcionIndividualController::class, 'guardar_all_inscripciones'])->name('inscripciones.store');




require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';