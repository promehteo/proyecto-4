<?php

declare(strict_types=1);

namespace App\Repositories\Rol;

use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RolFormRepository
{
    public function create(array $data): Rol
    {
        return Rol::create($data);
    }

    public function update(Rol $rol, array $data): Rol
    {
        $rol->update($data);
        return $rol->fresh();
    }

    public function activate(Rol $rol): Rol
    {
        $rol->update(['status' => 1]);
        return $rol->fresh();
    }

    public function deactivate(Rol $rol): Rol
    {
        $rol->update(['status' => 2]);
        return $rol->fresh();
    }

    public function getActivePermisosGroupedByModulo(): Collection
    {
        return Permiso::active()
            ->orderBy('modulo_permiso')
            ->orderBy('nombre_permiso')
            ->get()
            ->groupBy('modulo_permiso');
    }

    public function getAssignedPermisoIds(int $rolId): array
    {
        return DB::table('detalle_permiso')
            ->where('id_rol', $rolId)
            ->where('status', 1)
            ->pluck('id_permiso')
            ->toArray();
    }

    public function getPivotState(int $rolId): array
    {
        return DB::table('detalle_permiso')
            ->where('id_rol', $rolId)
            ->get()
            ->toArray();
    }

    public function syncPermisos(Rol $rol, array $permisoIds): array
    {
        DB::table('detalle_permiso')
            ->where('id_rol', $rol->id_rol)
            ->whereNotIn('id_permiso', $permisoIds)
            ->update(['status' => 2]);

        foreach ($permisoIds as $permisoId) {
            $exists = DB::table('detalle_permiso')
                ->where('id_rol', $rol->id_rol)
                ->where('id_permiso', $permisoId)
                ->exists();

            if ($exists) {
                DB::table('detalle_permiso')
                    ->where('id_rol', $rol->id_rol)
                    ->where('id_permiso', $permisoId)
                    ->update(['status' => 1]);
            } else {
                DB::table('detalle_permiso')->insert([
                    'id_rol' => $rol->id_rol,
                    'id_permiso' => $permisoId,
                    'status' => 1,
                ]);
            }
        }

        return $this->getPivotState($rol->id_rol);
    }
}
