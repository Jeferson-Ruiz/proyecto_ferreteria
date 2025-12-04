<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;


class ModeloUsuarios extends Authenticatable{ 
    protected $table = 'usuarios';
    public $timestamps = false;
    protected $fillable = ['nombre_completo', 'documento', 'correo', 'contrasena', 'rol_id'];

    /*=============================================
    CREAR USUARIO
    =============================================*/
    public static function mdlIngresarUsuario($tabla, $datos)
    {
        // Verificar duplicados
        $check = self::where('documento', $datos['documento'])
            ->orWhere('correo', $datos['correo'])
            ->first();

        if ($check) {
            return "duplicado";
        }

        $insert = self::create([
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
            return self::from($tabla . " AS u")
                ->join("roles AS r", "u.rol_id", "=", "r.id")
                ->select("u.*", "r.nombre AS rol")
                ->where("u.$item", $valor)
                ->first();
        } else {
            return self::from($tabla . " AS u")
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
        $duplicado = self::where(function ($q) use ($datos) {
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

        $update = self::where('id', $datos['id'])->update($updateData);

        return $update ? "ok" : "error";
    }

    /*=============================================
    ELIMINAR USUARIO
    =============================================*/
    public static function mdlEliminarUsuario($tabla, $id)
    {
        $delete = self::where('id', $id)->delete();
        return $delete ? "ok" : "error";
    }

    /*=============================================
    NUEVO MÉTODO PARA LOGIN
    =============================================*/
    public static function mdlObtenerUsuarioPorCorreo($correo)
    {
        return self::where("correo", $correo)->first();
    }
}