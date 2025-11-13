<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'roles';

    // Campos permitidos para asignación masiva
    protected $fillable = ['nombre'];

    // ======================================
    // Mostrar roles
    // ======================================
    public static function mostrarRoles($item = null, $valor = null)
    {
        if ($item !== null) {
            return self::where($item, $valor)->first();
        } else {
            return self::orderBy('id', 'desc')->get();
        }
    }

    // ======================================
    // Crear rol
    // ======================================
    public static function crearRol($datos)
    {
        $rol = self::create([
            'nombre' => $datos['nombre']
        ]);

        return $rol ? 'ok' : 'error';
    }

    // ======================================
    // Editar rol
    // ======================================
    public static function editarRol($datos)
    {
        $rol = self::find($datos['id']);
        if (!$rol) return 'error';

        $rol->nombre = $datos['nombre'];
        return $rol->save() ? 'ok' : 'error';
    }

    // ======================================
    // Borrar rol
    // ======================================
    public static function borrarRol($id)
    {
        $rol = self::find($id);
        if (!$rol) return 'error';

        return $rol->delete() ? 'ok' : 'error';
    }
}
