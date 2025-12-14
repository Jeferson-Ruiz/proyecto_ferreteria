@extends('layouts.plantilla')

@section('content')
<!-- 🧡 Mostrar mensajes -->
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>¡Éxito!</strong> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>¡Error!</strong> {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

<!-- 🧡 Card principal -->
<div class="card mb-4">
  <div class="card-header">
    <h3 class="text-center">Gestión de Roles</h3>
    
    <div class="d-flex justify-content-between align-items-center">
      <!-- BARRA DE BÚSQUEDA -->
      <form method="GET" action="{{ route('roles.buscar') }}" class="flex-grow-1 mr-3">
        <div class="input-group">
          <input type="text" name="termino" class="form-control" placeholder="Buscar rol..." 
                 value="{{ request('termino') }}">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>
      
      <!-- BOTÓN AGREGAR -->
      <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarRol">
        <i class="fas fa-plus"></i> Agregar Rol
      </button>
    </div>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table id="tablaRoles" class="table table-bordered table-striped text-center">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre del Rol</th>
            <th>Descripción</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @if($roles && count($roles) > 0)
            @foreach($roles as $key => $rol)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ ucwords($rol->nombre )}}</td>
                <td>{{ ucwords($rol->descripcion ?? 'Sin descripción' )}}</td>
                <td>
                  <div class="btn-group">
                    <button class="btn btn-warning btnEditarRol"
                            data-toggle="modal"
                            data-target="#modalEditarRol"
                            data-id="{{ $rol->id }}"
                            data-nombre="{{ $rol->nombre }}"
                            data-descripcion="{{ $rol->descripcion ?? '' }}">
                      <i class="fas fa-edit"></i> Editar
                    </button>
                    <form method="POST" action="{{ route('roles.destroy', $rol->id) }}" style="display: inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este rol?');">
                        <i class="fas fa-trash"></i> Eliminar
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="4" class="text-center">No hay roles registrados</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
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
          <div class="form-group">
            <label>Nombre del Rol</label>
            <input type="text" class="form-control" name="nuevoRol" placeholder="Nombre del rol" required>
          </div>
          <div class="form-group">
            <label>Descripción</label>
            <textarea class="form-control" name="nuevaDescripcion" placeholder="Descripción del rol" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
          <div class="form-group">
            <label>Nombre del Rol</label>
            <input type="text" class="form-control" id="editarRol" name="editarRol" required>
          </div>
          <div class="form-group">
            <label>Descripción</label>
            <textarea class="form-control" id="editarDescripcion" name="editarDescripcion" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.querySelectorAll(".btnEditarRol").forEach(btn => {
  btn.addEventListener("click", () => {
    document.getElementById("idRol").value = btn.dataset.id;
    document.getElementById("editarRol").value = btn.dataset.nombre;
    document.getElementById("editarDescripcion").value = btn.dataset.descripcion;
    
    // Actualizar action del formulario
    const form = document.getElementById("formEditarRol");
    form.action = "{{ url('roles') }}/" + btn.dataset.id;
  });
});

$(document).ready(function() {
  $('#tablaRoles').DataTable({
    "language": { 
      "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" 
    },
    "responsive": true,
    "autoWidth": false,
    "pageLength": 5,
    "searching": false
  });
});
</script>
@endsection