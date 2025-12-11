<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModeloUsuarios;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ModeloFacturacion;

class ControladorInicio extends Controller
{
    public function mostrar()
    {
        $datos = [
            'totalUsuarios' => ModeloUsuarios::mdlContarUsuarios(),
            'totalCategorias' => Categoria::count(),
            'totalProductos' => Producto::count(),
            'totalFacturas' => ModeloFacturacion::mdlTotalFacturas(),
            'productosBajoInventario' => Producto::mdlProductosBajoInventario(),
        ];

        return view('modulos.inicio', $datos);
    }
}