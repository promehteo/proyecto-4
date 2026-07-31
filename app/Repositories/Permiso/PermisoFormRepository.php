<?php

declare(strict_types=1);

namespace App\Repositories\Permiso;

use App\Models\Permiso;

class PermisoFormRepository
{
    public function create(array $data): Permiso
    {
        return Permiso::create([
            'nombre_permiso' => $data['nombre_permiso'],
            'clave_permiso' => $data['clave_permiso'],
            'modulo_permiso' => $data['modulo_permiso'],
            'status' => 1,
        ]);
    }

    public function update(Permiso $permiso, array $data): Permiso
    {
        $permiso->update([
            'nombre_permiso' => $data['nombre_permiso'],
            'clave_permiso' => $data['clave_permiso'],
            'modulo_permiso' => $data['modulo_permiso'],
        ]);
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
