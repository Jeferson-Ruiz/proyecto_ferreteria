<?php
require_once __DIR__ . '/../../controladores/autenticacion.controlador.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ferretería | Iniciar Sesión</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="app/vistas/plugins/fontawesome-free/css/all.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="app/vistas/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page" style="background-color: #f4f6f9;">
<div class="login-box">
  <div class="login-logo">
    <b>Ferretería</b>App
  </div>

  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Inicia sesión para continuar</p>

      <form method="post">
        <div class="input-group mb-3">
          <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
          </div>
        </div>

        <?php ControladorAutenticacion::ctrIniciarSesion(); ?>
      </form>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="app/vistas/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="app/vistas/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="app/vistas/dist/js/adminlte.min.js"></script>
</body>
</html>
