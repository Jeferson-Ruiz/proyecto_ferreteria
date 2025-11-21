<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ModeloProveedores extends Model
{
    /*=============================================
    CREAR PROVEEDOR
    =============================================*/
    public static function mdlIngresarProveedor($tabla, $datos)
    {
        // Verificar duplicados (por ejemplo: nombre del proveedor o NIT)
        $check = DB::table($tabla)
            ->where('nit', $datos['nit'])
            ->orWhere('correo', $datos['correo'])
            ->first();

        if ($check) {
            return "duplicado";
        }

        $insert = DB::table($tabla)->insert([
            'nombre'     => $datos['nombre'],
            'nit'        => $datos['nit'],
            'telefono'   => $datos['telefono'],
            'correo'     => $datos['correo'],
            'direccion'  => $datos['direccion']
        ]);

        return $insert ? "ok" : "error";
    }

    /*=============================================
    MOSTRAR PROVEEDORES
    =============================================*/
    public static function mdlMostrarProveedores($tabla, $item, $valor)
    {
        if ($item != null) {
            return DB::table($tabla)
                ->where($item, $valor)
                ->first();
        } else {
            return DB::table($tabla)
                ->orderBy("id", "DESC")
                ->get();
        }
    }

    /*=============================================
    EDITAR PROVEEDOR
    =============================================*/
    public static function mdlEditarProveedor($tabla, $datos)
    {
        // Verificar duplicados excepto el mismo ID
        $duplicado = DB::table($tabla)
            ->where(function ($q) use ($datos) {
                $q->where('nit', $datos['nit'])
                  ->orWhere('correo', $datos['correo']);
            })
            ->where('id', '!=', $datos['id'])
            ->first();

        if ($duplicado) {
            return "duplicado";
        }

        $update = DB::table($tabla)
            ->where('id', $datos['id'])
            ->update([
                'nombre'     => $datos['nombre'],
                'nit'        => $datos['nit'],
                'telefono'   => $datos['telefono'],
                'correo'     => $datos['correo'],
                'direccion'  => $datos['direccion']
            ]);

        return $update ? "ok" : "error";
    }

    /*=============================================
    ELIMINAR PROVEEDOR
    =============================================*/
    public static function mdlEliminarProveedor($tabla, $id)
    {
        $delete = DB::table($tabla)->where('id', $id)->delete();
        return $delete ? "ok" : "error";
    }
}
