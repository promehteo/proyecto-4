<?php

declare(strict_types=1);

namespace App\Repositories\Permiso;

use App\Models\Permiso;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PermisoListRepository
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $modulo = $filters['modulo'] ?? null;
        $status = isset($filters['status']) && $filters['status'] !== '' ? (int) $filters['status'] : null;

        return Permiso::search($search)
            ->byModulo($modulo)
            ->byStatus($status)
            ->orderBy('modulo_permiso')
            ->orderBy('nombre_permiso')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getDistinctModulos(): Collection
    {
        return Permiso::distinct()->pluck('modulo_permiso')->filter()->values();
    }
}
