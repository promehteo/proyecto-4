<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Permiso;
use App\Models\PermisoRol;
use App\Models\Rol;
use App\Models\RolUsuario;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles Base (Sin delete ni truncate, usando updateOrCreate)
        $rolAdmin = Rol::updateOrCreate(
            ['slug_rol' => 'admin'],
            [
                'nombre_rol' => 'Administrador',
                'descripcion_rol' => 'Acceso total al sistema de inventario',
                'status' => 1,
            ]
        );

        $rolCajero = Rol::updateOrCreate(
            ['slug_rol' => 'cajero'],
            [
                'nombre_rol' => 'Cajero',
                'descripcion_rol' => 'Gestión de ventas y caja',
                'status' => 1,
            ]
        );

        $rolInventario = Rol::updateOrCreate(
            ['slug_rol' => 'inventario'],
            [
                'nombre_rol' => 'Encargado de Inventario',
                'descripcion_rol' => 'Gestión de stock, productos y categorías',
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

            // Módulo permisos
            ['nombre' => 'Ver Permisos', 'slug' => 'permisos.ver', 'modulo' => 'permisos'],
            ['nombre' => 'Crear Permisos', 'slug' => 'permisos.crear', 'modulo' => 'permisos'],
            ['nombre' => 'Editar Permisos', 'slug' => 'permisos.editar', 'modulo' => 'permisos'],
            ['nombre' => 'Inactivar Permisos', 'slug' => 'permisos.inactivar', 'modulo' => 'permisos'],
            ['nombre' => 'Activar Permisos', 'slug' => 'permisos.activar', 'modulo' => 'permisos'],

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
                ['slug_permiso' => $item['slug']],
                [
                    'nombre_permiso' => $item['nombre'],
                    'modulo_permiso' => $item['modulo'],
                    'descripcion_permiso' => "Permiso para {$item['nombre']}",
                    'status' => 1,
                ]
            );

            // Asignar todos los permisos al rol administrador con status = 1
            PermisoRol::updateOrCreate(
                [
                    'id_rol_permiso_rol' => $rolAdmin->id_rol,
                    'id_permiso_permiso_rol' => $permiso->id_permiso,
                ],
                [
                    'status' => 1,
                ]
            );
        }

        // 3. Usuario Administrador Inicial
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@larapidito.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'status' => 1,
            ]
        );

        // Asignar rol Administrador al usuario
        RolUsuario::updateOrCreate(
            [
                'id_usuario_rol_usuario' => $adminUser->id,
                'id_rol_rol_usuario' => $rolAdmin->id_rol,
            ],
            [
                'status' => 1,
            ]
        );

        // 4. Categorías de ejemplo para Alimentos y Bebidas
        $categoriasEjemplo = [
            'Panadería' => 'Panes frescos, pasteles y bollería',
            'Cárnicos' => 'Cortes de carne, embutidos y aves',
            'Lácteos' => 'Leches, quesos, yogures y mantequillas',
            'Salsas' => 'Salsas preparadas, aderezos y condimentos',
            'Vegetales' => 'Verduras, hortalizas y legumbres frescas',
            'Abarrotes' => 'Productos secos, granos y enlatados',
            'Aceites y grasas' => 'Aceites vegetales, de oliva y mantecas',
            'Empaques' => 'Cajas, bolsas y envases de empaque',
            'Descartables' => 'Vasos, platos, cubiertos y servilletas desechables',
        ];

        foreach ($categoriasEjemplo as $nombre => $descripcion) {
            Categoria::updateOrCreate(
                ['nombre_categoria' => $nombre],
                [
                    'descripcion_categoria' => $descripcion,
                    'id_categoria_padre_categoria' => null,
                    'status' => 1,
                ]
            );
        }
    }
}
