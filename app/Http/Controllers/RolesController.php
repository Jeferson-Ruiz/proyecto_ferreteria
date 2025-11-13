<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;

class RolesController extends Controller
{
    /* ======================================
       Mostrar Roles
    ====================================== */
    public function index()
    {
        $roles = Rol::mostrarRoles();
        return view('roles.index', compact('roles'));
    }

    /* ======================================
       Crear Rol
    ====================================== */
    public function store(Request $request)
    {
        $request->validate([
            'nuevoRol' => 'required|string|max:255'
        ]);

        $nombre = trim($request->input('nuevoRol'));

        // Validar que el rol no exista
        $rolExistente = Rol::mostrarRoles('nombre', $nombre);
        if ($rolExistente) {
            return redirect()->route('roles.index')
                ->with('error', '❌ Este rol ya existe.');
        }

        $respuesta = Rol::crearRol(['nombre' => $nombre]);

        if ($respuesta === 'ok') {
            return redirect()->route('roles.index')
                ->with('success', '✅ Rol agregado correctamente');
        }

        return redirect()->route('roles.index')
            ->with('error', '❌ Error al agregar el rol');
    }

    /* ======================================
       Editar Rol
    ====================================== */
    public function update(Request $request, $id)
    {
        $request->validate([
            'editarRol' => 'required|string|max:255'
        ]);

        $datos = [
            'id' => $id,
            'nombre' => trim($request->input('editarRol'))
        ];

        $respuesta = Rol::editarRol($datos);

        if ($respuesta === 'ok') {
            return redirect()->route('roles.index')
                ->with('success', '✅ Rol actualizado correctamente');
        }

        return redirect()->route('roles.index')
            ->with('error', '❌ Error al actualizar el rol');
    }

    /* ======================================
       Borrar Rol
    ====================================== */
    public function destroy($id)
    {
        $respuesta = Rol::borrarRol($id);

        if ($respuesta === 'ok') {
            return redirect()->route('roles.index')
                ->with('success', '🗑️ Rol eliminado correctamente');
        }

        return redirect()->route('roles.index')
            ->with('error', '❌ Error al eliminar el rol');
    }
}
