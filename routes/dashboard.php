<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InscripcionIndividualController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\PagosController;


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware(['role:admin,inscripciones'])
        ->name('dashboard');
    Route::get('/mis_inscripciones', [InscripcionIndividualController::class, 'get_mis_inscripciones'])->name('inscripciones');
    Route::get('/mis_equipos', [DashboardController::class, 'mis_equipos'])->name('mis_equipos');

    Route::get('/reportes', [DashboardController::class, 'list_reportes'])
        ->middleware('role:admin')
        ->name('reportes');

    Route::post('/report_participantes_by_game', [ReportesController::class, 'report_participantes_by_game'])
        ->middleware('role:admin');

    Route::post('/update_pago', [PagosController::class, 'update_pago'])
        ->middleware('role:admin');

    Route::get('/mis_inscripciones', [InscripcionIndividualController::class, 'get_mis_inscripciones'])
        ->name('inscripciones');

    Route::get('/mis_equipos', [DashboardController::class, 'mis_equipos'])
        ->name('mis_equipos');

    Route::get('/inscripciones', [DashboardController::class, 'inscripciones'])
        ->middleware('role:admin')
        ->name('inscripciones.dashboard');

    Route::post('/inscripciones/actualizar-estado', [DashboardController::class, 'actualizarEstado'])
        ->middleware('role:admin');
});