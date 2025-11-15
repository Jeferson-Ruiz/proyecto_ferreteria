@extends('layouts.plantilla')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>Gestión de Roles</h1>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title">Listado de Roles</h3>
          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarRol">
            <i class="fas fa-plus"></i> Agregar Rol
          </button>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table id="tablaRoles" class="table table-bordered table-striped text-center">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre del Rol</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                @if($roles && count($roles) > 0)
                  @foreach($roles as $key => $rol)
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ $rol->nombre }}</td>
                      <td>
                        <div class="btn-group">
                          <button class="btn btn-warning btnEditarRol"
                                  data-toggle="modal"
                                  data-target="#modalEditarRol"
                                  data-id="{{ $rol->id }}"
                                  data-nombre="{{ $rol->nombre }}">
                            <i class="fas fa-edit"></i>
                          </button>
                          <form method="POST" action="{{ route('roles.destroy', $rol->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este rol?');">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="3" class="text-center">No hay roles registrados</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- MODAL AGREGAR ROL -->
<div id="modalAgregarRol" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('roles.store') }}">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h4 class="modal-title">Agregar Rol</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control" name="nuevoRol" placeholder="Nombre del rol" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDITAR ROL -->
<div id="modalEditarRol" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="formEditarRol">
        @csrf
        @method('PUT')
        <div class="modal-header bg-warning text-white">
          <h4 class="modal-title">Editar Rol</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idRol" name="idRol">
          <input type="text" class="form-control" id="editarRol" name="editarRol" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
document.querySelectorAll(".btnEditarRol").forEach(btn => {
  btn.addEventListener("click", () => {
    document.getElementById("idRol").value = btn.dataset.id;
    document.getElementById("editarRol").value = btn.dataset.nombre;
    
    // Actualizar action del formulario
    const form = document.getElementById("formEditarRol");
    form.action = "{{ url('roles') }}/" + btn.dataset.id;
  });
});

$(document).ready(function() {
  $('#tablaRoles').DataTable({
    "language": { "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" },
    "responsive": true,
    "autoWidth": false,
    "pageLength": 5
  });
});
</script>
@endsection