<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Index de usuarios, con paginación y ordenados por ID
    public function index()
    {
        $users = User::with('role')->orderBy('id')->get();
        return UserResource::collection($users);
    }

    // Show de un usuario específico, cargando su rol
    public function show(User $user): UserResource
    {
        $user->load('role');
        return new UserResource($user);
    }

    // Actualizar un usuario, validando los datos y normalizando el email
    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $data = $request->validated();

        if (array_key_exists('username', $data)) {
            $data['username'] = $data['username'];
        }
        if (array_key_exists('email', $data)) {
            $data['email'] = mb_strtolower($data['email']);
        }

        $user->update($data);

        return new UserResource($user->fresh()->load('role'));
    }

    // Eliminar un usuario, desactivándolo y eliminando sus tokens en una transacción
    public function destroy(UserDestroyRequest $request, User $user): JsonResponse
    {
        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->update(['status' => 'n']);
        });

        return response()->json(['message' => 'User deactivated']);
    }

    // Actualizar la contraseña de un usuario, validando la nueva contraseña y hasheándola
    public function updatePassword(UserPasswordUpdateRequest $request): JsonResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return response()->json(['message' => 'Password updated']);
    }
}
