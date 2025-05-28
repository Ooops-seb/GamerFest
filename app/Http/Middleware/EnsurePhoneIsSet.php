<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePhoneIsSet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        // Solo aplica la verificación de phone si el usuario está autenticado Y la ruta está dentro del grupo 'auth'
        // Esto se puede lograr verificando si la ruta requiere autenticación
        if ($user && (is_null($user->phone) || $user->phone === '')) {
            $currentRoute = $request->route()?->getName();
            // Solo aplica en rutas protegidas (auth middleware)
            if ($request->route()?->middleware() && in_array('auth', $request->route()->middleware())) {
                if (!in_array($currentRoute, ['phone.prompt', 'phone.update'])) {
                    return redirect()->route('phone.prompt');
                }
            }
        }
        return $next($request);
    }
}
