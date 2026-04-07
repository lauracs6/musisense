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
     * Allow access if authenticated via Sanctum or API Key.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? ['sanctum'] : $guards;

        try {
            $this->authenticate($request, $guards);
            return $next($request);

        } catch (AuthenticationException) {

            if ($this->authenticateUsingApiKey($request)) {
                return $next($request);
            }

            return response()->json([
                'message' => 'Unauthorized. Provide a valid Sanctum token or API Key.',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Validate API Key from header
     */
    protected function authenticateUsingApiKey(Request $request): bool
    {
        $expectedKey = config('app.api_key');
        $providedKey = $request->header('API-KEY');

        if (! is_string($expectedKey) || empty($expectedKey)) {
            return false;
        }

        if (! is_string($providedKey) || empty($providedKey)) {
            return false;
        }

        if (! hash_equals($expectedKey, $providedKey)) {
            return false;
        }

        // Obtener usuario admin
        $adminUser = User::whereHas('role', function ($q) {
            $q->where('name', 'admin');
        })->first();

        if (! $adminUser) {
            return false;
        }

        // Autenticar como admin
        $request->setUserResolver(fn () => $adminUser);
        Auth::shouldUse('sanctum');
        Auth::setUser($adminUser);

        return true;
    }
}