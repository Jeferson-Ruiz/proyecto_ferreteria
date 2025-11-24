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
        // Verificar duplicados
        $check = DB::table($tabla)
            ->where('correo', $datos['correo'])
            ->orWhere('telefono', $datos['telefono'])
            ->first();

        if ($check) {
            return "duplicado";
        }

        $insert = DB::table($tabla)->insert([
            'empresa'   => $datos['empresa'],
            'asesor'    => $datos['asesor'],
            'telefono'  => $datos['telefono'],
            'correo'    => $datos['correo'],
            'productos' => $datos['productos']
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
                ->orderBy('id', 'DESC')
                ->get();
        }
    }

    /*=============================================
    EDITAR PROVEEDOR
    =============================================*/
    public static function mdlEditarProveedor($tabla, $datos)
    {
        // Verificar duplicados
        $duplicado = DB::table($tabla)
            ->where(function ($q) use ($datos) {
                $q->where('correo', $datos['correo'])
                  ->orWhere('telefono', $datos['telefono']);
            })
            ->where('id', '!=', $datos['id'])
            ->first();

        if ($duplicado) {
            return "duplicado";
        }

        $update = DB::table($tabla)
            ->where('id', $datos['id'])
            ->update([
                'empresa'   => $datos['empresa'],
                'asesor'    => $datos['asesor'],
                'telefono'  => $datos['telefono'],
                'correo'    => $datos['correo'],
                'productos' => $datos['productos']
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