<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(Permission::VER_USUARIOS);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission(Permission::CREAR_USUARIOS);
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermission(Permission::EDITAR_USUARIOS);
    }

    public function activate(User $user, User $model): bool
    {
        return $user->hasPermission(Permission::ACTIVAR_USUARIOS);
    }

    public function deactivate(User $user, User $model): bool
    {
        return $user->hasPermission(Permission::INACTIVAR_USUARIOS);
    }

    public function assignRoles(User $user, User $model): bool
    {
        return $user->hasPermission(Permission::ASIGNAR_ROLES);
    }
}
