<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PhonePromptController extends Controller
{
    /**
     * Show the phone prompt page.
     */
    public function show(Request $request): Response|Redirect
    {
        // Si el usuario ya tiene phone, redirige al dashboard
        if ($request->user() && $request->user()->phone) {
            return redirect()->route('dashboard');
        }
        return Inertia::render('auth/PromptPhone', [
            'status' => $request->session()->get('status'),
        ]);
    }
}
