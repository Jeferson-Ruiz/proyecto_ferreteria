<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos'; // tabla exacta
    public $timestamps = false;      // sin created_at ni updated_at
    protected $fillable = ['nombre', 'categoria_id', 'stock', 'precio_unitario'];

    /*=============================================
    RELACIÓN CON CATEGORÍA
    =============================================*/
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /*=============================================
    MOSTRAR PRODUCTOS
    =============================================*/
    public static function mdlMostrarProductos($item = null, $valor = null)
    {
        if ($item != null) {
            return self::with('categoria')->where($item, $valor)->first();
        } else {
            return self::with('categoria')->orderBy('id', 'desc')->get();
        }
    }

    /*=============================================
    CREAR PRODUCTO
    =============================================*/
    public static function mdlIngresarProducto($datos)
    {
        return self::create([
            'nombre' => $datos['nombre'],
            'categoria_id' => $datos['categoria_id'],
            'stock' => $datos['stock'],
            'precio_unitario' => $datos['precio_unitario'],
        ]) ? "ok" : "error";
    }

    /*=============================================
    EDITAR PRODUCTO
    =============================================*/
    public static function mdlEditarProducto($datos)
    {
        $producto = self::find($datos['id']);
        if ($producto) {
            return $producto->update([
                'nombre' => $datos['nombre'],
                'categoria_id' => $datos['categoria_id'],
                'stock' => $datos['stock'],
                'precio_unitario' => $datos['precio_unitario'],
            ]) ? "ok" : "error";
        }
        return "error";
    }

    /*=============================================
    ELIMINAR PRODUCTO
    =============================================*/
    public static function mdlEliminarProducto($id)
    {
        $producto = self::find($id);
        if ($producto) {
            return $producto->delete() ? "ok" : "error";
        }
        return "error";
    }

    /*=============================================
    BUSCAR PRODUCTO (nombre o categoría)
    ======================================*/
    public static function mdlBuscarProducto($termino)
    {
        return self::with('categoria')
            ->where('nombre', 'LIKE', '%' . $termino . '%')
            ->orWhereHas('categoria', function($query) use ($termino) {
                $query->where('nombre', 'LIKE', '%' . $termino . '%');
            })
            ->orderBy('id', 'DESC')
            ->get();
    }

    /*=============================================
    CONTAR PRODUCTOS
    ======================================*/
    public static function mdlContarProductos()
    {
        return self::count();
    }



}
