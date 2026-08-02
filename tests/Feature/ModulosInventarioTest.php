<?php

declare(strict_types=1);

use App\Models\Bitacora;
use App\Models\Categoria;
use App\Models\Permiso;
use App\Models\Producto;
use App\Models\Rol;
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
        'nombre' => 'Usuario Sin',
        'apellido' => 'Permisos',
        'cedula' => 99999999,
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
            'clave_rol' => 'nuevo.rol',
            'status' => 1,
        ])
        ->assertForbidden();
});

test('usuario sin permiso no puede editar roles', function () {
    $rol = Rol::first();
    $this->actingAs($this->userSinPermiso)
        ->put(route('roles.update', $rol), [
            'nombre_rol' => 'Edit Rol',
            'clave_rol' => $rol->clave_rol,
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

test('usuario sin permiso no puede ver usuarios', function () {
    $this->actingAs($this->userSinPermiso)
        ->get(route('usuarios.index'))
        ->assertForbidden();
});

test('usuario sin permiso no puede crear usuarios', function () {
    $this->actingAs($this->userSinPermiso)
        ->post(route('usuarios.store'), [
            'nombre' => 'Test',
            'apellido' => 'User',
            'email' => 't@t.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'status' => 1,
        ])
        ->assertForbidden();
});

test('usuario sin permiso no puede editar usuarios', function () {
    $u = User::first();
    $this->actingAs($this->userSinPermiso)
        ->put(route('usuarios.update', $u), [
            'nombre' => 'Test',
            'apellido' => 'User',
            'email' => 't@t.com',
            'status' => 1,
        ])
        ->assertForbidden();
});

test('usuario sin permiso no puede inactivar usuarios', function () {
    $u = User::first();
    $this->actingAs($this->userSinPermiso)
        ->patch(route('usuarios.inactivar', $u))
        ->assertForbidden();
});

test('usuario sin permiso no puede asignar roles a usuarios', function () {
    $u = User::first();
    $this->actingAs($this->userSinPermiso)
        ->put(route('usuarios.roles.update', $u), [
            'roles' => [],
        ])
        ->assertForbidden();
});

// =========================================================================
// PRUEBAS DE MÓDULO 1: CATEGORÍAS (JERÁRQUICAS, BITÁCORA, REGLAS)
// =========================================================================

test('registrar bitácora al crear, editar, inactivar y activar categoría', function () {
    // 1. Crear
    $this->actingAs($this->adminUser)
        ->post(route('categorias.store'), [
            'nombre_categoria' => 'Panadería',
            'status' => 1,
        ])
        ->assertRedirect(route('categorias.index'));

    $categoria = Categoria::where('nombre_categoria', 'Panadería')->first();
    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'creación de categoría',
        'registro_id_bitacora' => $categoria->id_categoria,
    ]);

    // 2. Editar
    $this->actingAs($this->adminUser)
        ->put(route('categorias.update', $categoria), [
            'nombre_categoria' => 'Panadería y Pastelería',
            'status' => 1,
        ])
        ->assertRedirect(route('categorias.index'));

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'edición de categoría',
        'registro_id_bitacora' => $categoria->id_categoria,
    ]);

    // 3. Inactivar
    $this->actingAs($this->adminUser)
        ->patch(route('categorias.inactivar', $categoria))
        ->assertRedirect(route('categorias.index'));

    $this->assertDatabaseHas('categoria', [
        'id_categoria' => $categoria->id_categoria,
        'status' => 2,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'inactivación de categoría',
        'registro_id_bitacora' => $categoria->id_categoria,
    ]);

    // 4. Activar
    $this->actingAs($this->adminUser)
        ->patch(route('categorias.activar', $categoria))
        ->assertRedirect(route('categorias.index'));

    $this->assertDatabaseHas('categoria', [
        'id_categoria' => $categoria->id_categoria,
        'status' => 1,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'activación de categoría',
        'registro_id_bitacora' => $categoria->id_categoria,
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
        valoresNuevos: null
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
            'clave_rol' => 'supervisor',
            'status' => 1,
        ])
        ->assertRedirect(route('roles.index'));

    $rol = Rol::where('clave_rol', 'supervisor')->first();
    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'creación de rol',
        'registro_id_bitacora' => $rol->id_rol,
    ]);

    // 2. Editar
    $this->actingAs($this->adminUser)
        ->put(route('roles.update', $rol), [
            'nombre_rol' => 'Supervisor General',
            'clave_rol' => 'supervisor',
            'status' => 1,
        ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'edición de rol',
        'registro_id_bitacora' => $rol->id_rol,
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
        'registro_id_bitacora' => $rol->id_rol,
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
        'registro_id_bitacora' => $rol->id_rol,
    ]);
});

test('registrar bitácora al cambiar permisos de rol sin eliminación física pivote', function () {
    $rol = Rol::create(['nombre_rol' => 'Tester', 'clave_rol' => 'tester', 'status' => 1]);
    $permiso1 = Permiso::first();
    $permiso2 = Permiso::skip(1)->first();

    // Asignar permiso1 y permiso2
    $this->actingAs($this->adminUser)
        ->put(route('roles.permisos.update', $rol), [
            'permisos' => [$permiso1->id_permiso, $permiso2->id_permiso],
        ]);

    $this->assertDatabaseHas('detalle_permiso', [
        'id_rol' => $rol->id_rol,
        'id_permiso' => $permiso1->id_permiso,
        'status' => 1,
    ]);

    // Desmarcar permiso2
    $this->actingAs($this->adminUser)
        ->put(route('roles.permisos.update', $rol), [
            'permisos' => [$permiso1->id_permiso],
        ]);

    // Aserción Pivote Inactivo (status = 2)
    $this->assertDatabaseHas('detalle_permiso', [
        'id_rol' => $rol->id_rol,
        'id_permiso' => $permiso2->id_permiso,
        'status' => 2,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'Actualización de permisos',
        'registro_id_bitacora' => $rol->id_rol,
    ]);
});

test('registrar bitácora al cambiar roles de usuario sin eliminación física pivote', function () {
    $user = User::create([
        'nombre' => 'Empleado',
        'apellido' => '1',
        'cedula' => 11111111,
        'email' => 'emp1@test.com',
        'password' => Hash::make('password'),
        'status' => 1
    ]);
    $rolCajero = Rol::where('clave_rol', 'cajero')->first();
    $rolInventario = Rol::where('clave_rol', 'inventario')->first();

    // Asignar cajero e inventario
    $this->actingAs($this->adminUser)
        ->put(route('usuarios.roles.update', $user), [
            'roles' => [$rolCajero->id_rol, $rolInventario->id_rol],
        ]);

    $this->assertDatabaseHas('detalle_rol', [
        'id_usuario' => $user->id_user,
        'id_rol' => $rolCajero->id_rol,
        'status' => 1,
    ]);

    // Quitar rol inventario
    $this->actingAs($this->adminUser)
        ->put(route('usuarios.roles.update', $user), [
            'roles' => [$rolCajero->id_rol],
        ]);

    // Pivote inactivo
    $this->assertDatabaseHas('detalle_rol', [
        'id_usuario' => $user->id_user,
        'id_rol' => $rolInventario->id_rol,
        'status' => 2,
    ]);

    $this->assertDatabaseHas('bitacora', [
        'accion_bitacora' => 'asignación de roles a usuario',
        'registro_id_bitacora' => $user->id_user,
    ]);
});

test('no permitir eliminación física de rol o usuario', function () {
    $rol = Rol::first();

    $this->actingAs($this->adminUser)->delete("/roles/{$rol->id_rol}")->assertStatus(405);
    $this->actingAs($this->adminUser)->delete("/usuarios/{$this->userSinPermiso->id_user}")->assertStatus(405);
});
