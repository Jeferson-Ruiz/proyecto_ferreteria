<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ModeloProveedores;

class ControladorProveedores extends Controller
{
    private $tabla = "proveedores";

    /*=============================================
    CREAR PROVEEDOR
    =============================================*/
    public function crear(Request $solicitud)
    {
        $datos =[
            "empresa"   => strtolower(trim($solicitud->input("empresa"))),
            "asesor"    => strtolower(trim($solicitud->input("asesor"))), 
            "telefono"  => $solicitud->input("telefono"),
            "correo"    => strtolower(trim($solicitud->input("correo"))),
            "productos" => strtolower(trim($solicitud->input("productos")))
        ];

        $respuesta = ModeloProveedores::mdlIngresarProveedor($this->tabla, $datos);

        if ($respuesta == "ok") {
            return redirect('/proveedores')->with('success', 'Proveedor creado correctamente');
        } elseif ($respuesta == "duplicado") {
            return redirect('/proveedores')->with('error', 'El proveedor ya existe');
        } else {
            return redirect('/proveedores')->with('error', 'Error al crear proveedor');
        }
    }
    /*=============================================
    MOSTRAR PROVEEDORES
    =============================================*/
    public function mostrar($item = null, $valor = null)
    {
        $proveedores = ModeloProveedores::mdlMostrarProveedores($this->tabla, $item, $valor);

        return response()->json($proveedores, 200);
    }

    /*=============================================
    ACTUALIZAR PROVEEDOR
    =============================================*/
    public function actualizar(Request $solicitud, $id)
    {
        $datos = [
            "id"        => $id,
            "empresa"   => strtolower(trim($solicitud->input("empresa"))),
            "asesor"    => strtolower(trim($solicitud->input("asesor"))),
            "telefono"  => $solicitud->input("telefono"),
            "correo"    => strtolower(trim($solicitud->input("correo"))),
            "productos" => strtolower(trim($solicitud->input("productos")))
        ];

        $respuesta = ModeloProveedores::mdlEditarProveedor($this->tabla, $datos);

        if ($respuesta == "ok") {
            return redirect('/proveedores')->with('success', 'Proveedor actualizado correctamente');
        } elseif ($respuesta == "duplicado") {
            return redirect('/proveedores')->with('error', 'Ya existe otro proveedor con ese correo o teléfono');
        } else {
            return redirect('/proveedores')->with('error', 'Error al actualizar proveedor');
        }
    }


    /*=============================================
    ELIMINAR PROVEEDOR
    =============================================*/
    public function eliminar($id)
    {
        $respuesta = ModeloProveedores::mdlEliminarProveedor($this->tabla, $id);

        if ($respuesta == "ok") {
            return redirect('/proveedores')->with('success', 'Proveedor eliminado correctamente');
        } else {
            return redirect('/proveedores')->with('error', 'Error al eliminar proveedor');
        }
    }
}