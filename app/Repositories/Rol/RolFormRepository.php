<?php

declare(strict_types=1);

namespace App\Repositories\Rol;

use App\Models\Permiso;
use App\Models\PermisoRol;
use App\Models\Rol;
use Illuminate\Support\Collection;

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
        return PermisoRol::where('id_rol_permiso_rol', $rolId)
            ->where('status', 1)
            ->pluck('id_permiso_permiso_rol')
            ->toArray();
    }

    public function getPivotState(int $rolId): array
    {
        return PermisoRol::where('id_rol_permiso_rol', $rolId)
            ->get()
            ->toArray();
    }

    public function syncPermisos(Rol $rol, array $permisoIds): array
    {
        PermisoRol::where('id_rol_permiso_rol', $rol->id_rol)
            ->whereNotIn('id_permiso_permiso_rol', $permisoIds)
            ->update(['status' => 2]);

        foreach ($permisoIds as $permisoId) {
            PermisoRol::updateOrCreate(
                [
                    'id_rol_permiso_rol' => $rol->id_rol,
                    'id_permiso_permiso_rol' => $permisoId,
                ],
                [
                    'status' => 1,
                ]
            );
        }

        return $this->getPivotState($rol->id_rol);
    }
}
