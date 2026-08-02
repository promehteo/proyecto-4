<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permiso;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles Base
        $rolAdmin = Rol::updateOrCreate(
            ['clave_rol' => 'admin'],
            [
                'nombre_rol' => 'Administrador',
                'status' => 1,
            ]
        );

        $rolCajero = Rol::updateOrCreate(
            ['clave_rol' => 'cajero'],
            [
                'nombre_rol' => 'Cajero',
                'status' => 1,
            ]
        );

        $rolInventario = Rol::updateOrCreate(
            ['clave_rol' => 'inventario'],
            [
                'nombre_rol' => 'Encargado de Inventario',
                'status' => 1,
            ]
        );

        // 2. Permisos Base Agrupados por Módulo
        $permisosData = [
            // Módulo usuarios
            ['nombre' => 'Ver Usuarios', 'slug' => 'usuarios.ver', 'modulo' => 'usuarios'],
            ['nombre' => 'Crear Usuarios', 'slug' => 'usuarios.crear', 'modulo' => 'usuarios'],
            ['nombre' => 'Editar Usuarios', 'slug' => 'usuarios.editar', 'modulo' => 'usuarios'],
            ['nombre' => 'Inactivar Usuarios', 'slug' => 'usuarios.inactivar', 'modulo' => 'usuarios'],
            ['nombre' => 'Activar Usuarios', 'slug' => 'usuarios.activar', 'modulo' => 'usuarios'],
            ['nombre' => 'Asignar Roles a Usuarios', 'slug' => 'usuarios.asignar_roles', 'modulo' => 'usuarios'],

            // Módulo roles
            ['nombre' => 'Ver Roles', 'slug' => 'roles.ver', 'modulo' => 'roles'],
            ['nombre' => 'Crear Roles', 'slug' => 'roles.crear', 'modulo' => 'roles'],
            ['nombre' => 'Editar Roles', 'slug' => 'roles.editar', 'modulo' => 'roles'],
            ['nombre' => 'Inactivar Roles', 'slug' => 'roles.inactivar', 'modulo' => 'roles'],
            ['nombre' => 'Activar Roles', 'slug' => 'roles.activar', 'modulo' => 'roles'],
            ['nombre' => 'Asignar Permisos a Roles', 'slug' => 'roles.asignar_permisos', 'modulo' => 'roles'],

            // Módulo categorías
            ['nombre' => 'Ver Categorías', 'slug' => 'categorias.ver', 'modulo' => 'categorias'],
            ['nombre' => 'Crear Categorías', 'slug' => 'categorias.crear', 'modulo' => 'categorias'],
            ['nombre' => 'Editar Categorías', 'slug' => 'categorias.editar', 'modulo' => 'categorias'],
            ['nombre' => 'Inactivar Categorías', 'slug' => 'categorias.inactivar', 'modulo' => 'categorias'],
            ['nombre' => 'Activar Categorías', 'slug' => 'categorias.activar', 'modulo' => 'categorias'],

            // Módulo bitácora
            ['nombre' => 'Ver Bitácora', 'slug' => 'bitacora.ver', 'modulo' => 'bitacora'],
        ];

        foreach ($permisosData as $item) {
            $permiso = Permiso::updateOrCreate(
                ['clave_permiso' => $item['slug']],
                [
                    'nombre_permiso' => $item['nombre'],
                    'modulo_permiso' => $item['modulo'],
                    'status' => 1,
                ]
            );

            // Asignar todos los permisos al rol administrador
            $rolAdmin->permisos()->syncWithoutDetaching([
                $permiso->id_permiso => ['status' => 1]
            ]);
        }

        // 3. Usuario Administrador Inicial
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@larapidito.com'],
            [
                'nombre' => 'Administrador',
                'apellido' => 'Sistema',
                'password' => Hash::make('password'),
                'status' => 1,
            ]
        );

        // Asignar rol Administrador al usuario
        $adminUser->roles()->syncWithoutDetaching([
            $rolAdmin->id_rol => ['status' => 1]
        ]);

        // Sincronizar automáticamente los permisos del Enum con la base de datos
        \Illuminate\Support\Facades\Artisan::call('permissions:sync');
    }
}
