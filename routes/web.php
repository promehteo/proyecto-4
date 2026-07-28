<?php

use App\Http\Controllers\Bitacora\BitacoraIndexController;
use App\Http\Controllers\Bitacora\BitacoraShowController;
use App\Http\Controllers\Categoria\CategoriaFormController;
use App\Http\Controllers\Categoria\CategoriaIndexController;
use App\Http\Controllers\Permiso\PermisoFormController;
use App\Http\Controllers\Permiso\PermisoIndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Rol\RolFormController;
use App\Http\Controllers\Rol\RolIndexController;
use App\Http\Controllers\Rol\RolPermisoController;
use App\Http\Controllers\User\UserFormController;
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
    Route::get('/categorias/registrar', [CategoriaFormController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriaFormController::class, 'store'])->name('categorias.store');

    Route::get('/categorias/{categoria}/editar', [CategoriaFormController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{categoria}', [CategoriaFormController::class, 'update'])->name('categorias.update');
    Route::patch('/categorias/{categoria}/inactivar', [CategoriaIndexController::class, 'inactivar'])->name('categorias.inactivar');
    Route::patch('/categorias/{categoria}/activar', [CategoriaIndexController::class, 'activar'])->name('categorias.activar');
    Route::get('/categorias/{categoria}', [CategoriaIndexController::class, 'show'])->name('categorias.show');

    // Módulo 2: Bitácora (Solo Lectura)
    Route::get('/bitacora', [BitacoraIndexController::class, 'index'])->name('bitacora.index');
    Route::get('/bitacora/{bitacora}', [BitacoraShowController::class, 'show'])->name('bitacora.show');

    // Módulo 3: Roles
    Route::get('/roles', [RolIndexController::class, 'index'])->name('roles.index');
    Route::get('/roles/crear', [RolFormController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolFormController::class, 'store'])->name('roles.store');
    Route::get('/roles/{rol}/editar', [RolFormController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{rol}', [RolFormController::class, 'update'])->name('roles.update');
    Route::patch('/roles/{rol}/inactivar', [RolIndexController::class, 'inactivar'])->name('roles.inactivar');
    Route::patch('/roles/{rol}/activar', [RolIndexController::class, 'activar'])->name('roles.activar');
    Route::get('/roles/{rol}/permisos', [RolPermisoController::class, 'editPermisos'])->name('roles.permisos.edit');
    Route::put('/roles/{rol}/permisos', [RolPermisoController::class, 'updatePermisos'])->name('roles.permisos.update');

    // Módulo 3: Permisos
    Route::get('/permisos', [PermisoIndexController::class, 'index'])->name('permisos.index');
    Route::get('/permisos/crear', [PermisoFormController::class, 'create'])->name('permisos.create');
    Route::post('/permisos', [PermisoFormController::class, 'store'])->name('permisos.store');
    Route::get('/permisos/{permiso}/editar', [PermisoFormController::class, 'edit'])->name('permisos.edit');
    Route::put('/permisos/{permiso}', [PermisoFormController::class, 'update'])->name('permisos.update');
    Route::patch('/permisos/{permiso}/inactivar', [PermisoIndexController::class, 'inactivar'])->name('permisos.inactivar');
    Route::patch('/permisos/{permiso}/activar', [PermisoIndexController::class, 'activar'])->name('permisos.activar');

    // Módulo 3: Gestión de Usuarios y Roles
    Route::get('/usuarios', [UserIndexController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UserFormController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserFormController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/editar', [UserFormController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UserFormController::class, 'update'])->name('usuarios.update');
    Route::patch('/usuarios/{usuario}/inactivar', [UserIndexController::class, 'inactivar'])->name('usuarios.inactivar');
    Route::patch('/usuarios/{usuario}/activar', [UserIndexController::class, 'activar'])->name('usuarios.activar');
    Route::get('/usuarios/{usuario}/roles', [UserRolController::class, 'editRoles'])->name('usuarios.roles.edit');
    Route::put('/usuarios/{usuario}/roles', [UserRolController::class, 'updateRoles'])->name('usuarios.roles.update');

    // Validación genérica y dinámica de FormRequests
    Route::post('/api/validate/{formRequest}', [\App\Http\Controllers\SystemValidationController::class, 'validatePartial'])
        ->where('formRequest', '.*')
        ->name('api.system.validate-partial');
});

require __DIR__.'/auth.php';
