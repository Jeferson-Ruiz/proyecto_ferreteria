<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ModeloUsuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AutenticacionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function crear_usuario_y_verificar_login()
    {
        // 1. Primero crear un rol (necesario para la foreign key)
        \DB::table('roles')->insert([
            'id' => 1,
            'nombre' => 'Administrador',
            'descripcion' => 'Rol de administrador'
        ]);

        // 2. Crear usuario
        $documento = time();
        $correo = 'test' . $documento . '@test.com';

        $resultado = ModeloUsuarios::mdlIngresarUsuario('usuarios', [
            'nombre_completo' => 'Usuario Test',
            'documento' => $documento,
            'correo' => $correo,
            'contrasena' => 'password123',
            'rol_id' => 1
        ]);

        $this->assertEquals('ok', $resultado);

        // 3. Verificar que se puede obtener por correo
        $usuario = ModeloUsuarios::mdlObtenerUsuarioPorCorreo($correo);
        $this->assertNotNull($usuario);
        $this->assertEquals('Usuario Test', $usuario->nombre_completo);
    }

    /** @test */
    public function verificar_credenciales_de_usuario()
    {
        // 1. Crear rol
        \DB::table('roles')->insert([
            'id' => 2,
            'nombre' => 'Vendedor', 
            'descripcion' => 'Rol de vendedor'
        ]);

        // 2. Crear usuario con password hasheado
        $documento = rand(100000000, 999999999);
        $correo = 'verify' . $documento . '@test.com';
        $password = 'mipassword123';

        ModeloUsuarios::create([
            'nombre_completo' => 'Usuario Verify',
            'documento' => $documento,
            'correo' => $correo,
            'contrasena' => password_hash($password, PASSWORD_BCRYPT),
            'rol_id' => 2
        ]);

        // 3. Verificar credenciales
        $usuario = ModeloUsuarios::mdlObtenerUsuarioPorCorreo($correo);
        $this->assertNotNull($usuario);
        $this->assertTrue(password_verify($password, $usuario->contrasena));
        $this->assertFalse(password_verify('wrongpassword', $usuario->contrasena));
    }
}