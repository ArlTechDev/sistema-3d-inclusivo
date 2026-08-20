<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'loginForm'])->name('login')->middleware('throttle:global');
Route::post('login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:login');
Route::get('registro', [AuthController::class, 'registerForm'])->name('register')->middleware('throttle:global');
Route::post('registro', [AuthController::class, 'register'])->name('register.post')->middleware('throttle:registro');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('recursos.index');
    });

    // Pedidos: rutas personalizadas ANTES de cualquier resource
    Route::get('pedidos', [PedidoController::class, 'index'])
        ->name('pedidos.index')
        ->middleware('role:Administrador');

    Route::get('pedidos/exportar/pdf', [PedidoController::class, 'exportarPdf'])
        ->name('pedidos.pdf')
        ->middleware('role:Administrador');

    Route::get('pedidos/exportar/excel', [PedidoController::class, 'exportarExcel'])
        ->name('pedidos.excel')
        ->middleware('role:Administrador');

    Route::get('pedidos/crear', [PedidoController::class, 'create'])
        ->name('pedidos.create');

    Route::get('pedidos/mis', [PedidoController::class, 'mis'])
        ->name('pedidos.mis');

    Route::delete('pedidos/{pedido}/cancelar', [PedidoController::class, 'cancelar'])
        ->name('pedidos.cancelar');

    Route::post('pedidos', [PedidoController::class, 'store'])
        ->name('pedidos.store');

    Route::patch('pedidos/{pedido}/estado', [PedidoController::class, 'update'])
        ->name('pedidos.update')
        ->middleware('role:Administrador');

    Route::patch('pedidos/{pedido}/rechazar', [PedidoController::class, 'rechazar'])
        ->name('pedidos.rechazar')
        ->middleware('role:Administrador');

    Route::get('pedidos/{pedido}/gcode', [PedidoController::class, 'descargarGCode'])
        ->name('pedidos.gcode')
        ->middleware('role:Administrador');

    // Recursos: Rutas personalizadas ANTES del resource
    Route::get('recursos/exportar/pdf', [RecursoController::class, 'exportarPdf'])
        ->name('recursos.pdf')
        ->middleware('role:Administrador');

    Route::get('recursos/exportar/excel', [RecursoController::class, 'exportarExcel'])
        ->name('recursos.excel')
        ->middleware('role:Administrador');

    Route::get('recursos/gcode/{recurso}', [RecursoController::class, 'descargarGCode'])
        ->name('recursos.gcode')
        ->middleware('role:Administrador');

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
        ->name('instituciones.pdf')
        ->middleware('role:Administrador');

    Route::get('instituciones/exportar/excel', [InstitucionController::class, 'exportarExcel'])
        ->name('instituciones.excel')
        ->middleware('role:Administrador');

    Route::get('instituciones/papelera', [InstitucionController::class, 'papelera'])
        ->name('instituciones.papelera')
        ->middleware('role:Administrador');

    Route::post('instituciones/{id}/restore', [InstitucionController::class, 'restore'])
        ->name('instituciones.restore')
        ->middleware('role:Administrador');

    Route::delete('instituciones/{id}/force', [InstitucionController::class, 'forceDestroy'])
        ->name('instituciones.forceDestroy')
        ->middleware('role:Administrador');

    // Instituciones: Index solo Administrador (tabla de gestión, UC-05)
    Route::resource('instituciones', InstitucionController::class)
        ->only(['index'])
        ->middleware('role:Administrador')
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

    // Configuración del Sistema (Costos y Filamento): Solo Administrador
    Route::get('configuracion', [ConfiguracionController::class, 'index'])
        ->name('configuracion.index')
        ->middleware('role:Administrador');

    Route::post('configuracion', [ConfiguracionController::class, 'update'])
        ->name('configuracion.update')
        ->middleware('role:Administrador');
});
