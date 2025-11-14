<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModeloUsuarios;
use Illuminate\Support\Facades\Session;

class ControladorAutenticacion extends Controller
{
    /* =============================================
       INICIAR SESIÓN
    ============================================= */
    public static function ctrIniciarSesion(Request $request)
    {
        if ($request->has(['correo', 'contrasena'])) {

            $correo = trim($request->input('correo'));
            $password = $request->input('contrasena');

            if (empty($correo) || empty($password)) {
                return back()->with('error', 'Debes ingresar todos los datos.');
            }

            // Aquí usamos tu método del modelo que ya migramos
            $usuario = ModeloUsuarios::mdlObtenerUsuarioPorCorreo($correo);

            if (!$usuario) {
                return back()->with('error', 'Usuario no encontrado');
            }

            if (password_verify($password, $usuario->contrasena)) {

                // Guardamos sesión igual que en tu código nativo
                Session::put('id_usuario', $usuario->id);
                Session::put('nombre', $usuario->nombre_completo);
                Session::put('rol_id', $usuario->rol_id);
                Session::put('iniciarSesion', 'ok');

                return redirect()->to('/inicio');
            } else {
                return back()->with('error', 'Contraseña incorrecta');
            }
        }
    }

    /* =============================================
       CERRAR SESIÓN
    ============================================= */
    public static function ctrCerrarSesion()
    {
        // Esta parte es equivalente a session_destroy()
        Session::flush();

        return redirect()->to('/login');
    }
}
