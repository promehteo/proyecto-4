<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserListRepository
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $status = isset($filters['status']) && $filters['status'] !== '' ? (int) $filters['status'] : null;

        return User::with(['roles' => function ($q) {
                $q->where('rol.status', 1)->where('detalle_rol.status', 1);
            }])
            ->search($search)
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('nombre')
            ->paginate($perPage)
            ->withQueryString();
    }
}
