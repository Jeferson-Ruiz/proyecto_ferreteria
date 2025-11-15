<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ControladorPlantilla extends Controller
{
    public function ctrPlantilla(Request $request)
    {
        // Obtener la ruta de la URL, por defecto 'inicio'
        $ruta = $request->query('ruta', 'inicio');
        $ruta = strtolower($ruta);

        // RUTA pública: login
        if ($ruta === 'login') {
            return view('modulos.login');
        }

        // Si no hay sesión iniciada => redirigir al login
        if (Session::get('iniciarSesion') !== 'ok') {
            return redirect('/?ruta=login');
        }

        // Definir rutas permitidas por rol
        $permisos_por_rol = [
            1 => [ // ADMIN
                "inicio","usuarios","personas","productos","categorias",
                "roles","facturas","listado-facturas","logout","login"
            ],
            2 => [ // VENDEDOR
                "inicio","productos","facturas","listado-facturas","logout","login"
            ]
        ];

        $rol_id = (int) Session::get('rol_id', 0);

        // Si el rol no está en el mapa => mostrar 403
        if (!array_key_exists($rol_id, $permisos_por_rol)) {
            return view('modulos.403');
        }

        // Si la ruta no está permitida para el rol => 403
        if (!in_array($ruta, $permisos_por_rol[$rol_id])) {
            return view('modulos.403');
        }

        // Todo OK, cargar plantilla principal
        return view('layouts.plantilla', ['modulo' => $ruta]);
    }
}