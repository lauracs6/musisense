<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Listado de usuarios
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->isAdmin();
    }

    /**
     * Ver un usuario concreto
     */
    public function view(User $authUser, User $user): bool
    {
        // Admin puede ver todos
        if ($authUser->isAdmin()) {
            return true;
        }

        // Usuario puede verse a sí mismo
        return $authUser->id === $user->id;
    }

    /**
     * Crear usuarios (normalmente solo admin)
     */
    public function create(User $authUser): bool
    {
        return $authUser->isAdmin();
    }

    /**
     * Actualizar usuario
     */
    public function update(User $authUser, User $user): bool
    {
        // Admin puede todo
        if ($authUser->isAdmin()) {
            return true;
        }

        // Usuario solo puede actualizarse a sí mismo
        return $authUser->id === $user->id;
    }

    /**
     * Eliminar usuario
     */
    public function delete(User $authUser, User $user): bool
    {
        // SOLO admin (según tu diseño actual)
        return $authUser->isAdmin();
    }

    public function restore(User $authUser, User $user): bool
    {
        return $authUser->isAdmin();
    }

    public function forceDelete(User $authUser, User $user): bool
    {
        return $authUser->isAdmin();
    }
}