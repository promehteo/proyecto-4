<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rol\RolPermisoRequest;
use App\Models\Permiso;
use App\Models\PermisoRol;
use App\Models\Rol;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RolPermisoController extends Controller
{
    public function editPermisos(Rol $rol): View
    {
        $this->authorize('assignPermissions', $rol);

        $permisosAgrupados = Permiso::active()
            ->orderBy('modulo_permiso')
            ->orderBy('nombre_permiso')
            ->get()
            ->groupBy('modulo_permiso');

        $permisosAsignadosIds = PermisoRol::where('id_rol_permiso_rol', $rol->id_rol)
            ->where('status', 1)
            ->pluck('id_permiso_permiso_rol')
            ->toArray();

        return view('roles.permisos', compact('rol', 'permisosAgrupados', 'permisosAsignadosIds'));
    }

    public function updatePermisos(RolPermisoRequest $request, Rol $rol): RedirectResponse
    {
        $permisosEnviados = array_map('intval', $request->input('permisos', []));

        $anterioresPivote = PermisoRol::where('id_rol_permiso_rol', $rol->id_rol)
            ->get()
            ->toArray();

        PermisoRol::where('id_rol_permiso_rol', $rol->id_rol)
            ->whereNotIn('id_permiso_permiso_rol', $permisosEnviados)
            ->update(['status' => 2]);

        foreach ($permisosEnviados as $permisoId) {
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

        $nuevosPivote = PermisoRol::where('id_rol_permiso_rol', $rol->id_rol)
            ->get()
            ->toArray();

        BitacoraService::registrar(
            auditable: $rol,
            accion: 'asignación de permisos a rol',
            valoresAnteriores: ['permisos_pivote' => $anterioresPivote],
            valoresNuevos: ['permisos_pivote' => $nuevosPivote],
            descripcion: "Permisos actualizados para el rol '{$rol->nombre_rol}'."
        );

        return redirect()->route('roles.index')
            ->with('success', "Los permisos del rol '{$rol->nombre_rol}' han sido me actualizados correctamente.");
    }
}
