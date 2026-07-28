<?php

declare(strict_types=1);

namespace App\Http\Controllers\Permiso;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permiso\PermisoIndexRequest;
use App\Models\Permiso;
use App\Repositories\Permiso\PermisoFormRepository;
use App\Repositories\Permiso\PermisoListRepository;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermisoIndexController extends Controller
{
    public function __construct(
        protected PermisoListRepository $listRepository,
        protected PermisoFormRepository $formRepository
    ) {}

    public function index(PermisoIndexRequest $request): View
    {
        $this->authorize('viewAny', Permiso::class);

        $search = $request->input('search');
        $modulo = $request->input('modulo');
        $status = $request->input('status');

        $permisos = $this->listRepository->paginate([
            'search' => $search,
            'modulo' => $modulo,
            'status' => $status,
        ]);

        $modulos = $this->listRepository->getDistinctModulos();

        return view('permisos.index', compact('permisos', 'modulos', 'search', 'modulo', 'status'));
    }

    public function inactivar(PermisoIndexRequest $request, Permiso $permiso): RedirectResponse
    {
        $valoresAnteriores = $permiso->toArray();
        $permisoInactivado = $this->formRepository->deactivate($permiso);

        BitacoraService::registrar(
            auditable: $permisoInactivado,
            accion: 'inactivación de permiso',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $permisoInactivado->toArray(),
            descripcion: "Permiso '{$permisoInactivado->nombre_permiso}' inactivado."
        );

        return redirect()->route('permisos.index')
            ->with('success', "El permiso '{$permisoInactivado->nombre_permiso}' ha sido inactivado.");
    }

    public function activar(PermisoIndexRequest $request, Permiso $permiso): RedirectResponse
    {
        $valoresAnteriores = $permiso->toArray();
        $permisoActivado = $this->formRepository->activate($permiso);

        BitacoraService::registrar(
            auditable: $permisoActivado,
            accion: 'activación de permiso',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $permisoActivado->toArray(),
            descripcion: "Permiso '{$permisoActivado->nombre_permiso}' activado."
        );

        return redirect()->route('permisos.index')
            ->with('success', "El permiso '{$permisoActivado->nombre_permiso}' ha sido activado.");
    }

    public function show(Permiso $permiso): View
    {
        $this->authorize('view', $permiso);

        return view('permisos.show', compact('permiso'));
    }
}
