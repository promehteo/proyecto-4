<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('usuarios.ver');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('usuarios.crear');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('usuarios.editar');
    }

    public function activate(User $user, User $model): bool
    {
        return $user->hasPermissionTo('usuarios.activar');
    }

    public function deactivate(User $user, User $model): bool
    {
        return $user->hasPermissionTo('usuarios.inactivar');
    }

    public function assignRoles(User $user, User $model): bool
    {
        return $user->hasPermissionTo('usuarios.asignar_roles');
    }
}
