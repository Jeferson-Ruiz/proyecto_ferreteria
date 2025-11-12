<?php
require_once "app/controladores/facturacion.controlador.php";
require_once "app/modelos/facturacion.modelo.php";

$facturas = ControladorFacturacion::ctrMostrarFacturasConCliente();
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1><i class="fas fa-list"></i> Listado de Facturas</h1>
    </div>
  </section>

  <section class="content">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h3 class="card-title">🧾 Facturas Registradas</h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="bg-secondary text-white">
            <tr>
              <th>#</th>
              <th>Número</th>
              <th>Cliente</th>
              <th>Fecha</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($facturas)): ?>
              <?php foreach ($facturas as $i => $f): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= htmlspecialchars($f["numero_factura"]) ?></td>
                  <td><?= htmlspecialchars($f["cliente"]) ?></td>
                  <td><?= htmlspecialchars($f["fecha"]) ?></td>
                  <td>$<?= number_format($f["total"], 2) ?></td>
                  <td>
                    <!-- Ver PDF generado -->
                    <a href="factura_pdf/<?= htmlspecialchars($f["numero_factura"]) ?>.pdf" target="_blank" class="btn btn-info btn-sm">
                      <i class="fas fa-file-pdf"></i> Ver
                    </a>

                    <!-- Eliminar factura -->
                    <a href="index.php?ruta=listado-facturas&idFactura=<?= $f["id"] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Eliminar factura?')">
                      <i class="fas fa-trash"></i> Eliminar
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="6" class="text-center">No hay facturas registradas.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>
