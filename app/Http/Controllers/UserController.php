<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\ActivarUserRequest;
use App\Http\Requests\User\InactivarUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRolesRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Rol;
use App\Models\RolUsuario;
use App\Models\User;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $search = $request->input('search');
        $status = $request->input('status');

        $usuarios = User::with(['roles' => function ($q) {
                $q->where('rol.status', 1)->where('rol_usuario.status', 1);
            }])
            ->search($search)
            ->when($status, fn($q) => $q->where('status', (int) $status))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('usuarios.index', compact('usuarios', 'search', 'status'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('usuarios.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $usuario = User::create($data);

        BitacoraService::registrar(
            auditable: $usuario,
            accion: 'creación de usuario',
            valoresAnteriores: null,
            valoresNuevos: $usuario->toArray(),
            descripcion: "Usuario '{$usuario->name}' creado exitosamente."
        );

        return redirect()->route('usuarios.index')
            ->with('success', "El usuario '{$usuario->name}' ha sido creado correctamente.");
    }

    public function edit(User $usuario): View
    {
        $this->authorize('update', $usuario);

        return view('usuarios.edit', compact('usuario'));
    }

    public function update(UpdateUserRequest $request, User $usuario): RedirectResponse
    {
        $valoresAnteriores = $usuario->toArray();
        $data = $request->validated();

        $cambioPassword = false;
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
            $cambioPassword = true;
        } else {
            unset($data['password']);
        }

        $usuario->update($data);

        BitacoraService::registrar(
            auditable: $usuario,
            accion: $cambioPassword ? 'cambio de contraseña' : 'edición de usuario',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $usuario->fresh()->toArray(),
            descripcion: "Usuario '{$usuario->name}' actualizado exitosamente."
        );

        return redirect()->route('usuarios.index')
            ->with('success', "El usuario '{$usuario->name}' ha sido actualizado correctamente.");
    }

    public function inactivar(InactivarUserRequest $request, User $usuario): RedirectResponse
    {
        $valoresAnteriores = $usuario->toArray();
        $usuario->update(['status' => 2]);

        BitacoraService::registrar(
            auditable: $usuario,
            accion: 'inactivación de usuario',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $usuario->fresh()->toArray(),
            descripcion: "Usuario '{$usuario->name}' inactivado."
        );

        return redirect()->route('usuarios.index')
            ->with('success', "El usuario '{$usuario->name}' ha sido inactivado.");
    }

    public function activar(ActivarUserRequest $request, User $usuario): RedirectResponse
    {
        $valoresAnteriores = $usuario->toArray();
        $usuario->update(['status' => 1]);

        BitacoraService::registrar(
            auditable: $usuario,
            accion: 'activación de usuario',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $usuario->fresh()->toArray(),
            descripcion: "Usuario '{$usuario->name}' activado."
        );

        return redirect()->route('usuarios.index')
            ->with('success', "El usuario '{$usuario->name}' ha sido activado.");
    }

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

    public function updateRoles(UpdateUserRolesRequest $request, User $usuario): RedirectResponse
    {
        $rolesEnviados = array_map('intval', $request->input('roles', []));

        $anterioresPivote = RolUsuario::where('id_usuario_rol_usuario', $usuario->id)
            ->get()
            ->toArray();

        // 1. Inactivar (status = 2) roles no incluidos
        RolUsuario::where('id_usuario_rol_usuario', $usuario->id)
            ->whereNotIn('id_rol_rol_usuario', $rolesEnviados)
            ->update(['status' => 2]);

        // 2. Para cada rol enviado, updateOrCreate a status = 1
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
