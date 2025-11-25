<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModeloUsuarios;

class ControladorPersonas extends Controller
{
    /*=============================================
    MOSTRAR PERSONAS
    =============================================*/
    public static function ctrMostrarPersonas()
    {
        return ModeloUsuario::mdlMostrarUsuarios();
    }

    /*=============================================
    REGISTRAR PERSONA
    =============================================*/
    public static function ctrCrearPersona(Request $request)
    {
        if ($request->has('nombre_completo')) {

            $datos = [
                "nombre_completo" => strtolower(trim($request->input('nombre_completo'))),
                "documento"       => $request->input('documento'),
                "correo"          => strtolower(trim($request->input('correo'))),
                "contrasena"      => password_hash($request->input('contrasena'), PASSWORD_BCRYPT),
                "rol_id"          => $request->input('rol_id'),
            ];

            $respuesta = ModeloUsuario::mdlAgregarUsuario($datos);

            if ($respuesta == "ok") {
                echo "<script>alert('Usuario registrado correctamente'); window.location='/personas';</script>";
            }
        }
    }

    /*=============================================
    EDITAR PERSONA
    =============================================*/
    public static function ctrEditarPersona(Request $request)
    {
        if ($request->has('idEditar')) {

            $datos = [
                //"id"              => $request->input('idEditar'),
                "nombre_completo" => strtolower(trim($request->input('nombreEditar'))),
                "documento"       => $request->input('documentoEditar'),
                "correo"          => strtolower(trim($request->input('correoEditar'))),
                "rol_id"          => $request->input('rolEditar'),
            ];

            $respuesta = ModeloUsuario::mdlEditarUsuario($datos);

            if ($respuesta == "ok") {
                echo "<script>alert('Usuario actualizado correctamente'); window.location='/personas';</script>";
            }
        }
    }

    /*=============================================
    ELIMINAR PERSONA
    =============================================*/
    public static function ctrEliminarPersona(Request $request)
    {
        if ($request->has('idEliminar')) {

            $id = $request->input('idEliminar');
            $respuesta = ModeloUsuario::mdlEliminarUsuario($id);

            if ($respuesta == "ok") {
                echo "<script>alert('Usuario eliminado correctamente'); window.location='/personas';</script>";
            }
        }
    }
}
