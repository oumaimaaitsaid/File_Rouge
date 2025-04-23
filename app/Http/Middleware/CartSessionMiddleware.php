<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CartSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Vérifier s'il y a un cookie de session de panier à définir
        if ($request->attributes->has('set_cart_cookie')) {
            $sessionId = $request->attributes->get('set_cart_cookie');
            $response->cookie('cart_session_id', $sessionId, 60 * 24 * 30); // 30 jours
        }
        
        return $response;
    }}