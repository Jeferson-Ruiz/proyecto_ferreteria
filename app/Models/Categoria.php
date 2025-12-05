<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias'; // tabla exacta
    public $timestamps = false;      // sin created_at ni updated_at
    protected $fillable = ['nombre', 'descripcion']; // campos asignables

    /*=============================================
    MOSTRAR CATEGORÍAS
    =============================================*/
    public static function mdlMostrarCategorias($item = null, $valor = null)
    {
        if ($item != null) {
            return self::where($item, $valor)->first();
        } else {
            return self::orderBy('id', 'desc')->get();
        }
    }

    /*=============================================
    CREAR CATEGORÍA
    =============================================*/
    public static function mdlIngresarCategoria($datos)
    {
        return self::create([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion']
        ]) ? "ok" : "error";
    }

    /*=============================================
    EDITAR CATEGORÍA
    =============================================*/
    public static function mdlEditarCategoria($datos)
    {
        $categoria = self::find($datos['id']);
        if ($categoria) {
            return $categoria->update([
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion']
            ]) ? "ok" : "error";
        }
        return "error";
    }

    /*=============================================
    ELIMINAR CATEGORÍA
    =============================================*/
    public static function mdlEliminarCategoria($id)
    {
        $categoria = self::find($id);
        if ($categoria) {
            return $categoria->delete() ? "ok" : "error";
        }
        return "error";
    }

    /*=============================================
    BUSCAR CATEGORÍA (nombre o descripción)
    =============================================*/
    public static function mdlBuscarCategoria($termino)
    {
        return self::where('nombre', 'LIKE', '%' . $termino . '%')
            ->orWhere('descripcion', 'LIKE', '%' . $termino . '%')
            ->orderBy('id', 'DESC')
            ->get();
    }

    /*=============================================
    CONTAR CATEGORIAS
    ======================================*/
    public static function mdlContarCategorias()
    {
        return self::count();
    }




}
