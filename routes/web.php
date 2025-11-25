<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ControladorUsuarios;
use App\Http\Controllers\ControladorRoles;
use App\Http\Controllers\ControladorProveedores;
use App\Http\Controllers\ControladorFacturacion;
use App\Http\Controllers\ControladorClienteMayorista;
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

// RUTAS categoria:
Route::get('/categorias', [CategoriaController::class, 'mostrar'])->name('categorias.index');
Route::post('/categorias/crear', [CategoriaController::class, 'crear'])->name('categorias.crear');
Route::post('/categorias/editar', [CategoriaController::class, 'editar'])->name('categorias.editar');
Route::get('/categorias/buscar', [CategoriaController::class, 'buscar'])->name('categorias.buscar');
Route::post('/categorias/eliminar', [CategoriaController::class, 'eliminar'])->name('categorias.eliminar');

// RUTAS productos:
Route::get('/productos', [ProductoController::class, 'mostrar'])->name('productos.index');
Route::post('/productos/crear', [ProductoController::class, 'crear'])->name('productos.crear');
Route::post('/productos/editar', [ProductoController::class, 'editar'])->name('productos.editar');
Route::get('/productos/buscar', [ProductoController::class, 'buscar'])->name('productos.buscar');
Route::post('/productos/eliminar', [ProductoController::class, 'eliminar'])->name('productos.eliminar');

// RUTAS usuarios:
Route::get('/usuarios', [ControladorUsuarios::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/crear', [ControladorUsuarios::class, 'create'])->name('usuarios.create');
Route::post('/usuarios/crear', [ControladorUsuarios::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/editar/{id}', [ControladorUsuarios::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/editar/{id}', [ControladorUsuarios::class, 'ctrEditarUsuario'])->name('usuarios.update');
Route::delete('/usuarios/eliminar/{id}', [ControladorUsuarios::class, 'ctrBorrarUsuario'])->name('usuarios.destroy');

// Ruta roles
Route::get('/roles', [ControladorRoles::class, 'ctrMostrarRoles'])->name('roles.index');
Route::post('/roles', [ControladorRoles::class, 'ctrCrearRol'])->name('roles.store');
Route::get('/roles/buscar', [ControladorRoles::class, 'buscar'])->name('roles.buscar');
Route::put('/roles/{id}', [ControladorRoles::class, 'ctrEditarRol'])->name('roles.update');
Route::delete('/roles/{id}', [ControladorRoles::class, 'ctrBorrarRol'])->name('roles.destroy');


//Ruta proveedores
// Ruta para la vista
Route::get('/proveedores', function () {
    $proveedores = App\Models\ModeloProveedores::mdlMostrarProveedores('proveedores', null, null);
    return view('modulos.proveedores', compact('proveedores'));
})->name('proveedores.index');

Route::post('/proveedores/crear', [ControladorProveedores::class, 'crear'])->name('proveedores.store');
Route::put('/proveedores/actualizar/{id}', [ControladorProveedores::class, 'actualizar'])->name('proveedores.update');
Route::delete('/proveedores/eliminar/{id}', [ControladorProveedores::class, 'eliminar'])->name('proveedores.destroy');


// Rutas cliente mayorista
Route::get('/clientes-mayorista', [ControladorClienteMayorista::class, 'mostrar'])->name('clientes-mayorista.index');
Route::post('/clientes-mayorista/crear', [ControladorClienteMayorista::class, 'crear'])->name('clientes-mayorista.crear');
Route::post('/clientes-mayorista/editar', [ControladorClienteMayorista::class, 'editar'])->name('clientes-mayorista.editar');
Route::post('/clientes-mayorista/eliminar', [ControladorClienteMayorista::class, 'eliminar'])->name('clientes-mayorista.eliminar');
Route::get('/clientes-mayorista/buscar', [ControladorClienteMayorista::class, 'buscar'])->name('clientes-mayorista.buscar');
Route::post('/clientes-mayorista/actualizar-deuda', [ControladorClienteMayorista::class, 'actualizarDeuda'])->name('clientes-mayorista.actualizar-deuda');

// RUTAS Factura:
Route::get('/facturas', [ControladorFacturacion::class, 'ctrCrearFacturaView'])->name('facturas.index');
Route::post('/facturas/crear', [ControladorFacturacion::class, 'ctrCrearFactura'])->name('facturas.crear');
Route::delete('/facturas/eliminar', [ControladorFacturacion::class, 'ctrEliminarFactura'])->name('facturas.eliminar');
Route::get('/listado-facturas', function () {
    $facturas = \App\Models\ModeloFacturacion::mdlMostrarFacturasConCliente();
    return view('modulos.listado-facturas', compact('facturas'));
})->name('listado.facturas');