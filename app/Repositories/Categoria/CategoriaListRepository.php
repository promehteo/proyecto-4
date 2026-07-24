<?php

declare(strict_types=1);

namespace App\Repositories\Categoria;

use App\Models\Categoria;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CategoriaListRepository
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $status = isset($filters['status']) && $filters['status'] !== '' ? (int) $filters['status'] : null;

        return Categoria::with('padre')
            ->search($search)
            ->byStatus($status)
            ->orderBy('nombre_categoria')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getActiveForSelect(?int $excludeId = null): Collection
    {
        return Categoria::active()
            ->when($excludeId, fn ($q) => $q->where('id_categoria', '!=', $excludeId))
            ->orderBy('nombre_categoria')
            ->get();
    }
}
