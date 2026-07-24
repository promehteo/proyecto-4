<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Rol\ActivarRolRequest;
use App\Http\Requests\Rol\InactivarRolRequest;
use App\Http\Requests\Rol\StoreRolRequest;
use App\Http\Requests\Rol\UpdateRolPermisosRequest;
use App\Http\Requests\Rol\UpdateRolRequest;
use App\Models\Permiso;
use App\Models\PermisoRol;
use App\Models\Rol;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RolController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Rol::class);

        $search = $request->input('search');
        $status = $request->input('status');

        $roles = Rol::withCount(['permisos' => function ($q) {
                $q->where('permiso.status', 1)->where('permiso_rol.status', 1);
            }])
            ->search($search)
            ->byStatus($status ? (int) $status : null)
            ->orderBy('nombre_rol')
            ->paginate(15)
            ->withQueryString();

        return view('roles.index', compact('roles', 'search', 'status'));
    }

    public function create(): View
    {
        $this->authorize('create', Rol::class);

        return view('roles.create');
    }

    public function store(StoreRolRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $rol = Rol::create($data);

        BitacoraService::registrar(
            auditable: $rol,
            accion: 'creación de rol',
            valoresAnteriores: null,
            valoresNuevos: $rol->toArray(),
            descripcion: "Rol '{$rol->nombre_rol}' creado exitosamente."
        );

        return redirect()->route('roles.index')
            ->with('success', "El rol '{$rol->nombre_rol}' ha sido creado correctamente.");
    }

    public function edit(Rol $rol): View
    {
        $this->authorize('update', $rol);

        return view('roles.edit', compact('rol'));
    }

    public function update(UpdateRolRequest $request, Rol $rol): RedirectResponse
    {
        $valoresAnteriores = $rol->toArray();
        $data = $request->validated();

        $rol->update($data);

        BitacoraService::registrar(
            auditable: $rol,
            accion: 'edición de rol',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $rol->fresh()->toArray(),
            descripcion: "Rol '{$rol->nombre_rol}' actualizado exitosamente."
        );

        return redirect()->route('roles.index')
            ->with('success', "El rol '{$rol->nombre_rol}' ha sido actualizado correctamente.");
    }

    public function inactivar(InactivarRolRequest $request, Rol $rol): RedirectResponse
    {
        $valoresAnteriores = $rol->toArray();
        $rol->update(['status' => 2]);

        BitacoraService::registrar(
            auditable: $rol,
            accion: 'inactivación de rol',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $rol->fresh()->toArray(),
            descripcion: "Rol '{$rol->nombre_rol}' inactivado."
        );

        return redirect()->route('roles.index')
            ->with('success', "El rol '{$rol->nombre_rol}' ha sido inactivado.");
    }

    public function activar(ActivarRolRequest $request, Rol $rol): RedirectResponse
    {
        $valoresAnteriores = $rol->toArray();
        $rol->update(['status' => 1]);

        BitacoraService::registrar(
            auditable: $rol,
            accion: 'activación de rol',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $rol->fresh()->toArray(),
            descripcion: "Rol '{$rol->nombre_rol}' activado."
        );

        return redirect()->route('roles.index')
            ->with('success', "El rol '{$rol->nombre_rol}' ha sido activado.");
    }

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

    public function updatePermisos(UpdateRolPermisosRequest $request, Rol $rol): RedirectResponse
    {
        $permisosEnviados = array_map('intval', $request->input('permisos', []));

        $anterioresPivote = PermisoRol::where('id_rol_permiso_rol', $rol->id_rol)
            ->get()
            ->toArray();

        // 1. Inactivar (status = 2) todos los permisos asociados que NO están en la lista enviada
        PermisoRol::where('id_rol_permiso_rol', $rol->id_rol)
            ->whereNotIn('id_permiso_permiso_rol', $permisosEnviados)
            ->update(['status' => 2]);

        // 2. Para cada permiso enviado, hacer updateOrCreate a status = 1
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
