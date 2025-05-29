<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InscripcionIndividualController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\PagosController;


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['role:admin,inscripciones'])->name('dashboard');
    Route::get('/mis_inscripciones', [InscripcionIndividualController::class, 'get_mis_inscripciones'])->name('inscripciones');
    Route::get('/mis_equipos', [DashboardController::class, 'mis_equipos'])->name('mis_equipos');

    Route::get('/participantes', [DashboardController::class, 'list_participantes'])
        ->middleware('role:admin')
        ->name('participantes');

    Route::post('/report_participantes_by_game', [ReportesController::class, 'report_participantes_by_game'])
        ->middleware('role:admin');

    Route::post('/update_pago', [PagosController::class, 'update_pago'])
        ->middleware('role:admin');

    Route::get('/mis_inscripciones', [InscripcionIndividualController::class, 'get_mis_inscripciones'])
        ->name('inscripciones');

    Route::get('/mis_equipos', [DashboardController::class, 'mis_equipos'])
        ->name('mis_equipos');
});