<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rol\RolIndexRequest;
use App\Models\Rol;
use App\Repositories\Rol\RolFormRepository;
use App\Repositories\Rol\RolListRepository;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RolIndexController extends Controller
{
    public function __construct(
        protected RolListRepository $listRepository,
        protected RolFormRepository $formRepository
    ) {}

    public function index(RolIndexRequest $request): View
    {
        $this->authorize('viewAny', Rol::class);

        $search = $request->input('search');
        $status = $request->input('status');

        $roles = $this->listRepository->paginate([
            'search' => $search,
            'status' => $status,
        ]);

        return view('roles.index', compact('roles', 'search', 'status'));
    }

    public function inactivar(RolIndexRequest $request, Rol $rol): RedirectResponse
    {
        $valoresAnteriores = $rol->toArray();
        $rolInactivado = $this->formRepository->deactivate($rol);

        BitacoraService::registrar(
            auditable: $rolInactivado,
            accion: 'inactivación de rol',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $rolInactivado->toArray(),
            descripcion: "Rol '{$rolInactivado->nombre_rol}' inactivado."
        );

        return redirect()->route('roles.index')
            ->with('success', "El rol '{$rolInactivado->nombre_rol}' ha sido inactivado.");
    }

    public function activar(RolIndexRequest $request, Rol $rol): RedirectResponse
    {
        $valoresAnteriores = $rol->toArray();
        $rolActivado = $this->formRepository->activate($rol);

        BitacoraService::registrar(
            auditable: $rolActivado,
            accion: 'activación de rol',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $rolActivado->toArray(),
            descripcion: "Rol '{$rolActivado->nombre_rol}' activado."
        );

        return redirect()->route('roles.index')
            ->with('success', "El rol '{$rolActivado->nombre_rol}' ha sido activado.");
    }

    public function show(Rol $rol): View
    {
        $this->authorize('view', $rol);

        $rol->load('permisos');

        return view('roles.show', compact('rol'));
    }
}
