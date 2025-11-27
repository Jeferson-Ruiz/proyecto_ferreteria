<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ModeloRoles extends Model
{
    protected $table = 'roles';
    public $timestamps = false;
    protected $fillable = ['nombre', 'descripcion'];

    /* ======================================
       Mostrar Roles
    ====================================== */
    public static function mdlMostrarRoles($item = null, $valor = null)
    {
        if ($item != null) {
            return self::where($item, $valor)->first();
        } else {
            return self::orderBy("id", "DESC")->get();
        }
    }

    /* ======================================
       Crear Rol
    ====================================== */
    public static function mdlIngresarRol($tabla, $datos)
    {
        $insert = self::create([
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
        return self::where('nombre', 'LIKE', '%' . $termino . '%')
            ->orWhere('descripcion', 'LIKE', '%' . $termino . '%')
            ->orderBy("id", "DESC")
            ->get();
    }

    /* ======================================
       Editar Rol
    ====================================== */
    public static function mdlEditarRol($tabla, $datos)
    {
        $update = self::where('id', $datos['id'])
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
        $delete = self::where('id', $id)->delete();
        return $delete ? "ok" : "error";
    }
}