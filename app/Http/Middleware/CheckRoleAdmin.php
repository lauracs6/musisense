<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si no hay usuario autenticado o no tiene rol de administrador, devolvemos una respuesta 403 (Forbidden)
        if (! $user || ! $user->isAdmin()) {
            return response()->json(['message' => 'Acceso solo para administradores!'], 403);
        }

        // Si el usuario es admin, la petición continúa hacia el controlador
        return $next($request);
    }
}
