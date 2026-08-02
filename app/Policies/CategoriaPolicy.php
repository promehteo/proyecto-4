<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Categoria;
use App\Models\User;

class CategoriaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(Permission::VER_CATEGORIAS);
    }

    public function view(User $user, Categoria $categoria): bool
    {
        return $user->hasPermission(Permission::VER_CATEGORIAS);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission(Permission::CREAR_CATEGORIAS);
    }

    public function update(User $user, Categoria $categoria): bool
    {
        return $user->hasPermission(Permission::EDITAR_CATEGORIAS);
    }

    public function activate(User $user, Categoria $categoria): bool
    {
        return $user->hasPermission(Permission::ACTIVAR_CATEGORIAS);
    }

    public function deactivate(User $user, Categoria $categoria): bool
    {
        return $user->hasPermission(Permission::INACTIVAR_CATEGORIAS);
    }
}
