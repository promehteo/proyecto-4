<?php

declare(strict_types=1);

namespace App\Repositories\Bitacora;

use App\Models\Bitacora;

class BitacoraDetailRepository
{
    public function findDetailById(int $id): ?Bitacora
    {
        return Bitacora::with('usuario')->find($id);
    }
}
