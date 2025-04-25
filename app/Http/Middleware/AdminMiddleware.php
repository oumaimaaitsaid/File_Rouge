<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ne redirigez PAS vers des routes admin si l'utilisateur n'est pas admin
        if (!Auth::check() || Auth::user()->isAdmin()) {
            
            return $next($request);
        }
        return redirect()->route('home')->with('error', 'Accès non autorisé.');
    }
}