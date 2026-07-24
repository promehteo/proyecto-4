<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserIndexRequest;
use App\Models\User;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserIndexController extends Controller
{
    public function index(UserIndexRequest $request): View
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

    public function inactivar(UserIndexRequest $request, User $usuario): RedirectResponse
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

    public function activar(UserIndexRequest $request, User $usuario): RedirectResponse
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
}
