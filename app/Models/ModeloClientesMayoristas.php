<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ModeloClientesMayoristas; // ← AGREGAR ESTA LÍNEA


class ModeloClientesMayoristas extends Model
{
    protected $table = 'clientes_mayoristas';
    public $timestamps = false;
    protected $fillable = ['empresa', 'contacto', 'telefono', 'correo', 'direccion', 'debe'];

    /*=============================================
    MOSTRAR CLIENTES MAYORISTAS
    =============================================*/
    public static function mdlMostrarClientes($item = null, $valor = null)
    {
        if ($item != null) {
            return self::where($item, $valor)->first();
        } else {
            return self::orderBy('id', 'desc')->get();
        }
    }

    /*=============================================
    CREAR CLIENTE MAYORISTA
    =============================================*/
    public static function mdlIngresarCliente($datos)
    {
        return self::create([
            'empresa' => $datos['empresa'],
            'contacto' => $datos['contacto'],
            'telefono' => $datos['telefono'],
            'correo' => $datos['correo'],
            'direccion' => $datos['direccion'],
            'debe' => $datos['debe'] ?? 0
        ]) ? "ok" : "error";
    }

    /*=============================================
    EDITAR CLIENTE MAYORISTA
    =============================================*/
    public static function mdlEditarCliente($datos)
    {
        $cliente = self::find($datos['id']);
        if ($cliente) {
            return $cliente->update([
                'empresa' => $datos['empresa'],
                'contacto' => $datos['contacto'],
                'telefono' => $datos['telefono'],
                'correo' => $datos['correo'],
                'direccion' => $datos['direccion'],
                'debe' => $datos['debe'] ?? $cliente->debe
            ]) ? "ok" : "error";
        }
        return "error";
    }

    /*=============================================
    ELIMINAR CLIENTE MAYORISTA
    =============================================*/
    public static function mdlEliminarCliente($id)
    {
        $cliente = self::find($id);
        if ($cliente) {
            return $cliente->delete() ? "ok" : "error";
        }
        return "error";
    }

    /*=============================================
    BUSCAR CLIENTE MAYORISTA (empresa o contacto)
    =============================================*/
    public static function mdlBuscarCliente($termino)
    {
        return self::where('empresa', 'LIKE', '%' . $termino . '%')
            ->orWhere('contacto', 'LIKE', '%' . $termino . '%')
            ->orWhere('correo', 'LIKE', '%' . $termino . '%')
            ->orderBy('id', 'DESC')
            ->get();
    }

    /*=============================================
    ACTUALIZAR DEUDA
    =============================================*/
    public static function mdlActualizarDeuda($id, $monto)
    {
        $cliente = self::find($id);
        if ($cliente) {
            return $cliente->update(['debe' => $monto]) ? "ok" : "error";
        }
        return "error";
    }
}