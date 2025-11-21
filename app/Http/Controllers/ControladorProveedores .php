<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModeloProveedores;

class ControladorProveedores extends Controller
{
    /*=============================================
    CREAR PROVEEDOR
    =============================================*/
    public function crearProveedor(Request $request)
    {
        $datos = [
            'nombre'    => $request->input('nombre'),
            'nit'       => $request->input('nit'),
            'telefono'  => $request->input('telefono'),
            'correo'    => $request->input('correo'),
            'direccion' => $request->input('direccion')
        ];

        $respuesta = ModeloProveedores::mdlIngresarProveedor("proveedores", $datos);

        return response()->json($respuesta);
    }

    /*=============================================
    MOSTRAR PROVEEDORES
    =============================================*/
    public function mostrarProveedores($id = null)
    {
        if ($id) {
            $proveedor = ModeloProveedores::mdlMostrarProveedores(
                "proveedores",
                "id",
                $id
            );
            return response()->json($proveedor);
        }

        $proveedores = ModeloProveedores::mdlMostrarProveedores(
            "proveedores",
            null,
            null
        );

        return response()->json($proveedores);
    }

    /*=============================================
    EDITAR PROVEEDOR
    =============================================*/
    public function editarProveedor(Request $request, $id)
    {
        $datos = [
            'id'        => $id,
            'nombre'    => $request->input('nombre'),
            'nit'       => $request->input('nit'),
            'telefono'  => $request->input('telefono'),
            'correo'    => $request->input('correo'),
            'direccion' => $request->input('direccion')
        ];

        $respuesta = ModeloProveedores::mdlEditarProveedor("proveedores", $datos);

        return response()->json($respuesta);
    }

    /*=============================================
    ELIMINAR PROVEEDOR
    =============================================*/
    public function eliminarProveedor($id)
    {
        $respuesta = ModeloProveedores::mdlEliminarProveedor("proveedores", $id);
        return response()->json($respuesta);
    }
}
