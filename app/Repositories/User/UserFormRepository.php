<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\Rol;
use App\Models\RolUsuario;
use App\Models\User;
use Illuminate\Support\Collection;

class UserFormRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    public function activate(User $user): User
    {
        $user->update(['status' => 1]);
        return $user->fresh();
    }

    public function deactivate(User $user): User
    {
        $user->update(['status' => 2]);
        return $user->fresh();
    }

    public function getActiveRolesForSelect(): Collection
    {
        return Rol::active()->orderBy('nombre_rol')->get();
    }

    public function getAssignedRoleIds(int $userId): array
    {
        return RolUsuario::where('id_usuario_rol_usuario', $userId)
            ->where('status', 1)
            ->pluck('id_rol_rol_usuario')
            ->toArray();
    }

    public function getPivotState(int $userId): array
    {
        return RolUsuario::where('id_usuario_rol_usuario', $userId)
            ->get()
            ->toArray();
    }

    public function syncRoles(User $user, array $roleIds): array
    {
        RolUsuario::where('id_usuario_rol_usuario', $user->id)
            ->whereNotIn('id_rol_rol_usuario', $roleIds)
            ->update(['status' => 2]);

        foreach ($roleIds as $rolId) {
            RolUsuario::updateOrCreate(
                [
                    'id_usuario_rol_usuario' => $user->id,
                    'id_rol_rol_usuario' => $rolId,
                ],
                [
                    'status' => 1,
                ]
            );
        }

        return $this->getPivotState($user->id);
    }
}
