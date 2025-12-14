<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Obtener el rol del usuario desde la sesión
        $userRole = Session::get('rol');
        
        // Si no hay usuario autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }
        
        // Verificar si es admin (rol 1)
        if ($role === 'admin' && $userRole != 1) {
            return redirect()->route('inicio')
                ->with('error', 'Acceso denegado: Solo administradores pueden acceder a esta sección');
        }
        
        return $next($request);
    }
}