<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /*=============================================
    MOSTRAR CATEGORÍAS
    =============================================*/
    public function mostrar($item = null, $valor = null)
    {
        return Categoria::mdlMostrarCategorias($item, $valor);
    }

    /*=============================================
    CREAR CATEGORÍA
    =============================================*/
    public function crear(Request $request)
    {
        $nombre = trim($request->input('nuevaCategoria'));

        if ($nombre) {
            // Validar duplicados
            $existe = Categoria::mdlMostrarCategorias('nombre', $nombre);
            if ($existe) {
                return back()->with('error', '⚠️ La categoría ya existe');
            }

            $respuesta = Categoria::mdlIngresarCategoria(['nombre' => $nombre]);
            if ($respuesta == "ok") {
                return redirect()->route('categorias.index')->with('success', '✅ Categoría creada correctamente');
            }
        }

        return back()->with('error', 'No se pudo crear la categoría');
    }

    /*=============================================
    EDITAR CATEGORÍA
    =============================================*/
    public function editar(Request $request)
    {
        $datos = [
            'id' => $request->input('idCategoria'),
            'nombre' => trim($request->input('editarCategoria')),
        ];

        $respuesta = Categoria::mdlEditarCategoria($datos);
        if ($respuesta == "ok") {
            return redirect()->route('categorias.index')->with('success', '✅ Categoría actualizada correctamente');
        }

        return back()->with('error', 'No se pudo actualizar la categoría');
    }

    /*=============================================
    ELIMINAR CATEGORÍA
    =============================================*/
    public function eliminar(Request $request)
    {
        $id = $request->input('idCategoria');
        $respuesta = Categoria::mdlEliminarCategoria($id);

        if ($respuesta == "ok") {
            return redirect()->route('categorias.index')->with('success', '🗑️ Categoría eliminada');
        }

        return back()->with('error', 'No se pudo eliminar la categoría');
    }
}
