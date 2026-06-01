<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        // Solo verificar si hay usuario autenticado
        $user = $request->user();
        if ($user && $user->status === 'n') {
            return response()->json([
                'message' => 'Your account has been deactivated. Please contact support.'
            ], 403);
        }
        return $next($request);
    }
}
