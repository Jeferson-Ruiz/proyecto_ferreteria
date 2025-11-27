<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ModeloProveedores extends Model
{
    protected $table = 'proveedores';
    public $timestamps = false;
    protected $fillable = ['empresa', 'asesor', 'telefono', 'correo', 'productos'];

    /*=============================================
    CREAR PROVEEDOR
    =============================================*/
    public static function mdlIngresarProveedor($tabla, $datos)
    {
        // Verificar duplicados
        $check = self::where('correo', $datos['correo'])
            ->orWhere('telefono', $datos['telefono'])
            ->first();

        if ($check) {
            return "duplicado";
        }

        $insert = self::create([
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
            return self::where($item, $valor)->first();
        } else {
            return self::orderBy('id', 'DESC')->get();
        }
    }

    /*=============================================
    EDITAR PROVEEDOR
    =============================================*/
    public static function mdlEditarProveedor($tabla, $datos)
    {
        // Verificar duplicados
        $duplicado = self::where(function ($q) use ($datos) {
                $q->where('correo', $datos['correo'])
                  ->orWhere('telefono', $datos['telefono']);
            })
            ->where('id', '!=', $datos['id'])
            ->first();

        if ($duplicado) {
            return "duplicado";
        }

        $update = self::where('id', $datos['id'])
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
        $delete = self::where('id', $id)->delete();
        return $delete ? "ok" : "error";
    }
}