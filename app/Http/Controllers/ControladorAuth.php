<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\RecuperacionContrasenaMail;
use Illuminate\Support\Facades\Mail;

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

    /* ============================================
    RECUPERACIÓN DE CONTRASEÑA
    ============================================ */

    // Mostrar formulario "Olvidé mi contraseña"
    public function mostrarOlvideContrasena()
    {
        return view('modulos.olvide-contrasena');
    }

    // Enviar enlace de recuperación
    public function enviarLinkRecuperacion(Request $request)
    {
        $request->validate(['correo' => 'required|email']);
        
        $usuario = DB::table('usuarios')->where('correo', $request->correo)->first();
        
        if (!$usuario) {
            return back()->with('error', 'Correo no encontrado en el sistema');
        }
        
        // Generar token único
        $token = Str::random(60);
        
        // Guardar token en la tabla (expira en 24 horas)
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->correo],
            ['token' => $token, 'created_at' => now()]
        );
        
        // Enviar email
        try {
            Mail::to($request->correo)->send(new RecuperacionContrasenaMail($token, $request->correo));
            
            return redirect()->route('password.sent')->with('email', $request->correo);
            
        } catch (\Exception $e) {
            // Si falla el email (en desarrollo), muestra el enlace
            \Log::error('Error enviando email: ' . $e->getMessage());
            
            return back()->with('info', 
                'En desarrollo: <a href="' . url('/restablecer-contrasena/' . $token) . '">Haz clic aquí</a> para restablecer contraseña.'
            )->with('correo', $request->correo);
        }
    }

    // Mostrar formulario para nueva contraseña
    public function mostrarFormRestablecer($token)
    {
        $reset = DB::table('password_resets')->where('token', $token)->first();
        
        if (!$reset) {
            return redirect()->route('password.request')
                ->with('error', 'Token inválido o expirado');
        }
        
        return view('modulos.nueva-contrasena', [
            'token' => $token,
            'correo' => $reset->email
        ]);
    }

    // Actualizar contraseña
    public function restablecerPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'correo' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);
        
        // Verificar token
        $reset = DB::table('password_resets')
            ->where('email', $request->correo)
            ->where('token', $request->token)
            ->first();
        
        if (!$reset) {
            return back()->with('error', 'Token inválido o expirado');
        }
        
        // Actualizar contraseña
        DB::table('usuarios')
            ->where('correo', $request->correo)
            ->update(['contrasena' => password_hash($request->password, PASSWORD_DEFAULT)]);
        
        // Eliminar token usado
        DB::table('password_resets')->where('email', $request->correo)->delete();
        
        return redirect()->route('login')
            ->with('success', 'Contraseña actualizada correctamente. Ahora puedes iniciar sesión.');
    }
}