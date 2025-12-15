<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class ControladorAuth extends Controller
{
    /* ===========================================
       LOGIN / AUTENTICACIÓN DE USUARIO
    ============================================ */
    public function ctrIngresarUsuario(Request $request)
    {
        if ($request->has(['correo', 'contrasena'])) {

            $correo = trim($request->input('correo'));
            $contrasena = trim($request->input('contrasena'));

            $usuario = DB::table('usuarios')
                ->where('correo', $correo) 
                ->first();

            if ($usuario) {

                if (password_verify($contrasena, $usuario->contrasena)) {

                    Auth::loginUsingId($usuario->id);

                    // Guardar datos en sesión
                    Session::put('iniciarSesion', 'ok');
                    Session::put('id', $usuario->id);
                    Session::put('nombre', $usuario->nombre_completo);
                    Session::put('email', $usuario->correo); // ✅ También corregido aquí
                    Session::put('rol', $usuario->rol_id);

                    // Redirección según rol 
                    if ($usuario->rol_id=== 1 ) { //rol 1 administrador
                        return redirect()->to('/inicio');
                    } else {
                        return redirect()->to('/inicio');
                    }

                } else {
                    return back()->with('error', 'Contraseña incorrecta');
                }

            } else {
                return back()->with('error', 'Usuario no encontrado');
            }
        }
    }

    /* ===========================================
       CERRAR SESIÓN
    ============================================ */
    public function logout()
    {
        Auth::logout();

        Session::flush();
        return redirect()->to('/login');
    }
}