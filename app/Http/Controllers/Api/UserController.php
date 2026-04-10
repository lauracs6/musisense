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
    /**
     * Listado de usuarios (solo admin).
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('role')->orderBy('id')->get();
        return UserResource::collection($users);
    }

    /**
     * Mostrar un usuario concreto.
     */
    public function show(User $user): UserResource
    {
        $this->authorize('view', $user);
        $user->load('role');
        return new UserResource($user);
    }

    /**
     * Actualizar un usuario.
     */
    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $this->authorize('update', $user);

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

    /**
     * Desactivar usuario (soft delete lógico con status='n').
     */
    public function destroy(UserDestroyRequest $request, User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->update(['status' => 'n']);
        });

        return response()->json(['message' => 'Usuario desactivado']);
    }

    /**
     * Actualizar la contraseña del usuario autenticado.
     */
    public function updatePassword(UserPasswordUpdateRequest $request): JsonResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return response()->json(['message' => 'Contraseña actualizada']);
    }
}
