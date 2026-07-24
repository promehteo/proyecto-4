<?php

declare(strict_types=1);

namespace App\Http\Controllers\Permiso;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permiso\PermisoGesRequest;
use App\Models\Permiso;
use App\Repositories\Permiso\PermisoFormRepository;
use App\Repositories\Permiso\PermisoListRepository;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermisoFormController extends Controller
{
    public function __construct(
        protected PermisoListRepository $listRepository,
        protected PermisoFormRepository $formRepository
    ) {}

    public function create(): View
    {
        $this->authorize('create', Permiso::class);

        $modulosExistentes = $this->listRepository->getDistinctModulos();

        return view('permisos.create', compact('modulosExistentes'));
    }

    public function store(PermisoGesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $permiso = $this->formRepository->create($data);

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

        $modulosExistentes = $this->listRepository->getDistinctModulos();

        return view('permisos.edit', compact('permiso', 'modulosExistentes'));
    }

    public function update(PermisoGesRequest $request, Permiso $permiso): RedirectResponse
    {
        $valoresAnteriores = $permiso->toArray();
        $data = $request->validated();

        $permisoActualizado = $this->formRepository->update($permiso, $data);

        BitacoraService::registrar(
            auditable: $permisoActualizado,
            accion: 'edición de permiso',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $permisoActualizado->toArray(),
            descripcion: "Permiso '{$permisoActualizado->nombre_permiso}' actualizado exitosamente."
        );

        return redirect()->route('permisos.index')
            ->with('success', "El permiso '{$permisoActualizado->nombre_permiso}' ha sido actualizado correctamente.");
    }
}
