<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rol\RolIndexRequest;
use App\Models\Rol;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RolIndexController extends Controller
{
    public function index(RolIndexRequest $request): View
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

    public function inactivar(RolIndexRequest $request, Rol $rol): RedirectResponse
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

    public function activar(RolIndexRequest $request, Rol $rol): RedirectResponse
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
}
