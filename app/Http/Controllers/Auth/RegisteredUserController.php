<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming API registration request.
     */
    public function store(Request $request): JsonResponse
    {
        // Validación para API (solo los campos que existen en tu tabla 'users')
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Obtener el rol 'customer' (por defecto)
        $defaultRole = Role::where('name', 'customer')->first();
        if (!$defaultRole) {
            return response()->json([
                'message' => 'El rol "customer" no existe. Ejecuta los seeders de roles.'
            ], 500);
        }

        // Crear usuario
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $defaultRole->id,
            'status' => 'y', // activo por defecto
        ]);

        // Disparar evento de verificación de email (opcional)
        event(new Registered($user));

        // Crear token Sanctum
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user->load('role')),
        ], 201);
    }
}