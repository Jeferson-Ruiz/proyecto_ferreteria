@extends('layouts.plantilla')

@section('contenido')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1><i class="fas fa-boxes"></i> Gestión de Productos</h1>
    </div>
  </section>

  <section class="content">
    <!-- Mensajes de éxito o error -->
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
      <div class="card-header bg-success text-white">
        <h3 class="card-title">Listado de Productos</h3>
        <button class="btn btn-light float-right" data-toggle="modal" data-target="#modalAgregarProducto">
          <i class="fas fa-plus"></i> Nuevo Producto
        </button>
      </div>

      <div class="card-body">
        <table class="table table-bordered table-striped">
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
            @foreach ($productos as $key => $value)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $value['nombre'] }}</td>
                <td>{{ $value['categoria'] }}</td>
                <td>{{ $value['stock'] }}</td>
                <td>${{ number_format($value['precio_unitario'], 2) }}</td>
                <td>
                  <a href="#" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditarProducto{{ $value['id'] }}">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form action="{{ route('productos.eliminar') }}" method="POST" style="display:inline">
                    @csrf
                    <input type="hidden" name="idProducto" value="{{ $value['id'] }}">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar producto?')">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

<!-- Modal Agregar Producto -->
<div class="modal fade" id="modalAgregarProducto" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('productos.crear') }}" method="POST">
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
              @foreach ($categorias as $cat)
                <option value="{{ $cat['id'] }}">{{ $cat['nombre'] }}</option>
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

<!-- Modales Editar Producto (uno por producto, fuera de la tabla) -->
@foreach ($productos as $value)
<div class="modal fade" id="modalEditarProducto{{ $value['id'] }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('productos.editar') }}" method="POST">
        @csrf
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Editar Producto</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="idProducto" value="{{ $value['id'] }}">
          <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="editarProducto" class="form-control" value="{{ $value['nombre'] }}" required>
          </div>
          <div class="form-group">
            <label>Categoría</label>
            <select name="editarCategoria" class="form-control" required>
              @foreach ($categorias as $cat)
                <option value="{{ $cat['id'] }}" {{ $cat['id'] == $value['categoria_id'] ? 'selected' : '' }}>
                  {{ $cat['nombre'] }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Stock</label>
            <input type="number" name="editarStock" class="form-control" value="{{ $value['stock'] }}" required>
          </div>
          <div class="form-group">
            <label>Precio Unitario</label>
            <input type="number" step="0.01" name="editarPrecioUnitario" class="form-control" value="{{ $value['precio_unitario'] }}" required>
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
@endsection
