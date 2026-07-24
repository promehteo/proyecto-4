<?php

declare(strict_types=1);

namespace App\Http\Controllers\Permiso;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permiso\PermisoIndexRequest;
use App\Models\Permiso;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermisoIndexController extends Controller
{
    public function index(PermisoIndexRequest $request): View
    {
        $this->authorize('viewAny', Permiso::class);

        $search = $request->input('search');
        $modulo = $request->input('modulo');
        $status = $request->input('status');

        $permisos = Permiso::search($search)
            ->byModulo($modulo)
            ->byStatus($status ? (int) $status : null)
            ->orderBy('modulo_permiso')
            ->orderBy('nombre_permiso')
            ->paginate(15)
            ->withQueryString();

        $modulos = Permiso::distinct()->pluck('modulo_permiso')->filter()->values();

        return view('permisos.index', compact('permisos', 'modulos', 'search', 'modulo', 'status'));
    }

    public function inactivar(PermisoIndexRequest $request, Permiso $permiso): RedirectResponse
    {
        $valoresAnteriores = $permiso->toArray();
        $permiso->update(['status' => 2]);

        BitacoraService::registrar(
            auditable: $permiso,
            accion: 'inactivación de permiso',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $permiso->fresh()->toArray(),
            descripcion: "Permiso '{$permiso->nombre_permiso}' inactivado."
        );

        return redirect()->route('permisos.index')
            ->with('success', "El permiso '{$permiso->nombre_permiso}' ha sido inactivado.");
    }

    public function activar(PermisoIndexRequest $request, Permiso $permiso): RedirectResponse
    {
        $valoresAnteriores = $permiso->toArray();
        $permiso->update(['status' => 1]);

        BitacoraService::registrar(
            auditable: $permiso,
            accion: 'activación de permiso',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $permiso->fresh()->toArray(),
            descripcion: "Permiso '{$permiso->nombre_permiso}' activado."
        );

        return redirect()->route('permisos.index')
            ->with('success', "El permiso '{$permiso->nombre_permiso}' ha sido activado.");
    }
}
