<?php

declare(strict_types=1);

namespace App\Repositories\Bitacora;

use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BitacoraListRepository
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $usuarioId = isset($filters['usuario_id']) && $filters['usuario_id'] !== '' ? (int) $filters['usuario_id'] : null;
        $accion = $filters['accion'] ?? null;
        $modulo = $filters['auditable_tipo'] ?? null;
        $registroId = isset($filters['auditable_id']) && $filters['auditable_id'] !== '' ? (int) $filters['auditable_id'] : null;
        $fechaDesde = $filters['fecha_desde'] ?? null;
        $fechaHasta = $filters['fecha_hasta'] ?? null;
        $ip = $filters['ip'] ?? null;

        return Bitacora::with('usuario')
            ->search($search)
            ->byUsuario($usuarioId)
            ->byAccion($accion)
            ->byModulo($modulo)
            ->byRegistroId($registroId)
            ->byFechaDesde($fechaDesde)
            ->byFechaHasta($fechaHasta)
            ->byIp($ip)
            ->orderByDesc('fecha_bitacora')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getUsersForFilter(): Collection
    {
        return User::orderBy('nombre')->get();
    }

    public function getDistinctAcciones(): Collection
    {
        return Bitacora::distinct()
            ->orderBy('accion_bitacora')
            ->pluck('accion_bitacora')
            ->filter()
            ->values();
    }
}
