<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Models\Bitacora;
use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function edit(Rol $rol): View
    {
        $this->authorize('assignPermissions', $rol);

        $permisosAgrupados = Permiso::active()
            ->orderBy('modulo_permiso')
            ->orderBy('nombre_permiso')
            ->get()
            ->groupBy('modulo_permiso');

        $permisosAsignadosIds = $rol->permisos()
            ->where('detalle_permiso.status', 1)
            ->pluck('permiso.id_permiso')
            ->toArray();

        return view('roles.permisos', compact('rol', 'permisosAgrupados', 'permisosAsignadosIds'));
    }

    public function update(Request $request, Rol $rol): RedirectResponse
    {
        $this->authorize('assignPermissions', $rol);

        $request->validate([
            'permisos' => ['nullable', 'array'],
            'permisos.*' => ['integer', 'exists:permiso,id_permiso'],
        ]);

        $validClaves = array_map(fn($case) => $case->value, Permission::cases());
        $permisoIdsValidos = Permiso::whereIn('clave_permiso', $validClaves)->pluck('id_permiso')->toArray();

        $permisosEnviados = $request->input('permisos', []);
        $permisosAAsignar = array_intersect(array_map('intval', $permisosEnviados), $permisoIdsValidos);

        // Obtener estado anterior para bitácora
        $permisosActuales = $rol->permisos()
            ->where('detalle_permiso.status', 1)
            ->pluck('clave_permiso', 'permiso.id_permiso')
            ->toArray();

        // Obtener estado nuevo para bitácora
        $nuevosPermisosMapeados = Permiso::whereIn('id_permiso', $permisosAAsignar)
            ->pluck('clave_permiso', 'id_permiso')
            ->toArray();

        // Registrar en bitácora
        Bitacora::create([
            'id_usuario_bitacora' => $request->user()?->id_user,
            'accion_bitacora' => 'Actualización de permisos',
            'modulo_bitacora' => 'Roles',
            'registro_id_bitacora' => $rol->id_rol,
            'valores_anteriores_bitacora' => json_encode($permisosActuales),
            'valores_nuevos_bitacora' => json_encode($nuevosPermisosMapeados),
            'ip_bitacora' => RequestFacade::ip(),
            'navegador_bitacora' => RequestFacade::userAgent(),
            'url_bitacora' => RequestFacade::fullUrl(),
            'metodo_bitacora' => RequestFacade::method(),
            'fecha_bitacora' => now(),
            'status' => 1,
        ]);

        // Sincronizar permisos en detalle_permiso actualizando status
        \Illuminate\Support\Facades\DB::table('detalle_permiso')
            ->where('id_rol', $rol->id_rol)
            ->whereNotIn('id_permiso', $permisosAAsignar)
            ->update(['status' => 2]);

        foreach ($permisosAAsignar as $idPermiso) {
            $exists = \Illuminate\Support\Facades\DB::table('detalle_permiso')
                ->where('id_rol', $rol->id_rol)
                ->where('id_permiso', $idPermiso)
                ->exists();

            if ($exists) {
                \Illuminate\Support\Facades\DB::table('detalle_permiso')
                    ->where('id_rol', $rol->id_rol)
                    ->where('id_permiso', $idPermiso)
                    ->update(['status' => 1]);
            } else {
                \Illuminate\Support\Facades\DB::table('detalle_permiso')->insert([
                    'id_rol' => $rol->id_rol,
                    'id_permiso' => $idPermiso,
                    'status' => 1,
                ]);
            }
        }

        return redirect()->route('roles.index')
            ->with('success', "Permisos del rol '{$rol->nombre_rol}' actualizados correctamente.");
    }
}
