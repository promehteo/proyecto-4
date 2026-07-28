<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rol\RolGesRequest;
use App\Models\Rol;
use App\Repositories\Rol\RolFormRepository;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RolFormController extends Controller
{
    public function __construct(
        protected RolFormRepository $formRepository
    ) {}

    public function create(): View
    {
        $this->authorize('create', Rol::class);

        return view('roles.create');
    }

    public function store(RolGesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $rol = $this->formRepository->create($data);

        BitacoraService::registrar(
            auditable: $rol,
            accion: 'registro de rol',
            valoresAnteriores: null,
            valoresNuevos: $rol->toArray(),
            descripcion: "Rol '{$rol->nombre_rol}' registrado exitosamente."
        );

        return redirect()->route('roles.index')
            ->with('success', "El rol '{$rol->nombre_rol}' ha sido registrado correctamente.");
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

        $rolActualizado = $this->formRepository->update($rol, $data);

        BitacoraService::registrar(
            auditable: $rolActualizado,
            accion: 'edición de rol',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $rolActualizado->toArray(),
            descripcion: "Rol '{$rolActualizado->nombre_rol}' editado exitosamente."
        );

        return redirect()->route('roles.index')
            ->with('success', "El rol '{$rolActualizado->nombre_rol}' ha sido editado correctamente.");
    }
}
