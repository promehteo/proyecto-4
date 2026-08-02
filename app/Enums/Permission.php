<?php

declare(strict_types=1);

namespace App\Enums;

enum Permission: string
{
    // Usuarios
    case VER_USUARIOS = 'usuarios.ver';
    case CREAR_USUARIOS = 'usuarios.crear';
    case EDITAR_USUARIOS = 'usuarios.editar';
    case INACTIVAR_USUARIOS = 'usuarios.inactivar';
    case ACTIVAR_USUARIOS = 'usuarios.activar';
    case ASIGNAR_ROLES = 'usuarios.asignar_roles';

    // Roles
    case VER_ROLES = 'roles.ver';
    case CREAR_ROLES = 'roles.crear';
    case EDITAR_ROLES = 'roles.editar';
    case INACTIVAR_ROLES = 'roles.inactivar';
    case ACTIVAR_ROLES = 'roles.activar';
    case ASIGNAR_PERMISOS = 'roles.asignar_permisos';

    // Permisos
    case VER_PERMISOS = 'permisos.ver';
    case CREAR_PERMISOS = 'permisos.crear';
    case EDITAR_PERMISOS = 'permisos.editar';
    case INACTIVAR_PERMISOS = 'permisos.inactivar';
    case ACTIVAR_PERMISOS = 'permisos.activar';

    // Bitácora
    case VER_BITACORA = 'bitacora.ver';

    // Categorías
    case VER_CATEGORIAS = 'categorias.ver';
    case CREAR_CATEGORIAS = 'categorias.crear';
    case EDITAR_CATEGORIAS = 'categorias.editar';
    case INACTIVAR_CATEGORIAS = 'categorias.inactivar';
    case ACTIVAR_CATEGORIAS = 'categorias.activar';

    // Gestión híbrida base
    case GESTIONAR_USUARIOS = 'sistema.gestionar_usuarios';
    case GESTIONAR_ROLES = 'sistema.gestionar_roles';
    case GESTIONAR_PERMISOS = 'sistema.gestionar_permisos';

    public function nombre(): string
    {
        return match ($this) {
            self::VER_USUARIOS => 'Ver Usuarios',
            self::CREAR_USUARIOS => 'Crear Usuarios',
            self::EDITAR_USUARIOS => 'Editar Usuarios',
            self::INACTIVAR_USUARIOS => 'Inactivar Usuarios',
            self::ACTIVAR_USUARIOS => 'Activar Usuarios',
            self::ASIGNAR_ROLES => 'Asignar Roles a Usuarios',

            self::VER_ROLES => 'Ver Roles',
            self::CREAR_ROLES => 'Crear Roles',
            self::EDITAR_ROLES => 'Editar Roles',
            self::INACTIVAR_ROLES => 'Inactivar Roles',
            self::ACTIVAR_ROLES => 'Activar Roles',
            self::ASIGNAR_PERMISOS => 'Asignar Permisos a Roles',

            self::VER_PERMISOS => 'Ver Permisos',
            self::CREAR_PERMISOS => 'Crear Permisos',
            self::EDITAR_PERMISOS => 'Editar Permisos',
            self::INACTIVAR_PERMISOS => 'Inactivar Permisos',
            self::ACTIVAR_PERMISOS => 'Activar Permisos',

            self::VER_BITACORA => 'Ver Bitácora',

            self::VER_CATEGORIAS => 'Ver Categorías',
            self::CREAR_CATEGORIAS => 'Crear Categorías',
            self::EDITAR_CATEGORIAS => 'Editar Categorías',
            self::INACTIVAR_CATEGORIAS => 'Inactivar Categorías',
            self::ACTIVAR_CATEGORIAS => 'Activar Categorías',

            self::GESTIONAR_USUARIOS => 'Gestionar Usuarios (Sistema)',
            self::GESTIONAR_ROLES => 'Gestionar Roles (Sistema)',
            self::GESTIONAR_PERMISOS => 'Gestionar Permisos (Sistema)',
        };
    }

    public function modulo(): string
    {
        return match ($this) {
            self::VER_USUARIOS,
            self::CREAR_USUARIOS,
            self::EDITAR_USUARIOS,
            self::INACTIVAR_USUARIOS,
            self::ACTIVAR_USUARIOS,
            self::ASIGNAR_ROLES => 'usuarios',

            self::VER_ROLES,
            self::CREAR_ROLES,
            self::EDITAR_ROLES,
            self::INACTIVAR_ROLES,
            self::ACTIVAR_ROLES,
            self::ASIGNAR_PERMISOS => 'roles',

            self::VER_PERMISOS,
            self::CREAR_PERMISOS,
            self::EDITAR_PERMISOS,
            self::INACTIVAR_PERMISOS,
            self::ACTIVAR_PERMISOS => 'permisos',

            self::VER_BITACORA => 'bitacora',

            self::VER_CATEGORIAS,
            self::CREAR_CATEGORIAS,
            self::EDITAR_CATEGORIAS,
            self::INACTIVAR_CATEGORIAS,
            self::ACTIVAR_CATEGORIAS => 'categorias',

            self::GESTIONAR_USUARIOS,
            self::GESTIONAR_ROLES,
            self::GESTIONAR_PERMISOS => 'sistema',
        };
    }
}
