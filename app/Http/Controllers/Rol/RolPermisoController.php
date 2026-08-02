<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rol\RolPermisoRequest;
use App\Models\Rol;
use App\Repositories\Rol\RolFormRepository;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RolPermisoController extends Controller
{
    public function __construct(
        protected RolFormRepository $formRepository
    ) {}

    public function editPermisos(Rol $rol): View
    {
        $this->authorize('assignPermissions', $rol);

        $permisosAgrupados = $this->formRepository->getActivePermisosGroupedByModulo();
        $permisosAsignadosIds = $this->formRepository->getAssignedPermisoIds($rol->id_rol);

        return view('roles.permisos', compact('rol', 'permisosAgrupados', 'permisosAsignadosIds'));
    }

    public function updatePermisos(RolPermisoRequest $request, Rol $rol): RedirectResponse
    {
        $permisosEnviados = array_map('intval', $request->input('permisos', []));

        $anterioresPivote = $this->formRepository->getPivotState($rol->id_rol);
        $nuevosPivote = $this->formRepository->syncPermisos($rol, $permisosEnviados);

        BitacoraService::registrar(
            auditable: $rol,
            accion: 'asignación de permisos a rol',
            valoresAnteriores: ['permisos_pivote' => $anterioresPivote],
            valoresNuevos: ['permisos_pivote' => $nuevosPivote]
        );

        return redirect()->route('roles.index')
            ->with('success', "Los permisos del rol '{$rol->nombre_rol}' han sido me actualizados correctamente.");
    }
}
