<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Permiso;
use App\Models\User;

class PermisoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('permisos.ver');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('permisos.crear');
    }

    public function update(User $user, Permiso $permiso): bool
    {
        return $user->hasPermissionTo('permisos.editar');
    }

    public function activate(User $user, Permiso $permiso): bool
    {
        return $user->hasPermissionTo('permisos.activar');
    }

    public function deactivate(User $user, Permiso $permiso): bool
    {
        return $user->hasPermissionTo('permisos.inactivar');
    }
}
