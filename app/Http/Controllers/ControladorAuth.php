<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
                ->where('email', $correo)
                ->first(); // fetch(PDO::FETCH_ASSOC)

            if ($usuario) {

                if (password_verify($contrasena, $usuario->contrasena)) {

                    // Guardar datos en sesión
                    Session::put('iniciarSesion', 'ok');
                    Session::put('id', $usuario->id);
                    Session::put('nombre', $usuario->nombre);
                    Session::put('email', $usuario->email);
                    Session::put('rol', $usuario->rol);

                    // Redirección según rol
                    if ($usuario->rol === 'Administrador') {
                        return redirect()->to('/inicio');
                    } else {
                        return redirect()->to('/productos');
                    }

                } else {
                    echo "<script>alert('Contraseña incorrecta'); window.location='/login';</script>";
                    exit;
                }

            } else {
                echo "<script>alert('Usuario no encontrado'); window.location='/login';</script>";
                exit;
            }
        }
    }

    /* ===========================================
       CERRAR SESIÓN
    ============================================ */
    public function logout()
    {
        Session::flush();
        return redirect()->to('/login');
    }
}
