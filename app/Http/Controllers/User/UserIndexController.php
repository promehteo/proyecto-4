<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserIndexRequest;
use App\Models\User;
use App\Repositories\User\UserFormRepository;
use App\Repositories\User\UserListRepository;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserIndexController extends Controller
{
    public function __construct(
        protected UserListRepository $listRepository,
        protected UserFormRepository $formRepository
    ) {}

    public function index(UserIndexRequest $request): View
    {
        $this->authorize('viewAny', User::class);

        $search = $request->input('search');
        $status = $request->input('status');

        $usuarios = $this->listRepository->paginate([
            'search' => $search,
            'status' => $status,
        ]);

        return view('usuarios.index', compact('usuarios', 'search', 'status'));
    }

    public function inactivar(UserIndexRequest $request, User $usuario): RedirectResponse
    {
        $valoresAnteriores = $usuario->toArray();
        $usuarioInactivado = $this->formRepository->deactivate($usuario);

        BitacoraService::registrar(
            auditable: $usuarioInactivado,
            accion: 'inactivación de usuario',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $usuarioInactivado->toArray(),
            descripcion: "Usuario '{$usuarioInactivado->nombre} {$usuarioInactivado->apellido}' inactivado."
        );

        return redirect()->route('usuarios.index')
            ->with('success', "El usuario '{$usuarioInactivado->nombre} {$usuarioInactivado->apellido}' ha sido inactivado.");
    }

    public function activar(UserIndexRequest $request, User $usuario): RedirectResponse
    {
        $valoresAnteriores = $usuario->toArray();
        $usuarioActivado = $this->formRepository->activate($usuario);

        BitacoraService::registrar(
            auditable: $usuarioActivado,
            accion: 'activación de usuario',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $usuarioActivado->toArray(),
            descripcion: "Usuario '{$usuarioActivado->nombre} {$usuarioActivado->apellido}' activado."
        );

        return redirect()->route('usuarios.index')
            ->with('success', "El usuario '{$usuarioActivado->nombre} {$usuarioActivado->apellido}' ha sido activado.");
    }

    public function show(User $usuario): View
    {
        $this->authorize('view', $usuario);

        $usuario->load('roles');

        return view('usuarios.show', compact('usuario'));
    }
}
