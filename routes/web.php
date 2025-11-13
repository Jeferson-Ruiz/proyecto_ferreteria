<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuariosController;


Route::get('/', function () {
    return view('welcome');
});

// Rutas de Categorías
Route::get('/categorias', [CategoriaController::class, 'mostrar'])->name('categorias.index');
Route::post('/categorias/crear', [CategoriaController::class, 'crear'])->name('categorias.crear');
Route::post('/categorias/editar', [CategoriaController::class, 'editar'])->name('categorias.editar');
Route::post('/categorias/eliminar', [CategoriaController::class, 'eliminar'])->name('categorias.eliminar');

// Rutas de Productos
Route::get('/productos', [ProductoController::class, 'mostrar'])->name('productos.index');
Route::post('/productos/crear', [ProductoController::class, 'crear'])->name('productos.crear');
Route::post('/productos/editar', [ProductoController::class, 'editar'])->name('productos.editar');
Route::post('/productos/eliminar', [ProductoController::class, 'eliminar'])->name('productos.eliminar');


Route::prefix('usuarios')->group(function () {
    Route::get('/', [UsuariosController::class, 'index'])->name('usuarios.index');
    Route::post('/crear', [UsuariosController::class, 'crear'])->name('usuarios.crear');
    Route::post('/editar', [UsuariosController::class, 'editar'])->name('usuarios.editar');
    Route::get('/eliminar/{id}', [UsuariosController::class, 'eliminar'])->name('usuarios.eliminar');
});

