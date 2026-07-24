<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rol\RolGesRequest;
use App\Models\Rol;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RolGesController extends Controller
{
    public function create(): View
    {
        $this->authorize('create', Rol::class);

        return view('roles.create');
    }

    public function store(RolGesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = 1; // Todos los registros se crean activos por defecto
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

    public function update(RolGesRequest $request, Rol $rol): RedirectResponse
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
}
