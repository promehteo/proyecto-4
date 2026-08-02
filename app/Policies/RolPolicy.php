<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Rol;
use App\Models\User;

class RolPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(Permission::VER_ROLES);
    }

    public function view(User $user, Rol $rol): bool
    {
        return $user->hasPermission(Permission::VER_ROLES);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission(Permission::CREAR_ROLES);
    }

    public function update(User $user, Rol $rol): bool
    {
        return $user->hasPermission(Permission::EDITAR_ROLES);
    }

    public function activate(User $user, Rol $rol): bool
    {
        return $user->hasPermission(Permission::ACTIVAR_ROLES);
    }

    public function deactivate(User $user, Rol $rol): bool
    {
        return $user->hasPermission(Permission::INACTIVAR_ROLES);
    }

    public function assignPermissions(User $user, Rol $rol): bool
    {
        return $user->hasPermission(Permission::ASIGNAR_PERMISOS);
    }
}
