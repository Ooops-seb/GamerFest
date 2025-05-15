<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InscripcionIndividualController;
use App\Http\Controllers\DashboardController;


Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['role:admin,inscripciones'])->name('dashboard');
    Route::get('/mis_inscripciones', [InscripcionIndividualController::class, 'get_mis_inscripciones'])->name('inscripciones');
    Route::get('/mis_equipos', [DashboardController::class, 'mis_equipos'])->name('mis_equipos');
});