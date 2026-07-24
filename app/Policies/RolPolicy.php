<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Rol;
use App\Models\User;

class RolPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('roles.ver');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('roles.crear');
    }

    public function update(User $user, Rol $rol): bool
    {
        return $user->hasPermissionTo('roles.editar');
    }

    public function activate(User $user, Rol $rol): bool
    {
        return $user->hasPermissionTo('roles.activar');
    }

    public function deactivate(User $user, Rol $rol): bool
    {
        return $user->hasPermissionTo('roles.inactivar');
    }

    public function assignPermissions(User $user, Rol $rol): bool
    {
        return $user->hasPermissionTo('roles.asignar_permisos');
    }
}
