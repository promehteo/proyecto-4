<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserFormRequest;
use App\Models\User;
use App\Repositories\User\UserFormRepository;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserFormController extends Controller
{
    public function __construct(
        protected UserFormRepository $formRepository
    ) {}

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('usuarios.create');
    }

    public function store(UserFormRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $usuario = $this->formRepository->create($data);

        BitacoraService::registrar(
            auditable: $usuario,
            accion: 'registro de usuario',
            valoresAnteriores: null,
            valoresNuevos: $usuario->toArray()
        );

        return redirect()->route('usuarios.index')
            ->with('success', "El usuario '{$usuario->nombre} {$usuario->apellido}' ha sido registrado correctamente.");
    }

    public function edit(User $usuario): View
    {
        $this->authorize('update', $usuario);

        return view('usuarios.edit', compact('usuario'));
    }

    public function update(UserFormRequest $request, User $usuario): RedirectResponse
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

        $usuarioActualizado = $this->formRepository->update($usuario, $data);

        BitacoraService::registrar(
            auditable: $usuarioActualizado,
            accion: $cambioPassword ? 'cambio de contraseña' : 'edición de usuario',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $usuarioActualizado->toArray()
        );

        return redirect()->route('usuarios.index')
            ->with('success', "El usuario '{$usuarioActualizado->nombre} {$usuarioActualizado->apellido}' ha sido editado correctamente.");
    }
}
