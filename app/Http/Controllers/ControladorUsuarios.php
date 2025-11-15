<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModeloUsuarios;
use App\Models\ModeloRoles;

class ControladorUsuarios extends Controller
{
    /* =============================================
       MOSTRAR USUARIOS (index)
    ============================================= */
    public function index()
    {
        $usuarios = ModeloUsuarios::mdlMostrarUsuarios("usuarios", null, null);
        $roles = ModeloRoles::mdlMostrarRoles();
        return view('modulos.usuarios', compact('usuarios', 'roles'));
    }

    /* =============================================
       CREAR USUARIO (store)
    ============================================= */
    public function store(Request $request)
    {
        // Validaciones básicas
        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $request->nuevoNombre)) {
            return back()->with('error', 'Nombre no válido');
        }

        if (!filter_var($request->nuevoCorreo, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'Correo no válido');
        }

        $datos = [
            "nombre_completo" => trim($request->nuevoNombre),
            "documento"       => trim($request->nuevoDocumento),
            "correo"          => trim($request->nuevoCorreo),
            "contrasena"      => password_hash($request->nuevaContrasena, PASSWORD_DEFAULT),
            "rol_id"          => intval($request->nuevoRol)
        ];

        $respuesta = ModeloUsuarios::mdlIngresarUsuario("usuarios", $datos);

        if ($respuesta === "duplicado") {
            return back()->with('error', 'El correo o documento ya están registrados');
        }

        if ($respuesta == "ok") {
            return redirect()->route('usuarios.index')->with('success', 'Usuario registrado correctamente');
        }

        return back()->with('error', 'Error al registrar el usuario');
    }

    /* =============================================
       EDITAR USUARIO (update)
    ============================================= */
    public function ctrEditarUsuario(Request $request, $id)
    {
        if (!filter_var($request->editarCorreo, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'Correo no válido');
        }

        $datos = [
            "id"              => $id,
            "nombre_completo" => trim($request->editarNombre),
            "documento"       => trim($request->editarDocumento),
            "correo"          => trim($request->editarCorreo),
            "rol_id"          => intval($request->editarRol)
        ];

        // Agregar contraseña si se proporcionó
        if (!empty($request->editarContrasena)) {
            $datos["contrasena"] = password_hash($request->editarContrasena, PASSWORD_DEFAULT);
        }

        $respuesta = ModeloUsuarios::mdlEditarUsuario("usuarios", $datos);

        if ($respuesta == "ok") {
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
        }

        return back()->with('error', 'Error al actualizar el usuario');
    }

    /* =============================================
       ELIMINAR USUARIO (destroy)
    ============================================= */
    public function ctrBorrarUsuario($id)
    {
        $respuesta = ModeloUsuarios::mdlEliminarUsuario("usuarios", $id);

        if ($respuesta == "ok") {
            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
        }

        return back()->with('error', 'Error al eliminar el usuario');
    }
}