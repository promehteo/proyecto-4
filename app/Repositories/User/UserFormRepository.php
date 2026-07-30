<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        return DB::table('detalle_rol')
            ->where('id_usuario', $userId)
            ->where('status', 1)
            ->pluck('id_rol')
            ->toArray();
    }

    public function getPivotState(int $userId): array
    {
        return DB::table('detalle_rol')
            ->where('id_usuario', $userId)
            ->get()
            ->toArray();
    }

    public function syncRoles(User $user, array $roleIds): array
    {
        DB::table('detalle_rol')
            ->where('id_usuario', $user->id_user)
            ->whereNotIn('id_rol', $roleIds)
            ->update(['status' => 2]);

        foreach ($roleIds as $rolId) {
            $exists = DB::table('detalle_rol')
                ->where('id_usuario', $user->id_user)
                ->where('id_rol', $rolId)
                ->exists();

            if ($exists) {
                DB::table('detalle_rol')
                    ->where('id_usuario', $user->id_user)
                    ->where('id_rol', $rolId)
                    ->update(['status' => 1]);
            } else {
                DB::table('detalle_rol')->insert([
                    'id_usuario' => $user->id_user,
                    'id_rol' => $rolId,
                    'status' => 1,
                ]);
            }
        }

        return $this->getPivotState($user->id_user);
    }
}
