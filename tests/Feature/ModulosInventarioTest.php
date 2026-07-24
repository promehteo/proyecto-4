<?php

declare(strict_types=1);

use App\Models\Bitacora;
use App\Models\Categoria;
use App\Models\Permiso;
use App\Models\PermisoRol;
use App\Models\Producto;
use App\Models\Rol;
use App\Models\RolUsuario;
use App\Models\User;
use App\Services\BitacoraService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seeder base
    $this->seed();

    $this->adminUser = User::where('email', 'admin@larapidito.com')->first();

    // Crear un usuario sin permisos
    $this->userSinPermiso = User::create([
        'name' => 'Usuario Sin Permisos',
        'email' => 'sinpermiso@larapidito.com',
        'password' => Hash::make('password'),
        'status' => 1,
    ]);
});

// =========================================================================
// PRUEBAS DE ACCESO Y AUTORIZACIÓN (NO AUTENTICADO / SIN PERMISOS)
// =========================================================================

test('usuario no autenticado no puede acceder a módulos', function () {
    $this->get(route('categorias.index'))->assertRedirect(route('login'));
    $this->get(route('bitacora.index'))->assertRedirect(route('login'));
    $this->get(route('roles.index'))->assertRedirect(route('login'));
    $this->get(route('permisos.index'))->assertRedirect(route('login'));
    $this->get(route('usuarios.index'))->assertRedirect(route('login'));
});

test('usuario sin permiso no puede ver categorías', function () {
    $this->actingAs($this->userSinPermiso)
        ->get(route('categorias.index'))
        ->assertForbidden();
});

test('usuario sin permiso no puede crear categorías', function () {
    $this->actingAs($this->userSinPermiso)
        ->post(route('categorias.store'), [
            'nombre_categoria' => 'Prueba Sin Permiso',
            'status' => 1,
        ])
        ->assertForbidden();
});

test('usuario sin permiso no puede editar categorías', function () {
    $categoria = Categoria::first();
    $this->actingAs($this->userSinPermiso)
        ->put(route('categorias.update', $categoria), [
            'nombre_categoria' => 'Prueba Edit',
            'status' => 1,
        ])
        ->assertForbidden();
});

test('usuario sin permiso no puede inactivar categorías', function () {
    $categoria = Categoria::first();
    $this->actingAs($this->userSinPermiso)
        ->patch(route('categorias.inactivar', $categoria))
        ->assertForbidden();
});

test('usuario sin permiso no puede activar categorías', function () {
    $categoria = Categoria::first();
    $categoria->update(['status' => 2]);

    $this->actingAs($this->userSinPermiso)
        ->patch(route('categorias.activar', $categoria))
        ->assertForbidden();
});

test('usuario sin permiso no puede ver bitácora', function () {
    $this->actingAs($this->userSinPermiso)
        ->get(route('bitacora.index'))
        ->assertForbidden();
});

test('usuario sin permiso no puede ver roles', function () {
    $this->actingAs($this->userSinPermiso)
        ->get(route('roles.index'))
        ->assertForbidden();
});

test('usuario sin permiso no puede crear roles', function () {
    $this->actingAs($this->userSinPermiso)
        ->post(route('roles.store'), [
            'nombre_rol' => 'Nuevo Rol',
            'slug_rol' => 'nuevo.rol',
            'status' => 1,
        ])
        ->assertForbidden();
});

test('usuario sin permiso no puede editar roles', function () {
    $rol = Rol::first();
    $this->actingAs($this->userSinPermiso)
        ->put(route('roles.update', $rol), [
            'nombre_rol' => 'Edit Rol',
            'slug_rol' => $rol->slug_rol,
            'status' => 1,
        ])
        ->assertForbidden();
});

test('usuario sin permiso no puede inactivar roles', function () {
    $rol = Rol::first();
    $this->actingAs($this->userSinPermiso)
        ->patch(route('roles.inactivar', $rol))
        ->assertForbidden();
});

test('usuario sin permiso no puede asignar permisos a roles', function () {
    $rol = Rol::first();
    $this->actingAs($this->userSinPermiso)
        ->put(route('roles.permisos.update', $rol), [
            'permisos' => [],
        ])
        ->assertForbidden();
});

test('usuario sin permiso no puede asignar roles a usuarios', function () {
    $this->actingAs($this->userSinPermiso)
        ->put(route('usuarios.roles.update', $this->adminUser), [
            'roles' => [],
        ])
        ->assertForbidden();
});

test('administrador puede acceder a todo', function () {
    $this->actingAs($this->adminUser)
        ->get(route('categorias.index'))
        ->assertOk();

    $this->actingAs($this->adminUser)
        ->get(route('bitacora.index'))
        ->assertOk();

    $this->actingAs($this->adminUser)
        ->get(route('roles.index'))
        ->assertOk();

    $this->actingAs($this->adminUser)
        ->get(route('permisos.index'))
        ->assertOk();

    $this->actingAs($this->adminUser)
        ->get(route('usuarios.index'))
        ->assertOk();
});

// =========================================================================
// PRUEBAS DE MÓDULO 1: CATEGORÍAS
// =========================================================================

test('crear categoría correctamente y registrar en bitácora', function () {
    $this->actingAs($this->adminUser)
        ->post(route('categorias.store'), [
            'nombre_categoria' => 'Bebidas Energéticas',
            'descripcion_categoria' => 'Bebidas con cafeína y taurina',
            'status' => 1,
        ])
        ->assertRedirect(route('categorias.index'));

    $this->assertDatabaseHas('categoria', [
        'nombre_categoria' => 'Bebidas Energéticas',
        'status' => 1,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'creación de categoría',
        'auditable_tipo_bitacora' => Categoria::class,
    ]);
});

test('editar categoría correctamente y registrar en bitácora', function () {
    $categoria = Categoria::where('nombre_categoria', 'Panadería')->first();

    $this->actingAs($this->adminUser)
        ->put(route('categorias.update', $categoria), [
            'nombre_categoria' => 'Panadería y Pastelería',
            'descripcion_categoria' => 'Panes y pasteles finos',
            'status' => 1,
        ])
        ->assertRedirect(route('categorias.index'));

    $this->assertDatabaseHas('categoria', [
        'id_categoria' => $categoria->id_categoria,
        'nombre_categoria' => 'Panadería y Pastelería',
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'edición de categoría',
        'auditable_id_bitacora' => $categoria->id_categoria,
    ]);
});

test('inactivar categoría correctamente y registrar en bitácora', function () {
    $categoria = Categoria::where('nombre_categoria', 'Salsas')->first();

    $this->actingAs($this->adminUser)
        ->patch(route('categorias.inactivar', $categoria))
        ->assertRedirect(route('categorias.index'));

    // Aserción de Inactivación Lógica (sin eliminación física)
    $this->assertDatabaseHas('categoria', [
        'id_categoria' => $categoria->id_categoria,
        'status' => 2,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'inactivación de categoría',
        'auditable_id_bitacora' => $categoria->id_categoria,
    ]);
});

test('activar categoría correctamente y registrar en bitácora', function () {
    $categoria = Categoria::where('nombre_categoria', 'Vegetales')->first();
    $categoria->update(['status' => 2]);

    $this->actingAs($this->adminUser)
        ->patch(route('categorias.activar', $categoria))
        ->assertRedirect(route('categorias.index'));

    $this->assertDatabaseHas('categoria', [
        'id_categoria' => $categoria->id_categoria,
        'status' => 1,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'activación de categoría',
        'auditable_id_bitacora' => $categoria->id_categoria,
    ]);
});

test('no permitir que una categoría sea su propio padre', function () {
    $categoria = Categoria::where('nombre_categoria', 'Panadería')->first();

    $this->actingAs($this->adminUser)
        ->put(route('categorias.update', $categoria), [
            'nombre_categoria' => 'Panadería',
            'id_categoria_padre_categoria' => $categoria->id_categoria,
            'status' => 1,
        ])
        ->assertSessionHasErrors(['id_categoria_padre_categoria']);
});

test('no permitir jerarquía circular', function () {
    $padre = Categoria::create(['nombre_categoria' => 'Padre', 'status' => 1]);
    $hijo = Categoria::create(['nombre_categoria' => 'Hijo', 'id_categoria_padre_categoria' => $padre->id_categoria, 'status' => 1]);

    // Intentar asignar el hijo como padre del padre
    $this->actingAs($this->adminUser)
        ->put(route('categorias.update', $padre), [
            'nombre_categoria' => 'Padre Modificado',
            'id_categoria_padre_categoria' => $hijo->id_categoria,
            'status' => 1,
        ])
        ->assertSessionHasErrors(['id_categoria_padre_categoria']);
});

test('no permitir inactivar categoría con productos activos', function () {
    $categoria = Categoria::where('nombre_categoria', 'Lácteos')->first();
    $tipoProd = \Illuminate\Support\Facades\DB::table('tipo_producto')->insertGetId([
        'nombre_tipo_producto' => 'Tipo Prueba',
        'status' => 1,
    ]);
    $unidadMed = \Illuminate\Support\Facades\DB::table('unidad_medida')->insertGetId([
        'nombre_unidad_medida' => 'Unidad Prueba',
        'simbolo_unidad_medida' => 'u',
        'status' => 1,
    ]);

    Producto::create([
        'id_categoria_producto' => $categoria->id_categoria,
        'id_tipo_producto_producto' => $tipoProd,
        'id_unidad_medida_producto' => $unidadMed,
        'codigo_interno_producto' => 'PROD-TEST-1',
        'nombre_producto' => 'Leche Entera',
        'status' => 1,
    ]);

    $this->actingAs($this->adminUser)
        ->patch(route('categorias.inactivar', $categoria))
        ->assertSessionHasErrors(['id_categoria']);

    $this->assertDatabaseHas('categoria', [
        'id_categoria' => $categoria->id_categoria,
        'status' => 1,
    ]);
});

test('no permitir inactivar categoría con categorías hijas activas', function () {
    $padre = Categoria::create(['nombre_categoria' => 'Bebidas', 'status' => 1]);
    Categoria::create(['nombre_categoria' => 'Jugos', 'id_categoria_padre_categoria' => $padre->id_categoria, 'status' => 1]);

    $this->actingAs($this->adminUser)
        ->patch(route('categorias.inactivar', $padre))
        ->assertSessionHasErrors(['id_categoria']);

    $this->assertDatabaseHas('categoria', [
        'id_categoria' => $padre->id_categoria,
        'status' => 1,
    ]);
});

test('no permitir eliminación física de categoría', function () {
    $categoria = Categoria::first();

    $this->actingAs($this->adminUser)
        ->delete("/categorias/{$categoria->id_categoria}")
        ->assertStatus(405); // La ruta DELETE no está soportada (MethodNotAllowed)
});

// =========================================================================
// PRUEBAS DE MÓDULO 2: BITÁCORA DE AUDITORÍA
// =========================================================================

test('no permitir editar ni eliminar bitácora', function () {
    $log = BitacoraService::registrar(
        auditable: 'Prueba',
        accion: 'test',
        valoresAnteriores: null,
        valoresNuevos: null,
        descripcion: 'Prueba'
    );

    $this->actingAs($this->adminUser)
        ->put("/bitacora/{$log->id_bitacora}", ['accion' => 'hack'])
        ->assertStatus(405);

    $this->actingAs($this->adminUser)
        ->delete("/bitacora/{$log->id_bitacora}")
        ->assertStatus(405);
});

// =========================================================================
// PRUEBAS DE MÓDULO 3: ROLES Y PERMISOS Y USUARIOS
// =========================================================================

test('registrar bitácora al crear, editar, inactivar y activar rol', function () {
    // 1. Crear
    $this->actingAs($this->adminUser)
        ->post(route('roles.store'), [
            'nombre_rol' => 'Supervisor',
            'slug_rol' => 'supervisor',
            'descripcion_rol' => 'Supervisor de tienda',
            'status' => 1,
        ])
        ->assertRedirect(route('roles.index'));

    $rol = Rol::where('slug_rol', 'supervisor')->first();
    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'creación de rol',
        'auditable_id_bitacora' => $rol->id_rol,
    ]);

    // 2. Editar
    $this->actingAs($this->adminUser)
        ->put(route('roles.update', $rol), [
            'nombre_rol' => 'Supervisor General',
            'slug_rol' => 'supervisor',
            'status' => 1,
        ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'edición de rol',
        'auditable_id_bitacora' => $rol->id_rol,
    ]);

    // 3. Inactivar
    $this->actingAs($this->adminUser)
        ->patch(route('roles.inactivar', $rol));

    $this->assertDatabaseHas('rol', [
        'id_rol' => $rol->id_rol,
        'status' => 2,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'inactivación de rol',
        'auditable_id_bitacora' => $rol->id_rol,
    ]);

    // 4. Activar
    $this->actingAs($this->adminUser)
        ->patch(route('roles.activar', $rol));

    $this->assertDatabaseHas('rol', [
        'id_rol' => $rol->id_rol,
        'status' => 1,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'activación de rol',
        'auditable_id_bitacora' => $rol->id_rol,
    ]);
});

test('registrar bitácora al crear, editar, inactivar y activar permiso', function () {
    // 1. Crear
    $this->actingAs($this->adminUser)
        ->post(route('permisos.store'), [
            'nombre_permiso' => 'Exportar Reportes',
            'slug_permiso' => 'reportes.exportar',
            'modulo_permiso' => 'reportes',
            'status' => 1,
        ]);

    $permiso = Permiso::where('slug_permiso', 'reportes.exportar')->first();
    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'creación de permiso',
        'auditable_id_bitacora' => $permiso->id_permiso,
    ]);

    // 2. Editar
    $this->actingAs($this->adminUser)
        ->put(route('permisos.update', $permiso), [
            'nombre_permiso' => 'Exportar Todo',
            'slug_permiso' => 'reportes.exportar',
            'modulo_permiso' => 'reportes',
            'status' => 1,
        ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'edición de permiso',
        'auditable_id_bitacora' => $permiso->id_permiso,
    ]);

    // 3. Inactivar
    $this->actingAs($this->adminUser)
        ->patch(route('permisos.inactivar', $permiso));

    $this->assertDatabaseHas('permiso', [
        'id_permiso' => $permiso->id_permiso,
        'status' => 2,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'inactivación de permiso',
        'auditable_id_bitacora' => $permiso->id_permiso,
    ]);

    // 4. Activar
    $this->actingAs($this->adminUser)
        ->patch(route('permisos.activar', $permiso));

    $this->assertDatabaseHas('permiso', [
        'id_permiso' => $permiso->id_permiso,
        'status' => 1,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'activación de permiso',
        'auditable_id_bitacora' => $permiso->id_permiso,
    ]);
});

test('registrar bitácora al cambiar permisos de rol sin eliminación física pivote', function () {
    $rol = Rol::create(['nombre_rol' => 'Tester', 'slug_rol' => 'tester', 'status' => 1]);
    $permiso1 = Permiso::first();
    $permiso2 = Permiso::skip(1)->first();

    // Asignar permiso1 y permiso2
    $this->actingAs($this->adminUser)
        ->put(route('roles.permisos.update', $rol), [
            'permisos' => [$permiso1->id_permiso, $permiso2->id_permiso],
        ]);

    $this->assertDatabaseHas('permiso_rol', [
        'id_rol_permiso_rol' => $rol->id_rol,
        'id_permiso_permiso_rol' => $permiso1->id_permiso,
        'status' => 1,
    ]);

    // Desmarcar permiso2
    $this->actingAs($this->adminUser)
        ->put(route('roles.permisos.update', $rol), [
            'permisos' => [$permiso1->id_permiso],
        ]);

    // Aserción Pivote Inactivo (status = 2)
    $this->assertDatabaseHas('permiso_rol', [
        'id_rol_permiso_rol' => $rol->id_rol,
        'id_permiso_permiso_rol' => $permiso2->id_permiso,
        'status' => 2,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'asignación de permisos a rol',
        'auditable_id_bitacora' => $rol->id_rol,
    ]);
});

test('registrar bitácora al cambiar roles de usuario sin eliminación física pivote', function () {
    $user = User::create(['name' => 'Empleado 1', 'email' => 'emp1@test.com', 'password' => Hash::make('password'), 'status' => 1]);
    $rolCajero = Rol::where('slug_rol', 'cajero')->first();
    $rolInventario = Rol::where('slug_rol', 'inventario')->first();

    // Asignar cajero e inventario
    $this->actingAs($this->adminUser)
        ->put(route('usuarios.roles.update', $user), [
            'roles' => [$rolCajero->id_rol, $rolInventario->id_rol],
        ]);

    $this->assertDatabaseHas('rol_usuario', [
        'id_usuario_rol_usuario' => $user->id,
        'id_rol_rol_usuario' => $rolCajero->id_rol,
        'status' => 1,
    ]);

    // Quitar rol inventario
    $this->actingAs($this->adminUser)
        ->put(route('usuarios.roles.update', $user), [
            'roles' => [$rolCajero->id_rol],
        ]);

    // Pivote inactivo
    $this->assertDatabaseHas('rol_usuario', [
        'id_usuario_rol_usuario' => $user->id,
        'id_rol_rol_usuario' => $rolInventario->id_rol,
        'status' => 2,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'asignación de roles a usuario',
        'auditable_id_bitacora' => $user->id,
    ]);
});

test('no permitir eliminación física de rol, permiso o usuario', function () {
    $rol = Rol::first();
    $permiso = Permiso::first();

    $this->actingAs($this->adminUser)->delete("/roles/{$rol->id_rol}")->assertStatus(405);
    $this->actingAs($this->adminUser)->delete("/permisos/{$permiso->id_permiso}")->assertStatus(405);
    $this->actingAs($this->adminUser)->delete("/usuarios/{$this->userSinPermiso->id}")->assertStatus(405);
});
