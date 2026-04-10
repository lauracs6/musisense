<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Listado de usuarios con filtros (solo admin).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $search = trim($request->query('q', ''));
        $role = $request->query('role', 'all');
        $status = $request->query('status', 'all');

        $users = User::query()
            ->with('role')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role !== 'all', function ($query) use ($role) {
                $query->whereHas('role', fn($q) => $q->where('name', $role));
            })
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status === 'active' ? 'y' : 'n');
            })
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return UserResource::collection($users);
    }

    /**
     * Mostrar un usuario específico.
     */
    public function show(User $user): UserResource
    {
        $user->load('role');
        return new UserResource($user);
    }

    /**
     * Actualizar un usuario (solo admin).
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'username' => ['sometimes', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => ['sometimes', 'exists:roles,id'],
            'status' => ['sometimes', Rule::in(['y', 'n'])],
        ]);

        $user->update($validated);
        $user->load('role');

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Desactivar usuario (cambiar status a 'n').
     */
    public function deactivate(User $user): JsonResponse
    {
        $user->update(['status' => 'n']);
        return response()->json(['message' => 'Usuario desactivado']);
    }

    /**
     * Activar usuario (cambiar status a 'y').
     */
    public function activate(User $user): JsonResponse
    {
        $user->update(['status' => 'y']);
        return response()->json(['message' => 'Usuario activado']);
    }

    /**
     * Eliminar usuario (borrado lógico o físico según necesites).
     * Aquí haremos borrado lógico (status='n') igual que deactivate.
     * Si quieres borrado físico, cambia a $user->delete().
     */
    public function destroy(User $user): JsonResponse
    {
        // Opcional: eliminar tokens antes
        $user->tokens()->delete();
        $user->update(['status' => 'n']);
        return response()->json(['message' => 'Usuario desactivado']);
    }
}