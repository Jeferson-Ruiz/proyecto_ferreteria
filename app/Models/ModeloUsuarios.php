<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

class ModeloUsuarios extends Model
{ 
    /*=============================================
    CREAR USUARIO
    =============================================*/
    public static function mdlIngresarUsuario($tabla, $datos)
    {
        // Verificar duplicados
        $check = DB::table($tabla)
            ->where('documento', $datos['documento'])
            ->orWhere('correo', $datos['correo'])
            ->first();

        if ($check) {
            return "duplicado";
        }

        $insert = DB::table($tabla)->insert([
            'nombre_completo' => $datos['nombre_completo'],
            'documento'       => $datos['documento'],
            'correo'          => $datos['correo'],
            'contrasena'      => $datos['contrasena'],
            'rol_id'          => $datos['rol_id']
        ]);

        return $insert ? "ok" : "error";
    }

    /*=============================================
    MOSTRAR USUARIOS
    =============================================*/
    public static function mdlMostrarUsuarios($tabla, $item, $valor)
    {
        if ($item != null) {
            return DB::table($tabla . " AS u")
                ->join("roles AS r", "u.rol_id", "=", "r.id")
                ->select("u.*", "r.nombre AS rol")
                ->where("u.$item", $valor)
                ->first();
        } else {
            return DB::table($tabla . " AS u")
                ->join("roles AS r", "u.rol_id", "=", "r.id")
                ->select("u.*", "r.nombre AS rol")
                ->orderBy("u.id", "DESC")
                ->get();
        }
    }

    /*=============================================
    EDITAR USUARIO
    =============================================*/
    public static function mdlEditarUsuario($tabla, $datos)
    {
        // Verificar duplicados
        $duplicado = DB::table($tabla)
            ->where(function ($q) use ($datos) {
                $q->where('documento', $datos['documento'])
                  ->orWhere('correo', $datos['correo']);
            })
            ->where('id', '!=', $datos['id'])
            ->first();

        if ($duplicado) {
            return "duplicado";
        }

        // Construcción de campos según si hay contraseña
        $updateData = [
            'nombre_completo' => $datos['nombre_completo'],
            'documento'       => $datos['documento'],
            'correo'          => $datos['correo'],
            'rol_id'          => $datos['rol_id']
        ];

        if (!empty($datos['contrasena'])) {
            $updateData['contrasena'] = $datos['contrasena'];
        }

        $update = DB::table($tabla)
            ->where('id', $datos['id'])
            ->update($updateData);

        return $update ? "ok" : "error";
    }

    /*=============================================
    ELIMINAR USUARIO
    =============================================*/
    public static function mdlEliminarUsuario($tabla, $id)
    {
        $delete = DB::table($tabla)->where('id', $id)->delete();
        return $delete ? "ok" : "error";
    }

    /*=============================================
    NUEVO MÉTODO PARA LOGIN
    =============================================*/
    public static function mdlObtenerUsuarioPorCorreo($correo)
    {
        return DB::table("usuarios")
            ->where("correo", $correo)
            ->first();
    }
}
