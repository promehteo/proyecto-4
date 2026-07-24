<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserGesRequest;
use App\Models\User;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserGesController extends Controller
{
    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('usuarios.create');
    }

    public function store(UserGesRequest $request): RedirectResponse
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

    public function update(UserGesRequest $request, User $usuario): RedirectResponse
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
}
