<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Categoria;
use App\Models\User;

class CategoriaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('categorias.ver');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('categorias.crear');
    }

    public function update(User $user, Categoria $categoria): bool
    {
        return $user->hasPermissionTo('categorias.editar');
    }

    public function activate(User $user, Categoria $categoria): bool
    {
        return $user->hasPermissionTo('categorias.activar');
    }

    public function deactivate(User $user, Categoria $categoria): bool
    {
        return $user->hasPermissionTo('categorias.inactivar');
    }
}
