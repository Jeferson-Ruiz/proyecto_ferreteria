<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /*=============================================
    MOSTRAR CATEGORÍAS
    =============================================*/
    public function mostrar($item = null, $valor = null)
    {
        $categorias = Categoria::mdlMostrarCategorias($item, $valor);
        return view('modulos.categorias', compact('categorias'));
    }

    /*=============================================
    CREAR CATEGORÍA
    =============================================*/
    public function crear(Request $request)
    {
        $nombre = strtolower(trim($request->input('nuevaCategoria')));
        $descripcion = strtolower(trim($request->input('nuevaDescripcion')));

        if ($nombre) {
            // Validar duplicados
            $existe = Categoria::mdlMostrarCategorias('nombre', $nombre);
            if ($existe) {
                return back()->with('error', '⚠️ La categoría ya existe');
            }

            $respuesta = Categoria::mdlIngresarCategoria([
                'nombre' => $nombre,
                'descripcion' => $descripcion
            ]);
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
            'nombre' => strtolower(trim($request->input('editarCategoria'))),
            'descripcion' => strtolower(trim($request->input('editarDescripcion')))
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

    /*=============================================
    BUSCAR CATEGORÍAS
    =============================================*/
    public function buscar(Request $solicitud)
    {
        $termino = $solicitud->input('termino');
        
        if ($termino) {
            $categorias = Categoria::mdlBuscarCategoria($termino);
        } else {
            $categorias = Categoria::mdlMostrarCategorias();
        }
        
        return view('modulos.categorias', compact('categorias'));
    }
}
