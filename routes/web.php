<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ControladorUsuarios;
use App\Http\Controllers\ControladorRoles;
use App\Http\Controllers\ControladorFacturacion;
use App\Http\Controllers\ControladorAuth;

Route::get('/', function () {
    return view('welcome');
});

// RUTAS PÚBLICAS
Route::get('/login', function () {
    return view('modulos.login');
})->name('login');

Route::post('/login', [ControladorAuth::class, 'ctrIngresarUsuario'])->name('login.procesar');

// RUTAS PROTEGIDAS
Route::get('/logout', [ControladorAuth::class, 'logout'])->name('logout');

Route::get('/inicio', function () { return view('modulos.inicio'); })->name('inicio');

Route::get('/categorias', [CategoriaController::class, 'mostrar'])->name('categorias.index');
Route::post('/categorias/crear', [CategoriaController::class, 'crear'])->name('categorias.crear');
Route::post('/categorias/editar', [CategoriaController::class, 'editar'])->name('categorias.editar');
Route::post('/categorias/eliminar', [CategoriaController::class, 'eliminar'])->name('categorias.eliminar');
Route::get('/productos', [ProductoController::class, 'mostrar'])->name('productos.index');
Route::post('/productos/crear', [ProductoController::class, 'crear'])->name('productos.crear');
Route::post('/productos/editar', [ProductoController::class, 'editar'])->name('productos.editar');
Route::post('/productos/eliminar', [ProductoController::class, 'eliminar'])->name('productos.eliminar');
Route::get('/usuarios', [ControladorUsuarios::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/crear', [ControladorUsuarios::class, 'create'])->name('usuarios.create');
Route::post('/usuarios/crear', [ControladorUsuarios::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/editar/{id}', [ControladorUsuarios::class, 'edit'])->name('usuarios.edit');
Route::post('/usuarios/editar/{id}', [ControladorUsuarios::class, 'update'])->name('usuarios.update');
Route::get('/usuarios/eliminar/{id}', [ControladorUsuarios::class, 'destroy'])->name('usuarios.destroy');
Route::get('/roles', [ControladorRoles::class, 'index'])->name('roles.index');
Route::get('/roles/crear', [ControladorRoles::class, 'create'])->name('roles.create');
Route::post('/roles/crear', [ControladorRoles::class, 'store'])->name('roles.store');
Route::get('/roles/editar/{id}', [ControladorRoles::class, 'edit'])->name('roles.edit');
Route::post('/roles/editar/{id}', [ControladorRoles::class, 'update'])->name('roles.update');
Route::get('/roles/eliminar/{id}', [ControladorRoles::class, 'destroy'])->name('roles.destroy');
Route::post('/facturas/crear', [ControladorFacturacion::class, 'ctrCrearFactura'])->name('facturas.crear');
Route::get('/facturas/eliminar', [ControladorFacturacion::class, 'ctrEliminarFactura'])->name('facturas.eliminar');
Route::get('/facturas/listado', function () {
    $facturas = \App\Models\ModeloFacturacion::mdlMostrarFacturasConCliente();
    return view('listado-facturas', compact('facturas'));
})->name('listado.facturas');