@extends('layouts.plantilla')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1><i class="fas fa-boxes"></i> Gestión de Productos</h1>
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
          <form method="GET" action="{{ route('productos.buscar') }}">
            <div class="input-group">
              <input type="text" name="termino" class="form-control" placeholder="Buscar producto o categoría..." 
                     value="{{ request('termino') }}">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
          
          <!-- BOTÓN AGREGAR A LA DERECHA -->
          <button class="btn btn-success" data-toggle="modal" data-target="#modalAgregarProducto">
            <i class="fas fa-plus"></i> Nuevo Producto
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
                <th>Categoría</th>
                <th>Stock</th>
                <th>Precio Unitario</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @if($productos && count($productos) > 0)
                @foreach($productos as $key => $producto)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                    <td>{{ $producto->stock }}</td>
                    <td>${{ number_format($producto->precio_unitario, 2) }}</td>
                    <td>
                      <div class="btn-group">
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditarProducto{{ $producto->id }}">
                          <i class="fas fa-edit"></i> Editar
                        </button>
                        <form method="POST" action="{{ route('productos.eliminar') }}" style="display: inline;">
                          @csrf
                          <input type="hidden" name="idProducto" value="{{ $producto->id }}">
                          <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar producto?')">
                            <i class="fas fa-trash"></i> Eliminar
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>

                  <!-- Modal Editar -->
                  <div class="modal fade" id="modalEditarProducto{{ $producto->id }}" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form method="POST" action="{{ route('productos.editar') }}">
                          @csrf
                          <div class="modal-header bg-warning">
                            <h5 class="modal-title">Editar Producto</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <input type="hidden" name="idProducto" value="{{ $producto->id }}">
                            <div class="form-group">
                              <label>Nombre</label>
                              <input type="text" name="editarProducto" class="form-control" value="{{ $producto->nombre }}" required>
                            </div>
                            <div class="form-group">
                              <label>Categoría</label>
                              <select name="editarCategoria" class="form-control" required>
                                @foreach($categorias as $cat)
                                  <option value="{{ $cat->id }}" {{ $cat->id == $producto->categoria_id ? 'selected' : '' }}>
                                    {{ $cat->nombre }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Stock</label>
                              <input type="number" name="editarStock" class="form-control" value="{{ $producto->stock }}" required>
                            </div>
                            <div class="form-group">
                              <label>Precio Unitario</label>
                              <input type="number" step="0.01" name="editarPrecioUnitario" class="form-control" value="{{ $producto->precio_unitario }}" required>
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
                  <td colspan="6" class="text-center">No hay productos registrados</td>
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
<div class="modal fade" id="modalAgregarProducto" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('productos.crear') }}">
        @csrf
        <div class="modal-header bg-success">
          <h5 class="modal-title">Agregar Producto</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nuevoProducto" class="form-control" placeholder="Ej: Martillo" required>
          </div>
          <div class="form-group">
            <label>Categoría</label>
            <select name="nuevaCategoria" class="form-control" required>
              <option value="">Seleccione...</option>
              @foreach($categorias as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Stock</label>
            <input type="number" name="nuevoStock" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Precio Unitario</label>
            <input type="number" step="0.01" name="nuevoPrecioUnitario" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
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