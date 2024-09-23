<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // On laisse passer la requête au prochain middleware
        $response = $next($request);

        // \App\Http\Middleware\CorsMiddleware::class,
        // Ajouter les en-têtes CORS à la réponse
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept, X-Requested-With');

        // Si la méthode est OPTIONS, renvoyer une réponse vide avec les en-têtes CORS
        if ($request->getMethod() === "OPTIONS")
        {
            return response()->json('{"method":"OPTIONS"}', 200, $response->headers->all());
        }

        return $response;
    
    }
}
