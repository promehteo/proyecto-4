<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Permission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Maneja la petición entrante.
     */
    public function handle(Request $request, Closure $next, string $permissionValue): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Acceso denegado. No autenticado.');
        }

        $permission = Permission::tryFrom($permissionValue);

        if (!$permission || !$user->hasPermission($permission)) {
            abort(403, 'No posees los privilegios necesarios para realizar esta acción.');
        }

        return $next($request);
    }
}
