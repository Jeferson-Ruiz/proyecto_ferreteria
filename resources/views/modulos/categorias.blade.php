
<?php
require_once "app/controladores/categorias.controlador.php";
require_once "app/modelos/categorias.modelo.php";

// Crear
ControladorCategorias::ctrCrearCategoria();

// Editar
ControladorCategorias::ctrEditarCategoria();

// Eliminar
ControladorCategorias::ctrEliminarCategoria();

$categorias = ControladorCategorias::ctrMostrarCategorias(null, null);
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1><i class="fas fa-tags"></i> Gestión de Categorías</h1>
    </div>
  </section>

  <section class="content">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h3 class="card-title">Listado de Categorías</h3>
        <button class="btn btn-light float-right" data-toggle="modal" data-target="#modalAgregarCategoria">
          <i class="fas fa-plus"></i> Nueva Categoría
        </button>
      </div>

      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($categorias as $key => $value): ?>
              <tr>
                <td><?= $key+1 ?></td>
                <td><?= htmlspecialchars($value["nombre"]) ?></td>
                <td>
                  <a href="#" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditarCategoria<?= $value["id"] ?>">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="index.php?ruta=categorias&idCategoria=<?= $value["id"] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar categoría?')">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>

              <!-- Modal Editar -->
              <div class="modal fade" id="modalEditarCategoria<?= $value["id"] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form method="post">
                      <div class="modal-header bg-warning">
                        <h5 class="modal-title">Editar Categoría</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="idCategoria" value="<?= $value["id"] ?>">
                        <div class="form-group">
                          <label>Nombre</label>
                          <input type="text" name="editarCategoria" class="form-control" value="<?= $value["nombre"] ?>" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

<!-- Modal Agregar -->
<div class="modal fade" id="modalAgregarCategoria" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header bg-primary">
          <h5 class="modal-title">Agregar Categoría</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nuevaCategoria" class="form-control" placeholder="Ej: Herramientas" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>
