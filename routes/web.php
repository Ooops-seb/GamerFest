<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


//Platform Routes
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('another', function () {
    return Inertia::render('Another');
})->middleware(['auth', 'verified'])->name('another');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
