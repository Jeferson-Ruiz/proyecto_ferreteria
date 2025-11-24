<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModeloRoles;

class ControladorRoles extends Controller
{
    /* ======================================
       Mostrar Roles (index)
    ====================================== */
    public function ctrMostrarRoles()
    {
        $roles = ModeloRoles::mdlMostrarRoles();
        return view('modulos.roles', compact('roles'));
    }

    /* ======================================
       Crear Rol (store)
    ====================================== */
    public function ctrCrearRol(Request $request)
    {
        if (!empty($request->nuevoRol)) {

            $datos = ["nombre" => trim($request->nuevoRol),
                    "descripcion" => trim($request->nuevaDescripcion)];


            // Validar que el rol no exista
            $rolExistente = ModeloRoles::mdlMostrarRoles("nombre", $datos["nombre"]);
            if ($rolExistente) {
                return back()->with('error', '❌ Este rol ya existe.');
            }

            $respuesta = ModeloRoles::mdlIngresarRol("roles", $datos);

            if ($respuesta == "ok") {
                return redirect()->route('roles.index')->with('success', '✅ Rol agregado correctamente');
            }
        }

        return back()->with('error', 'No se pudo crear el rol');
    }


    /* ======================================
    Buscar Roles
    ====================================== */
    public function buscar(Request $solicitud)
    {
        $termino = $solicitud->input('termino');
        
        if ($termino) {
            $roles = ModeloRoles::mdlBuscarRol($termino);
        } else {
            $roles = ModeloRoles::mdlMostrarRoles();
        }
        
        return view('modulos.roles', compact('roles'));
    }

    /* ======================================
       Editar Rol (update)
    ====================================== */
    public function ctrEditarRol(Request $request, $id)
    {
        if (!empty($request->editarRol)) {

            $datos = [
                "id"     => $id,
                "nombre" => trim($request->editarRol),
                "descripcion" => trim($request->editarDescripcion)

            ];

            $respuesta = ModeloRoles::mdlEditarRol("roles", $datos);

            if ($respuesta == "ok") {
                return redirect()->route('roles.index')->with('success', '✅ Rol actualizado correctamente');
            }
        }

        return back()->with('error', 'No se pudo actualizar el rol');
    }

    /* ======================================
       Eliminar Rol (destroy)
    ====================================== */
    public function ctrBorrarRol($id)
    {
        $respuesta = ModeloRoles::mdlBorrarRol("roles", $id);

        if ($respuesta == "ok") {
            return redirect()->route('roles.index')->with('success', '🗑️ Rol eliminado correctamente');
        }

        return back()->with('error', 'No se pudo eliminar el rol');
    }
}