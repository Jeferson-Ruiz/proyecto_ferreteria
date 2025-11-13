<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Usuario; 

class UsuariosController extends Controller
{
    /* =============================================
       CREAR USUARIO
    ============================================= */
    public function crear(Request $request)
    {
        $request->validate([
            'nuevoNombre' => ['required', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/'],
            'nuevoCorreo' => ['required', 'email'],
            'nuevoDocumento' => ['required', 'integer'],
            'nuevaContrasena' => ['required'],
            'nuevoRol' => ['required', 'integer']
        ]);

        $datos = [
            'nombre_completo' => trim($request->nuevoNombre),
            'documento' => trim($request->nuevoDocumento),
            'correo' => trim($request->nuevoCorreo),
            'contrasena' => bcrypt($request->nuevaContrasena),
            'rol_id' => intval($request->nuevoRol)
        ];

        $respuesta = Usuario::mdlIngresarUsuario($datos);

        if ($respuesta === 'duplicado') {
            return redirect()->route('usuarios.index')
                             ->with('error', 'El correo o documento ya están registrados');
        }

        if ($respuesta === 'ok') {
            return redirect()->route('usuarios.index')
                             ->with('success', 'Usuario registrado correctamente');
        }

        return redirect()->route('usuarios.index')
                         ->with('error', 'Error al registrar el usuario');
    }

    /* =============================================
       MOSTRAR USUARIOS
    ============================================= */
    public function index()
    {
        $usuarios = Usuario::mdlMostrarUsuarios();
        return view('usuarios.index', compact('usuarios'));
    }

    /* =============================================
       EDITAR USUARIO
    ============================================= */
    public function editar(Request $request)
    {
        $request->validate([
            'editarNombre' => ['required'],
            'editarCorreo' => ['required', 'email'],
            'editarDocumento' => ['required', 'integer'],
            'editarRol' => ['required', 'integer']
        ]);

        $datos = [
            'id' => intval($request->idUsuario),
            'nombre_completo' => trim($request->editarNombre),
            'documento' => trim($request->editarDocumento),
            'correo' => trim($request->editarCorreo),
            'rol_id' => intval($request->editarRol),
            'contrasena' => $request->editarContrasena ?? null
        ];

        $respuesta = Usuario::mdlEditarUsuario($datos);

        if ($respuesta === 'ok') {
            return redirect()->route('usuarios.index')
                             ->with('success', 'Usuario actualizado correctamente');
        }

        return redirect()->route('usuarios.index')
                         ->with('error', 'Error al actualizar el usuario');
    }

    /* =============================================
       BORRAR USUARIO
    ============================================= */
    public function eliminar($id)
    {
        $respuesta = Usuario::mdlEliminarUsuario($id);

        if ($respuesta === 'ok') {
            return redirect()->route('usuarios.index')
                             ->with('success', 'Usuario eliminado correctamente');
        }

        return redirect()->route('usuarios.index')
                         ->with('error', 'Error al eliminar el usuario');
    }
}
