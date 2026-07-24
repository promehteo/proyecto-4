<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRolRequest;
use App\Models\User;
use App\Repositories\User\UserFormRepository;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserRolController extends Controller
{
    public function __construct(
        protected UserFormRepository $formRepository
    ) {}

    public function editRoles(User $usuario): View
    {
        $this->authorize('assignRoles', $usuario);

        $rolesActivos = $this->formRepository->getActiveRolesForSelect();
        $rolesAsignadosIds = $this->formRepository->getAssignedRoleIds($usuario->id);

        return view('usuarios.roles', compact('usuario', 'rolesActivos', 'rolesAsignadosIds'));
    }

    public function updateRoles(UserRolRequest $request, User $usuario): RedirectResponse
    {
        $rolesEnviados = array_map('intval', $request->input('roles', []));

        $anterioresPivote = $this->formRepository->getPivotState($usuario->id);
        $nuevosPivote = $this->formRepository->syncRoles($usuario, $rolesEnviados);

        BitacoraService::registrar(
            auditable: $usuario,
            accion: 'asignación de roles a usuario',
            valoresAnteriores: ['roles_pivote' => $anterioresPivote],
            valoresNuevos: ['roles_pivote' => $nuevosPivote],
            descripcion: "Roles me actualizados para el usuario '{$usuario->name}'."
        );

        return redirect()->route('usuarios.index')
            ->with('success', "Los roles del usuario '{$usuario->name}' han sido actualizados correctamente.");
    }
}
