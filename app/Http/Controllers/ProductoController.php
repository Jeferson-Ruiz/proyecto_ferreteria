<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    /*=============================================
    MOSTRAR PRODUCTOS
    =============================================*/
    public function mostrar($item = null, $valor = null)
    {
        $productos = Producto::mdlMostrarProductos($item, $valor);
        $categorias = Categoria::all(); // Para que Blade tenga acceso a las categorías
        return view("modulos.productos", compact("productos", "categorias"));
    }

    /*=============================================
    CREAR PRODUCTO
    =============================================*/
    public function crear(Request $request)
    {
        if ($request->has("nuevoProducto")) {

            $nombre = trim($request->input("nuevoProducto"));
            $categoria = $request->input("nuevaCategoria");
            $stock = $request->input("nuevoStock");
            $precio_unitario = $request->input("nuevoPrecioUnitario");

            // Validar duplicados
            $existe = Producto::mdlMostrarProductos("nombre", $nombre);
            if ($existe) {
                return back()->with("error", "⚠️ El producto ya existe");
            }

            $datos = [
                "nombre" => strtolower(trim($nombre)),
                "categoria_id" => $categoria,
                "stock" => $stock,
                "precio_unitario" => $precio_unitario
            ];

            $respuesta = Producto::mdlIngresarProducto($datos);

            if ($respuesta == "ok") {
                return redirect()->route("productos.index")
                    ->with("success", "✅ Producto registrado correctamente");
            }
        }

        return back()->with("error", "No se pudo crear el producto");
    }

    /*=============================================
    EDITAR PRODUCTO
    =============================================*/
    public function editar(Request $request)
    {
        if ($request->has("editarProducto")) {

            $id = $request->input("idProducto");
            $nombre = trim($request->input("editarProducto"));
            $categoria = $request->input("editarCategoria");
            $stock = $request->input("editarStock");
            $precio_unitario = $request->input("editarPrecioUnitario");

            // Verificar duplicados
            $productoExistente = Producto::mdlMostrarProductos("nombre", $nombre);
            if ($productoExistente && $productoExistente["id"] != $id) {
                return back()->with("error", "⚠️ Ya existe otro producto con ese nombre");
            }

            $datos = [
                "id" => $id,
                "nombre" => strtolower(trim($nombre)),
                "categoria_id" => $categoria,
                "stock" => $stock,
                "precio_unitario" => $precio_unitario
            ];

            $respuesta = Producto::mdlEditarProducto($datos);

            if ($respuesta == "ok") {
                return redirect()->route("productos.index")
                    ->with("success", "✅ Producto actualizado correctamente");
            }
        }

        return back()->with("error", "No se pudo actualizar el producto");
    }

    /*=============================================
    ELIMINAR PRODUCTO
    =============================================*/
    public function eliminar(Request $request)
    {
        if ($request->has("idProducto")) {
            $respuesta = Producto::mdlEliminarProducto($request->input("idProducto"));

            if ($respuesta == "ok") {
                return redirect()->route("productos.index")
                    ->with("success", "🗑️ Producto eliminado");
            }
        }

        return back()->with("error", "No se pudo eliminar el producto");
    }

    /*=============================================
    BUSCAR PRODUCTOS
    ======================================*/
    public function buscar(Request $solicitud)
    {
        $termino = $solicitud->input('termino');
        
        if ($termino) {
            $productos = Producto::mdlBuscarProducto($termino);
        } else {
            $productos = Producto::mdlMostrarProductos();
        }
        
        $categorias = Categoria::mdlMostrarCategorias(); // Para el select de categorías
        return view('modulos.productos', compact('productos', 'categorias'));
    }

    /*=============================================
    PRODUCTOS BAJOS EN INVENTARIO
    ======================================*/
    public function bajoInventario()
    {
        $productos = Producto::mdlObtenerProductosBajoInventario();
        return view('modulos.productos-bajo-inventario', compact('productos'));
    }

}
