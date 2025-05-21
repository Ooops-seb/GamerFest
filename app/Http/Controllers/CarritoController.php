<?php

namespace App\Http\Controllers;

use App\Models\AdModel;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CarritoController extends Controller
{
    public function index()
    {
        $ads = AdModel::all();

        return Inertia::render('Carrito', [
            'role' => Auth::user()->hasRole("admin"),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'ads' => $ads,
        ]);
    }
}
