<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Permission;
use App\Models\Bitacora;
use App\Models\Permiso;
use Illuminate\Console\Command;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza los permisos definidos en los Enums de PHP con la base de datos';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Sincronizando permisos...');

        $actuales = [];
        foreach (Permission::cases() as $permission) {
            $permiso = Permiso::updateOrCreate(
                ['clave_permiso' => $permission->value],
                [
                    'nombre_permiso' => $permission->nombre(),
                    'modulo_permiso' => $permission->modulo(),
                    'status' => 1,
                ]
            );
            $actuales[] = $permiso->id_permiso;
        }

        // Inactivar permisos en DB que ya no existan en el Enum para mantener sincronía
        Permiso::whereNotIn('id_permiso', $actuales)->update(['status' => 2]);

        // Asignar todos los permisos activos del Enum al rol 'admin'
        $rolAdmin = \App\Models\Rol::where('clave_rol', 'admin')->first();
        if ($rolAdmin) {
            foreach ($actuales as $idPermiso) {
                $rolAdmin->permisos()->syncWithoutDetaching([
                    $idPermiso => ['status' => 1]
                ]);
                \Illuminate\Support\Facades\DB::table('detalle_permiso')
                    ->where('id_rol', $rolAdmin->id_rol)
                    ->where('id_permiso', $idPermiso)
                    ->update(['status' => 1]);
            }
        }

        // Registrar en bitácora
        Bitacora::create([
            'id_usuario_bitacora' => null,
            'accion_bitacora' => 'Sincronización de permisos',
            'modulo_bitacora' => 'Permisos',
            'registro_id_bitacora' => 0,
            'valores_anteriores_bitacora' => null,
            'valores_nuevos_bitacora' => null,
            'ip_bitacora' => '127.0.0.1',
            'navegador_bitacora' => 'Artisan Console',
            'url_bitacora' => 'CLI',
            'metodo_bitacora' => 'CLI',
            'fecha_bitacora' => now(),
            'status' => 1,
        ]);

        $this->info('Permisos sincronizados correctamente y registrados en bitácora.');
    }
}
