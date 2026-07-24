<?php

use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
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

    // Módulo 1: Categorías (Sin ruta destroy)
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/crear', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{categoria}/editar', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::patch('/categorias/{categoria}/inactivar', [CategoriaController::class, 'inactivar'])->name('categorias.inactivar');
    Route::patch('/categorias/{categoria}/activar', [CategoriaController::class, 'activar'])->name('categorias.activar');

    // Módulo 2: Bitácora (Solo Lectura - Sin create, store, edit, update, destroy, activar, inactivar)
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
    Route::get('/bitacora/{bitacora}', [BitacoraController::class, 'show'])->name('bitacora.show');

    // Módulo 3: Roles (Sin ruta destroy)
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/crear', [RolController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
    Route::get('/roles/{rol}/editar', [RolController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{rol}', [RolController::class, 'update'])->name('roles.update');
    Route::patch('/roles/{rol}/inactivar', [RolController::class, 'inactivar'])->name('roles.inactivar');
    Route::patch('/roles/{rol}/activar', [RolController::class, 'activar'])->name('roles.activar');
    Route::get('/roles/{rol}/permisos', [RolController::class, 'editPermisos'])->name('roles.permisos.edit');
    Route::put('/roles/{rol}/permisos', [RolController::class, 'updatePermisos'])->name('roles.permisos.update');

    // Módulo 3: Permisos (Sin ruta destroy)
    Route::get('/permisos', [PermisoController::class, 'index'])->name('permisos.index');
    Route::get('/permisos/crear', [PermisoController::class, 'create'])->name('permisos.create');
    Route::post('/permisos', [PermisoController::class, 'store'])->name('permisos.store');
    Route::get('/permisos/{permiso}/editar', [PermisoController::class, 'edit'])->name('permisos.edit');
    Route::put('/permisos/{permiso}', [PermisoController::class, 'update'])->name('permisos.update');
    Route::patch('/permisos/{permiso}/inactivar', [PermisoController::class, 'inactivar'])->name('permisos.inactivar');
    Route::patch('/permisos/{permiso}/activar', [PermisoController::class, 'activar'])->name('permisos.activar');

    // Módulo 3: Gestión de Usuarios y Roles (Sin ruta destroy)
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
    Route::patch('/usuarios/{usuario}/inactivar', [UserController::class, 'inactivar'])->name('usuarios.inactivar');
    Route::patch('/usuarios/{usuario}/activar', [UserController::class, 'activar'])->name('usuarios.activar');
    Route::get('/usuarios/{usuario}/roles', [UserController::class, 'editRoles'])->name('usuarios.roles.edit');
    Route::put('/usuarios/{usuario}/roles', [UserController::class, 'updateRoles'])->name('usuarios.roles.update');
});

require __DIR__.'/auth.php';
