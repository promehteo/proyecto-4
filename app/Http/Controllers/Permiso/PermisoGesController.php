<?php

declare(strict_types=1);

namespace App\Http\Controllers\Permiso;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permiso\PermisoGesRequest;
use App\Models\Permiso;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermisoGesController extends Controller
{
    public function create(): View
    {
        $this->authorize('create', Permiso::class);

        $modulosExistentes = Permiso::distinct()->pluck('modulo_permiso')->filter()->values();

        return view('permisos.create', compact('modulosExistentes'));
    }

    public function store(PermisoGesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = 1; // Todos los registros se crean activos por defecto
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

    public function update(PermisoGesRequest $request, Permiso $permiso): RedirectResponse
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
}
