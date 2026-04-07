<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Lista de usuarios con filtros de búsqueda
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));
        $role = $request->query('role', 'all');
        $status = $request->query('status', 'all');
        $roles = Role::query()->orderBy('name')->get();

        $users = User::query()
            ->with('role')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%");
                });
            })
            ->when($role !== 'all', function ($query) use ($role) {
                $query->whereHas('role', function ($roleQuery) use ($role) {
                    $roleQuery->where('name', $role);
                });
            })
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('active', $status === 'active' ? 1 : 0);
            })
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
            'role' => $role,
            'status' => $status,
        ]);
    }

    // Vista de detalle de usuario
    public function show(User $user)
    {
        $user = $user->load('role', 'playlists');

        $previous = User::where('id', '<', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        $next = User::where('id', '>', $user->id)
            ->orderBy('id', 'asc')
            ->first();

        return view('admin.users.show', [
            'user' => $user,
            'previous' => $previous,
            'next' => $next,
        ]);
    }

    // Formulario de edición de usuario
    public function edit(User $user)
    {
        $roles = Role::query()
            ->whereIn('name', ['admin', 'user']) // Roles editables
            ->orderBy('name')
            ->get();

        return view('admin.users.edit', [
            'user' => $user->load('role'),
            'roles' => $roles,
        ]);
    }

    // Actualización de datos y rol
    public function update(Request $request, User $user)
    {
        $editableRoleIds = Role::query()
            ->whereIn('name', ['admin', 'user'])
            ->pluck('id')
            ->all();

        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'country' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'role_id' => ['required', Rule::in($editableRoleIds)],
            'active' => ['required', Rule::in([0,1])],
        ]);

        $user->update($data);

        return redirect()
            ->route('admin.users.edit', $user->id)
            ->with('status', 'Usuario actualizado.');
    }

    // Desactivar usuario rápidamente desde el listado
    public function deactivate(User $user)
    {
        $user->update([
            'active' => 0,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario desactivado.');
    }

    // Activar usuario rápidamente desde el listado
    public function activate(User $user)
    {
        $user->update([
            'active' => 1,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario activado.');
    }
}