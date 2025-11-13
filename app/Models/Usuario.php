<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;


class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $fillable = ['nombre_completo', 'documento', 'correo', 'contrasena', 'rol_id'];
    public $timestamps = false;

    /*=============================================
    CREAR USUARIO
    =============================================*/
    public static function mdlIngresarUsuario($datos)
    {
        // Verificar duplicados
        $existe = self::where('documento', $datos['documento'])
            ->orWhere('correo', $datos['correo'])
            ->first();

        if ($existe) {
            return 'duplicado';
        }

        return self::create($datos) ? 'ok' : 'error';
    }

    /*=============================================
    MOSTRAR USUARIOS
    =============================================*/
    public static function mdlMostrarUsuarios($item = null, $valor = null)
    {
        if ($item != null) {
            return self::with('rol')->where($item, $valor)->first();
        } else {
            return self::with('rol')->orderBy('id', 'desc')->get();
        }
    }

    /*=============================================
    RELACIÓN CON ROLES
    =============================================*/
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /*=============================================
    EDITAR USUARIO
    =============================================*/
    public static function mdlEditarUsuario($datos)
    {
        $usuarioExistente = self::where(function($query) use ($datos) {
                $query->where('documento', $datos['documento'])
                      ->orWhere('correo', $datos['correo']);
            })
            ->where('id', '!=', $datos['id'])
            ->first();

        if ($usuarioExistente) {
            return 'duplicado';
        }

        $usuario = self::find($datos['id']);
        if (!$usuario) return 'error';

        $usuario->nombre_completo = $datos['nombre_completo'];
        $usuario->documento = $datos['documento'];
        $usuario->correo = $datos['correo'];
        $usuario->rol_id = $datos['rol_id'];
        if (!empty($datos['contrasena'])) {
            $usuario->contrasena = $datos['contrasena'];
        }

        return $usuario->save() ? 'ok' : 'error';
    }

    /*=============================================
    ELIMINAR USUARIO
    =============================================*/
    public static function mdlEliminarUsuario($id)
    {
        $usuario = self::find($id);
        return $usuario ? ($usuario->delete() ? 'ok' : 'error') : 'error';
    }

    /*=============================================
    LOGIN POR CORREO
    =============================================*/
    public static function mdlObtenerUsuarioPorCorreo($correo)
    {
        return self::where('correo', $correo)->first();
    }
}
