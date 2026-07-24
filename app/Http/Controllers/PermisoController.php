<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Permiso\ActivarPermisoRequest;
use App\Http\Requests\Permiso\InactivarPermisoRequest;
use App\Http\Requests\Permiso\StorePermisoRequest;
use App\Http\Requests\Permiso\UpdatePermisoRequest;
use App\Models\Permiso;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermisoController extends Controller
{
    public function index(Request $request): View
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

    public function create(): View
    {
        $this->authorize('create', Permiso::class);

        $modulosExistentes = Permiso::distinct()->pluck('modulo_permiso')->filter()->values();

        return view('permisos.create', compact('modulosExistentes'));
    }

    public function store(StorePermisoRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $permiso = Permiso::create($data);

        BitacoraService::registrar(
            auditable: $permiso,
            accion: 'creación de permiso',
            valoresAnteriores: null,
            valoresNuevos: $permiso->toArray(),
            descripcion: "Permiso '{$permiso->nombre_permiso}' creado exitosamente."
        );

        return redirect()->route('permisos.index')
            ->with('success', "El permiso '{$permiso->nombre_permiso}' ha sido creado correctamente.");
    }

    public function edit(Permiso $permiso): View
    {
        $this->authorize('update', $permiso);

        $modulosExistentes = Permiso::distinct()->pluck('modulo_permiso')->filter()->values();

        return view('permisos.edit', compact('permiso', 'modulosExistentes'));
    }

    public function update(UpdatePermisoRequest $request, Permiso $permiso): RedirectResponse
    {
        $valoresAnteriores = $permiso->toArray();
        $data = $request->validated();

        $permiso->update($data);

        BitacoraService::registrar(
            auditable: $permiso,
            accion: 'edición de permiso',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $permiso->fresh()->toArray(),
            descripcion: "Permiso '{$permiso->nombre_permiso}' actualizado exitosamente."
        );

        return redirect()->route('permisos.index')
            ->with('success', "El permiso '{$permiso->nombre_permiso}' ha sido actualizado correctamente.");
    }

    public function inactivar(InactivarPermisoRequest $request, Permiso $permiso): RedirectResponse
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

    public function activar(ActivarPermisoRequest $request, Permiso $permiso): RedirectResponse
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
