<?php

declare(strict_types=1);

namespace App\Repositories\Rol;

use App\Models\Rol;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RolListRepository
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $status = isset($filters['status']) && $filters['status'] !== '' ? (int) $filters['status'] : null;

        return Rol::withCount(['permisos' => function ($q) {
                $q->where('permiso.status', 1)->where('detalle_permiso.status', 1);
            }])
            ->search($search)
            ->byStatus($status)
            ->orderBy('nombre_rol')
            ->paginate($perPage)
            ->withQueryString();
    }
}
