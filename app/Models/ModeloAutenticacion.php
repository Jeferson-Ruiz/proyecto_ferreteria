<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ModeloAutenticacion
{
    /*
     * Obtener usuario por correo
     */
    public static function mdlIniciarSesion($correo)
    {
        try {
            return DB::table('usuarios')
                ->where('correo', $correo)
                ->first(); // Igual que fetch(PDO::FETCH_ASSOC)
        } catch (\Exception $e) {
            dd("Error en mdlIniciarSesion: " . $e->getMessage());
        }
    }
}
