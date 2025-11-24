@extends('layouts.plantilla')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1><i class="fas fa-tags"></i> Gestión de Categorías</h1>
    </div>
  </section>

  <section class="content">
    <!-- Mostrar mensajes -->
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

    <div class="card mb-4">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <!-- BARRA DE BÚSQUEDA A LA IZQUIERDA -->
          <form method="GET" action="{{ route('categorias.buscar') }}">
            <div class="input-group">
              <input type="text" name="termino" class="form-control" placeholder="Buscar categoría..." 
                     value="{{ request('termino') }}">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
          
          <!-- BOTÓN AGREGAR A LA DERECHA -->
          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCategoria">
            <i class="fas fa-plus"></i> Nueva Categoría
          </button>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center">
            <thead>
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @if($categorias && count($categorias) > 0)
                @foreach($categorias as $key => $categoria)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $categoria->nombre }}</td>
                    <td>{{ $categoria->descripcion ?? 'Sin descripción' }}</td>
                    <td>
                      <div class="btn-group">
                        <button class="btn btn-warning btn-sm" 
                                data-toggle="modal" 
                                data-target="#modalEditarCategoria{{ $categoria->id }}">
                          <i class="fas fa-edit"></i> Editar
                        </button>
                        <form method="POST" action="{{ route('categorias.eliminar') }}" style="display: inline;">
                          @csrf
                          <input type="hidden" name="idCategoria" value="{{ $categoria->id }}">
                          <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar categoría?')">
                            <i class="fas fa-trash"></i> Eliminar
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>

                  <!-- Modal Editar -->
                  <div class="modal fade" id="modalEditarCategoria{{ $categoria->id }}" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form method="POST" action="{{ route('categorias.editar') }}">
                          @csrf
                          <div class="modal-header bg-warning">
                            <h5 class="modal-title">Editar Categoría</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <input type="hidden" name="idCategoria" value="{{ $categoria->id }}">
                            <div class="form-group">
                              <label>Nombre</label>
                              <input type="text" name="editarCategoria" class="form-control" value="{{ $categoria->nombre }}" required>
                            </div>
                            <div class="form-group">
                              <label>Descripción</label>
                              <textarea name="editarDescripcion" class="form-control" rows="3" placeholder="Descripción de la categoría">{{ $categoria->descripcion ?? '' }}</textarea>
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
                @endforeach
              @else
                <tr>
                  <td colspan="4" class="text-center">No hay categorías registradas</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Agregar -->
<div class="modal fade" id="modalAgregarCategoria" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('categorias.crear') }}">
        @csrf
        <div class="modal-header bg-primary">
          <h5 class="modal-title">Agregar Categoría</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nuevaCategoria" class="form-control" placeholder="Ej: Herramientas" required>
          </div>
          <div class="form-group">
            <label>Descripción</label>
            <textarea name="nuevaDescripcion" class="form-control" rows="3" placeholder="Descripción de la categoría"></textarea>
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

<script>
$(document).ready(function() {
  $('.table').DataTable({
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