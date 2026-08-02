<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Permiso;
use App\Models\User;

class PermisoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(Permission::VER_PERMISOS);
    }

    public function view(User $user, Permiso $permiso): bool
    {
        return $user->hasPermission(Permission::VER_PERMISOS);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission(Permission::CREAR_PERMISOS);
    }

    public function update(User $user, Permiso $permiso): bool
    {
        return $user->hasPermission(Permission::EDITAR_PERMISOS);
    }

    public function activate(User $user, Permiso $permiso): bool
    {
        return $user->hasPermission(Permission::ACTIVAR_PERMISOS);
    }

    public function deactivate(User $user, Permiso $permiso): bool
    {
        return $user->hasPermission(Permission::INACTIVAR_PERMISOS);
    }
}
