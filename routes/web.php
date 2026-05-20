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

    Route::get('recursos/exportar-pdf', [RecursoController::class, 'exportarPdf'])
        ->name('recursos.exportarPdf')
        ->middleware('role:admin,docente');

    Route::get('recursos/exportar-excel', [RecursoController::class, 'exportarExcel'])
        ->name('recursos.exportarExcel')
        ->middleware('role:admin,docente');

    Route::resource('recursos', RecursoController::class)
        ->only(['index'])
        ->middleware('role:admin,docente')
        ->parameters(['recursos' => 'recurso']);

    Route::resource('recursos', RecursoController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('role:admin')
        ->parameters(['recursos' => 'recurso']);

    Route::get('recursos/papelera', [RecursoController::class, 'papelera'])
        ->name('recursos.papelera')
        ->middleware('role:admin');

    Route::post('recursos/{id}/restore', [RecursoController::class, 'restore'])
        ->name('recursos.restore')
        ->middleware('role:admin');

    Route::delete('recursos/{recurso}/force', [RecursoController::class, 'forceDestroy'])
        ->name('recursos.forceDestroy')
        ->middleware('role:admin');

    Route::resource('usuarios', UserController::class)
        ->middleware('role:Administrador')
        ->parameters(['usuarios' => 'usuario']);

    Route::delete('usuarios/{usuario}/force', [UserController::class, 'forceDestroy'])
        ->name('usuarios.forceDestroy')
        ->middleware('role:Administrador');

    Route::get('instituciones/papelera', [InstitucionController::class, 'papelera'])
        ->name('instituciones.papelera');

    Route::post('instituciones/{id}/restore', [InstitucionController::class, 'restore'])
        ->name('instituciones.restore');

    Route::delete('instituciones/{id}/force', [InstitucionController::class, 'forceDestroy'])
        ->name('instituciones.forceDestroy');

    Route::resource('instituciones', InstitucionController::class)->parameters([
        'instituciones' => 'institucion'
    ]);
});
