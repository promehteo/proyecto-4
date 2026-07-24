<?php

declare(strict_types=1);

namespace App\Repositories\Permiso;

use App\Models\Permiso;

class PermisoFormRepository
{
    public function create(array $data): Permiso
    {
        return Permiso::create($data);
    }

    public function update(Permiso $permiso, array $data): Permiso
    {
        $permiso->update($data);
        return $permiso->fresh();
    }

    public function activate(Permiso $permiso): Permiso
    {
        $permiso->update(['status' => 1]);
        return $permiso->fresh();
    }

    public function deactivate(Permiso $permiso): Permiso
    {
        $permiso->update(['status' => 2]);
        return $permiso->fresh();
    }
}
