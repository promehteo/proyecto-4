<?php

use App\Http\Controllers\Bitacora\BitacoraIndexController;
use App\Http\Controllers\Bitacora\BitacoraShowController;
use App\Http\Controllers\Categoria\CategoriaGesController;
use App\Http\Controllers\Categoria\CategoriaIndexController;
use App\Http\Controllers\Permiso\PermisoGesController;
use App\Http\Controllers\Permiso\PermisoIndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Rol\RolGesController;
use App\Http\Controllers\Rol\RolIndexController;
use App\Http\Controllers\Rol\RolPermisoController;
use App\Http\Controllers\User\UserGesController;
use App\Http\Controllers\User\UserIndexController;
use App\Http\Controllers\User\UserRolController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Módulo 1: Categorías
    Route::get('/categorias', [CategoriaIndexController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/crear', [CategoriaGesController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriaGesController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{categoria}/editar', [CategoriaGesController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{categoria}', [CategoriaGesController::class, 'update'])->name('categorias.update');
    Route::patch('/categorias/{categoria}/inactivar', [CategoriaIndexController::class, 'inactivar'])->name('categorias.inactivar');
    Route::patch('/categorias/{categoria}/activar', [CategoriaIndexController::class, 'activar'])->name('categorias.activar');

    // Módulo 2: Bitácora (Solo Lectura)
    Route::get('/bitacora', [BitacoraIndexController::class, 'index'])->name('bitacora.index');
    Route::get('/bitacora/{bitacora}', [BitacoraShowController::class, 'show'])->name('bitacora.show');

    // Módulo 3: Roles
    Route::get('/roles', [RolIndexController::class, 'index'])->name('roles.index');
    Route::get('/roles/crear', [RolGesController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolGesController::class, 'store'])->name('roles.store');
    Route::get('/roles/{rol}/editar', [RolGesController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{rol}', [RolGesController::class, 'update'])->name('roles.update');
    Route::patch('/roles/{rol}/inactivar', [RolIndexController::class, 'inactivar'])->name('roles.inactivar');
    Route::patch('/roles/{rol}/activar', [RolIndexController::class, 'activar'])->name('roles.activar');
    Route::get('/roles/{rol}/permisos', [RolPermisoController::class, 'editPermisos'])->name('roles.permisos.edit');
    Route::put('/roles/{rol}/permisos', [RolPermisoController::class, 'updatePermisos'])->name('roles.permisos.update');

    // Módulo 3: Permisos
    Route::get('/permisos', [PermisoIndexController::class, 'index'])->name('permisos.index');
    Route::get('/permisos/crear', [PermisoGesController::class, 'create'])->name('permisos.create');
    Route::post('/permisos', [PermisoGesController::class, 'store'])->name('permisos.store');
    Route::get('/permisos/{permiso}/editar', [PermisoGesController::class, 'edit'])->name('permisos.edit');
    Route::put('/permisos/{permiso}', [PermisoGesController::class, 'update'])->name('permisos.update');
    Route::patch('/permisos/{permiso}/inactivar', [PermisoIndexController::class, 'inactivar'])->name('permisos.inactivar');
    Route::patch('/permisos/{permiso}/activar', [PermisoIndexController::class, 'activar'])->name('permisos.activar');

    // Módulo 3: Gestión de Usuarios y Roles
    Route::get('/usuarios', [UserIndexController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UserGesController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserGesController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/editar', [UserGesController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UserGesController::class, 'update'])->name('usuarios.update');
    Route::patch('/usuarios/{usuario}/inactivar', [UserIndexController::class, 'inactivar'])->name('usuarios.inactivar');
    Route::patch('/usuarios/{usuario}/activar', [UserIndexController::class, 'activar'])->name('usuarios.activar');
    Route::get('/usuarios/{usuario}/roles', [UserRolController::class, 'editRoles'])->name('usuarios.roles.edit');
    Route::put('/usuarios/{usuario}/roles', [UserRolController::class, 'updateRoles'])->name('usuarios.roles.update');
});

require __DIR__.'/auth.php';
