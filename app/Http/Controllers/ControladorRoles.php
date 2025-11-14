<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModeloRoles;

class ControladorRoles extends Controller
{
    /* ======================================
       Mostrar Roles
    ====================================== */
    public static function ctrMostrarRoles($item = null, $valor = null)
    {
        return ModeloRoles::mdlMostrarRoles($item, $valor);
    }

    /* ======================================
       Crear Rol
    ====================================== */
    public static function ctrCrearRol(Request $request)
    {
        if (!empty($request->nuevoRol)) {

            $tabla = "roles";
            $datos = ["nombre" => trim($request->nuevoRol)];

            $rolExistente = ModeloRoles::mdlMostrarRoles("nombre", $datos["nombre"]);

            if ($rolExistente) {
                echo "<script>
                    alert('❌ Este rol ya existe.');
                    window.location='/roles';
                </script>";
                return;
            }

            $respuesta = ModeloRoles::mdlIngresarRol($tabla, $datos);

            if ($respuesta == "ok") {
                echo "<script>
                    alert('✅ Rol agregado correctamente');
                    window.location='/roles';
                </script>";
            }
        }
    }

    /* ======================================
       Editar Rol
    ====================================== */
    public static function ctrEditarRol(Request $request)
    {
        if (!empty($request->editarRol)) {

            $tabla = "roles";

            $datos = [
                "id"     => $request->idRol,
                "nombre" => trim($request->editarRol)
            ];

            $respuesta = ModeloRoles::mdlEditarRol($tabla, $datos);

            if ($respuesta == "ok") {
                echo "<script>
                    alert('✅ Rol actualizado correctamente');
                    window.location='/roles';
                </script>";
            }
        }
    }

    /* ======================================
       Borrar Rol
    ====================================== */
    public static function ctrBorrarRol(Request $request)
    {
        if ($request->has("idRol")) {

            $tabla = "roles";
            $id = intval($request->idRol);

            $respuesta = ModeloRoles::mdlBorrarRol($tabla, $id);

           	if ($respuesta == "ok") {
                echo "<script>
                    alert('🗑️ Rol eliminado correctamente');
                    window.location='/roles';
                </script>";
            }
        }
    }
}
