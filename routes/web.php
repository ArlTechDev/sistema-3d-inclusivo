<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\UserController;

Route::get('login', [AuthController::class, 'loginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('recursos.index');
    });

    // Recursos: Rutas personalizadas ANTES del resource
    Route::get('recursos/exportar/pdf', [RecursoController::class, 'exportarPdf'])
        ->name('recursos.pdf');

    Route::get('recursos/exportar/excel', [RecursoController::class, 'exportarExcel'])
        ->name('recursos.excel');

    Route::get('recursos/papelera', [RecursoController::class, 'papelera'])
        ->name('recursos.papelera')
        ->middleware('role:Administrador');

    Route::post('recursos/{id}/restore', [RecursoController::class, 'restore'])
        ->name('recursos.restore')
        ->middleware('role:Administrador');

    Route::delete('recursos/{recurso}/force', [RecursoController::class, 'forceDestroy'])
        ->name('recursos.forceDestroy')
        ->middleware('role:Administrador');

    // Recursos: Index accesible para todos los autenticados
    Route::resource('recursos', RecursoController::class)
        ->only(['index'])
        ->parameters(['recursos' => 'recurso']);

    // Recursos: Métodos que modifican BD - Solo Administrador
    Route::resource('recursos', RecursoController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy', 'show'])
        ->middleware('role:Administrador')
        ->parameters(['recursos' => 'recurso']);

    // Instituciones: Rutas personalizadas ANTES del resource
    Route::get('instituciones/exportar/pdf', [InstitucionController::class, 'exportarPdf'])
        ->name('instituciones.pdf');

    Route::get('instituciones/exportar/excel', [InstitucionController::class, 'exportarExcel'])
        ->name('instituciones.excel');

    Route::get('instituciones/papelera', [InstitucionController::class, 'papelera'])
        ->name('instituciones.papelera')
        ->middleware('role:Administrador');

    Route::post('instituciones/{id}/restore', [InstitucionController::class, 'restore'])
        ->name('instituciones.restore')
        ->middleware('role:Administrador');

    Route::delete('instituciones/{id}/force', [InstitucionController::class, 'forceDestroy'])
        ->name('instituciones.forceDestroy')
        ->middleware('role:Administrador');

    // Instituciones: Index accesible para todos los autenticados
    Route::resource('instituciones', InstitucionController::class)
        ->only(['index'])
        ->parameters(['instituciones' => 'institucion']);

    // Instituciones: Métodos que modifican BD - Solo Administrador
    Route::resource('instituciones', InstitucionController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('role:Administrador')
        ->parameters(['instituciones' => 'institucion']);

    // Usuarios: Rutas personalizadas ANTES del resource
    Route::get('usuarios/exportar/pdf', [UserController::class, 'exportarPdf'])
        ->name('usuarios.pdf')
        ->middleware('role:Administrador');

    Route::get('usuarios/exportar/excel', [UserController::class, 'exportarExcel'])
        ->name('usuarios.excel')
        ->middleware('role:Administrador');

    Route::get('usuarios/papelera', [UserController::class, 'papelera'])
        ->name('usuarios.papelera')
        ->middleware('role:Administrador');

    Route::post('usuarios/{id}/restore', [UserController::class, 'restore'])
        ->name('usuarios.restore')
        ->middleware('role:Administrador');

    Route::delete('usuarios/{id}/force', [UserController::class, 'forceDestroy'])
        ->name('usuarios.forceDestroy')
        ->middleware('role:Administrador');

    // Usuarios: Todo protegido para Solo Administrador
    Route::resource('usuarios', UserController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('role:Administrador')
        ->parameters(['usuarios' => 'usuario']);
});
