<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModeloUsuarios;

class ControladorUsuarios extends Controller
{
    /* =============================================
       CREAR USUARIO
    ============================================= */
    public static function ctrCrearUsuario(Request $request)
    {
        if ($request->has("nuevoNombre")) {

            // Validaciones básicas
            if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $request->nuevoNombre)) {
                echo "<script>alert('Nombre no válido'); window.location='/usuarios';</script>";
                return;
            }

            if (!filter_var($request->nuevoCorreo, FILTER_VALIDATE_EMAIL)) {
                echo "<script>alert('Correo no válido'); window.location='/usuarios';</script>";
                return;
            }

            $tabla = "usuarios";

            $datos = [
                "nombre_completo" => trim($request->nuevoNombre),
                "documento"       => trim($request->nuevoDocumento),
                "correo"          => trim($request->nuevoCorreo),
                "contrasena"      => password_hash($request->nuevaContrasena, PASSWORD_DEFAULT),
                "rol_id"          => intval($request->nuevoRol)
            ];

            $respuesta = ModeloUsuarios::mdlIngresarUsuario($tabla, $datos);

            if ($respuesta === "duplicado") {
                echo "<script>alert('El correo o documento ya están registrados'); window.location='/usuarios';</script>";
                return;
            }

            if ($respuesta == "ok") {
                echo "<script>
                    alert('Usuario registrado correctamente');
                    window.location='/usuarios';
                </script>";
            } else {
                echo "<script>
                    alert('Error al registrar el usuario');
                    window.location='/usuarios';
                </script>";
            }
        }
    }

    /* =============================================
       MOSTRAR USUARIOS
    ============================================= */
    public static function ctrMostrarUsuarios($item = null, $valor = null)
    {
        $tabla = "usuarios";
        return ModeloUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);
    }

    /* =============================================
       EDITAR USUARIO
    ============================================= */
    public static function ctrEditarUsuario(Request $request)
    {
        if ($request->has("editarNombre")) {

            if (!filter_var($request->editarCorreo, FILTER_VALIDATE_EMAIL)) {
                echo "<script>alert('Correo no válido'); window.location='/usuarios';</script>";
                return;
            }

            $tabla = "usuarios";

            $datos = [
                "id"              => intval($request->idUsuario),
                "nombre_completo" => trim($request->editarNombre),
                "documento"       => trim($request->editarDocumento),
                "correo"          => trim($request->editarCorreo),
                "rol_id"          => intval($request->editarRol)
            ];

            $respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);

            if ($respuesta == "ok") {
                echo "<script>
                    alert('Usuario actualizado correctamente');
                    window.location='/usuarios';
                </script>";
            } else {
                echo "<script>
                    alert('Error al actualizar el usuario');
                    window.location='/usuarios';
                </script>";
            }
        }
    }

    /* =============================================
       BORRAR USUARIO
    ============================================= */
    public static function ctrBorrarUsuario(Request $request)
    {
        if ($request->has("idUsuario")) {

            $tabla = "usuarios";
            $id = intval($request->idUsuario);

            $respuesta = ModeloUsuarios::mdlEliminarUsuario($tabla, $id);

            if ($respuesta == "ok") {
                echo "<script>
                    alert('Usuario eliminado correctamente');
                    window.location='/usuarios';
                </script>";
            } else {
                echo "<script>
                    alert('Error al eliminar el usuario');
                    window.location='/usuarios';
                </script>";
            }
        }
    }
}
