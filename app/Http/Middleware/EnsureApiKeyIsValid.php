<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKeyIsValid extends Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        /** @var Request $request */
        $guards = empty($guards) ? ['sanctum'] : $guards;

        try {
            // Primero, intenta la autenticación por guard (Sanctum por defecto)
            $this->authenticate($request, $guards);
            return $next($request);
        } catch (AuthenticationException) {
            // Fallback: valida el header API-KEY y suplanta al admin si es válido
            if ($this->authenticateUsingApiKey($request)) {
                return $next($request);
            }

            return response()->json([
                'message' => 'No autorizado. Proporciona un token Sanctum válido o una API-KEY.',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Try to replace the authenticated user with the admin when a valid API key is provided.
     */
    protected function authenticateUsingApiKey(Request $request): bool
    {
        // Usa la clave de la app como API key esperada
        $expectedKey = config('app.key');
        $providedKey = $request->header('API-KEY');

        // Comprobaciones básicas antes de comparar
        if (!is_string($expectedKey) || $expectedKey === '') {
            return false;
        }
        if (!is_string($providedKey) || $providedKey === '') {
            return false;
        }

        // Comparación segura ante timing attacks
        if (!hash_equals($expectedKey, $providedKey)) {
            return false;
        }

        // Resuelve el usuario administrador (primero con rol 'admin')
        $adminUser = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->first();

        if (!$adminUser) {
            return false;
        }

        // Inyecta el usuario en el contexto de request/auth
        $request->setUserResolver(fn () => $adminUser);
        Auth::shouldUse('sanctum');
        Auth::setUser($adminUser);

        return true;
    }
}