@extends('layouts.plantilla')

@section('content')
<style>
.table thead th {
    background-color: #343a40;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn {
    border-radius: 6px;
}

.btn-warning { background-color: #ffc107; border: none; }
.btn-danger { background-color: #dc3545; border: none; }
.btn-success { background-color: #28a745; border: none; }

.modal-header { border-bottom: 2px solid #343a40; }
.form-control { border-radius: 8px; }
</style>

<!-- 🧡 Card principal -->
<div class="card shadow-lg p-4">
  <h2 class="text-center mb-4 fw-bold">Gestión de Proveedores</h2>

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

  <!-- FORMULARIO DE REGISTRO -->
  <form method="POST" action="{{ route('proveedores.store') }}" class="mb-4">
      @csrf
      <div class="row gx-2">
          <div class="col-lg-3 col-md-6 mb-2">
              <input type="text" class="form-control" name="empresa" placeholder="Nombre empresa" required>
          </div>
          <div class="col-lg-2 col-md-6 mb-2">
              <input type="text" class="form-control" name="asesor" placeholder="Asesor" required>
          </div>
          <div class="col-lg-2 col-md-6 mb-2">
              <input type="text" class="form-control" name="telefono" placeholder="Teléfono" required>
          </div>
          <div class="col-lg-3 col-md-6 mb-2">
              <input type="email" class="form-control" name="correo" placeholder="Correo electrónico" required>
          </div>
          <div class="col-lg-2 col-md-6 mb-2">
              <input type="text" class="form-control" name="productos" placeholder="Productos" required>
          </div>
      </div>

      <button type="submit" class="btn btn-success w-100 mt-2">
          <i class="fas fa-truck"></i> Registrar Proveedor
      </button>
  </form>

  <!-- TABLA DE PROVEEDORES -->
  <div class="table-responsive">
      <table class="table table-hover table-striped table-bordered align-middle text-center">
          <thead>
              <tr>
                  <th>#</th>
                  <th>Empresa</th>
                  <th>Asesor</th>
                  <th>Teléfono</th>
                  <th>Email</th>
                  <th>Productos</th>
                  <th>Acciones</th>
              </tr>
          </thead>
          <tbody>
              @if($proveedores && count($proveedores) > 0)
                  @foreach($proveedores as $key => $proveedor)
                      <tr>
                          <td>{{ $key + 1 }}</td>
                          <td>{{ ucwords($proveedor->empresa ?? '' )}}</td>
                          <td>{{ ucwords($proveedor->asesor ?? '' )}}</td>
                          <td>{{ $proveedor->telefono ?? '' }}</td>
                          <td>{{ $proveedor->correo ?? '' }}</td>
                          <td>{{ ucwords($proveedor->productos ?? '' )}}</td>
                          <td>
                              <button 
                                  type="button" 
                                  class="btn btn-warning btn-sm" 
                                  data-toggle="modal" 
                                  data-target="#editar{{ $proveedor->id }}">
                                  <i class="fas fa-edit"></i> Editar
                              </button>

                              <form method="POST" action="{{ route('proveedores.destroy', $proveedor->id) }}" style="display: inline;">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este proveedor?')">
                                      <i class="fas fa-trash"></i> Eliminar
                                  </button>
                              </form>
                          </td>
                      </tr>

                      <!-- MODAL EDITAR -->
                      <div class="modal fade" id="editar{{ $proveedor->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                  <form method="POST" action="{{ route('proveedores.update', $proveedor->id) }}">
                                      @csrf
                                      @method('PUT')
                                      <div class="modal-header bg-dark text-white">
                                          <h5 class="modal-title">Editar Proveedor</h5>
                                          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                      </div>

                                      <div class="modal-body">
                                          <input type="text" class="form-control mb-2" name="empresa" value="{{ $proveedor->empresa }}" required>
                                          <input type="text" class="form-control mb-2" name="asesor" value="{{ $proveedor->asesor }}" required>
                                          <input type="text" class="form-control mb-2" name="telefono" value="{{ $proveedor->telefono }}" required>
                                          <input type="email" class="form-control mb-2" name="correo" value="{{ $proveedor->correo }}" required>
                                          <input type="text" class="form-control mb-2" name="productos" value="{{ $proveedor->productos }}" required>
                                      </div>

                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                          <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>
                  @endforeach
              @else
                  <tr>
                      <td colspan="7" class="text-center">No hay proveedores registrados.</td>
                  </tr>
              @endif
          </tbody>
      </table>
  </div>
</div>
@endsection