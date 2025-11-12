<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    /*=============================================
    MOSTRAR PRODUCTOS
    =============================================*/
    public function mostrar($item = null, $valor = null)
    {
        return Producto::mdlMostrarProductos($item, $valor);
    }

    /*=============================================
    CREAR PRODUCTO
    =============================================*/
    public function crear(Request $request)
    {
        $nombre = trim($request->input('nuevoProducto'));
        $categoria = $request->input('nuevaCategoria');
        $stock = $request->input('nuevoStock');
        $precio_unitario = $request->input('nuevoPrecioUnitario');

        if ($nombre) {
            // Validar duplicados
            $existe = Producto::mdlMostrarProductos('nombre', $nombre);
            if ($existe) {
                return back()->with('error', '⚠️ El producto ya existe');
            }

            $datos = [
                'nombre' => $nombre,
                'categoria_id' => $categoria,
                'stock' => $stock,
                'precio_unitario' => $precio_unitario,
            ];

            $respuesta = Producto::mdlIngresarProducto($datos);
            if ($respuesta == "ok") {
                return redirect()->route('productos.index')
                    ->with('success', '✅ Producto registrado correctamente');
            }
        }

        return back()->with('error', 'No se pudo crear el producto');
    }

    /*=============================================
    EDITAR PRODUCTO
    =============================================*/
    public function editar(Request $request)
    {
        $id = $request->input('idProducto');
        $nombre = trim($request->input('editarProducto'));
        $categoria = $request->input('editarCategoria');
        $stock = $request->input('editarStock');
        $precio_unitario = $request->input('editarPrecioUnitario');

        // Verificar duplicados
        $productoExistente = Producto::mdlMostrarProductos('nombre', $nombre);
        if ($productoExistente && $productoExistente->id != $id) {
            return back()->with('error', '⚠️ Ya existe otro producto con ese nombre');
        }

        $datos = [
            'id' => $id,
            'nombre' => $nombre,
            'categoria_id' => $categoria,
            'stock' => $stock,
            'precio_unitario' => $precio_unitario,
        ];

        $respuesta = Producto::mdlEditarProducto($datos);
        if ($respuesta == "ok") {
            return redirect()->route('productos.index')
                ->with('success', '✅ Producto actualizado correctamente');
        }

        return back()->with('error', 'No se pudo actualizar el producto');
    }

    /*=============================================
    ELIMINAR PRODUCTO
    =============================================*/
    public function eliminar(Request $request)
    {
        $id = $request->input('idProducto');
        $respuesta = Producto::mdlEliminarProducto($id);

        if ($respuesta == "ok") {
            return redirect()->route('productos.index')
                ->with('success', '🗑️ Producto eliminado');
        }

        return back()->with('error', 'No se pudo eliminar el producto');
    }
}
