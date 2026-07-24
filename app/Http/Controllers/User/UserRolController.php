<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRolRequest;
use App\Models\Rol;
use App\Models\RolUsuario;
use App\Models\User;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserRolController extends Controller
{
    public function editRoles(User $usuario): View
    {
        $this->authorize('assignRoles', $usuario);

        $rolesActivos = Rol::active()->orderBy('nombre_rol')->get();
        $rolesAsignadosIds = RolUsuario::where('id_usuario_rol_usuario', $usuario->id)
            ->where('status', 1)
            ->pluck('id_rol_rol_usuario')
            ->toArray();

        return view('usuarios.roles', compact('usuario', 'rolesActivos', 'rolesAsignadosIds'));
    }

    public function updateRoles(UserRolRequest $request, User $usuario): RedirectResponse
    {
        $rolesEnviados = array_map('intval', $request->input('roles', []));

        $anterioresPivote = RolUsuario::where('id_usuario_rol_usuario', $usuario->id)
            ->get()
            ->toArray();

        RolUsuario::where('id_usuario_rol_usuario', $usuario->id)
            ->whereNotIn('id_rol_rol_usuario', $rolesEnviados)
            ->update(['status' => 2]);

        foreach ($rolesEnviados as $rolId) {
            RolUsuario::updateOrCreate(
                [
                    'id_usuario_rol_usuario' => $usuario->id,
                    'id_rol_rol_usuario' => $rolId,
                ],
                [
                    'status' => 1,
                ]
            );
        }

        $nuevosPivote = RolUsuario::where('id_usuario_rol_usuario', $usuario->id)
            ->get()
            ->toArray();

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
