<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

class ModeloRoles
{
    /* ======================================
       Mostrar Roles
    ====================================== */
    public static function mdlMostrarRoles($item = null, $valor = null)
    {
        if ($item != null) {
            return DB::table("roles")
                ->where($item, $valor)
                ->first();
        } else {
            return DB::table("roles")
                ->orderBy("id", "DESC")
                ->get();
        }
    }

    /* ======================================
       Crear Rol
    ====================================== */
    public static function mdlIngresarRol($tabla, $datos)
    {
        $insert = DB::table($tabla)->insert([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion']

        ]);

        return $insert ? "ok" : "error";
    }

    /* ======================================
        Buscar Rol (nombre o descripción)
    ====================================== */
    public static function mdlBuscarRol($termino)
    {
        return DB::table("roles")
            ->where('nombre', 'LIKE', '%' . $termino . '%')
            ->orWhere('descripcion', 'LIKE', '%' . $termino . '%')
            ->orderBy("id", "DESC")
            ->get();
    }

    
    /* ======================================
       Editar Rol
    ====================================== */
    public static function mdlEditarRol($tabla, $datos)
    {
        $update = DB::table($tabla)
            ->where('id', $datos['id'])
            ->update([
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion']

            ]);

        return $update ? "ok" : "error";
    }

    /* ======================================
       Eliminar Rol
    ====================================== */
    public static function mdlBorrarRol($tabla, $id)
    {
        $delete = DB::table($tabla)
            ->where('id', $id)
            ->delete();

        return $delete ? "ok" : "error";
    }
}
