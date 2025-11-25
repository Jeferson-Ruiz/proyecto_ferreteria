@extends('layouts.plantilla')

@section('content')
<style>
.table thead th {
    background-color: #343a40;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card {
    border-radius: 12px;
}

.btn {
    border-radius: 6px;
}

.btn-warning { background-color: #ffc107; border: none; }
.btn-danger { background-color: #dc3545; border: none; }
.btn-success { background-color: #28a745; border: none; }
.btn-info { background-color: #17a2b8; border: none; }

.modal-header { border-bottom: 2px solid #343a40; }
.form-control { border-radius: 8px; }

.content-wrapper {
    padding: 20px 30px;
    transition: all 0.3s ease;
}
</style>

<!-- 🔹 Envoltura necesaria para AdminLTE -->
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid mt-4">
      <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4 fw-bold">Gestión de Clientes Mayoristas</h2>

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

        <!-- BARRA DE BÚSQUEDA Y BOTÓN -->
        <div class="card mb-4">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <!-- BARRA DE BÚSQUEDA A LA IZQUIERDA -->
              <form method="GET" action="{{ route('clientes-mayorista.buscar') }}">
                <div class="input-group">
                  <input type="text" name="termino" class="form-control" placeholder="Buscar cliente..." 
                         value="{{ request('termino') }}">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </form>
              
              <!-- BOTÓN AGREGAR A LA DERECHA -->
              <button class="btn btn-success" data-toggle="modal" data-target="#modalAgregarCliente">
                <i class="fas fa-plus"></i> Nuevo Cliente
              </button>
            </div>
          </div>
        </div>

        <!-- TABLA DE CLIENTES MAYORISTAS -->
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Empresa</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Deuda</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if($clientes && count($clientes) > 0)
                        @foreach($clientes as $key => $cliente)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $cliente->empresa ?? '' }}</td>
                                <td>{{ $cliente->contacto ?? '' }}</td>
                                <td>{{ $cliente->telefono ?? '' }}</td>
                                <td>{{ $cliente->correo ?? '' }}</td>
                                <td>{{ $cliente->direccion ?? '' }}</td>
                                <td>${{ number_format($cliente->debe, 2) }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditarCliente{{ $cliente->id }}">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalDeuda{{ $cliente->id }}">
                                            <i class="fas fa-money-bill"></i> Deuda
                                        </button>
                                        <form method="POST" action="{{ route('clientes-mayorista.eliminar') }}" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="idCliente" value="{{ $cliente->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este cliente?')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- MODAL EDITAR -->
                            <div class="modal fade" id="modalEditarCliente{{ $cliente->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('clientes-mayorista.editar') }}">
                                            @csrf
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title">Editar Cliente</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="idCliente" value="{{ $cliente->id }}">
                                                <div class="form-group">
                                                    <label>Empresa</label>
                                                    <input type="text" class="form-control" name="editarEmpresa" value="{{ $cliente->empresa }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Contacto</label>
                                                    <input type="text" class="form-control" name="editarContacto" value="{{ $cliente->contacto }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Teléfono</label>
                                                    <input type="text" class="form-control" name="editarTelefono" value="{{ $cliente->telefono }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" class="form-control" name="editarCorreo" value="{{ $cliente->correo }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Dirección</label>
                                                    <textarea class="form-control" name="editarDireccion" rows="3">{{ $cliente->direccion }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Deuda</label>
                                                    <input type="number" step="0.01" class="form-control" name="editarDeuda" value="{{ $cliente->debe }}">
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

                            <!-- MODAL DEUDA -->
                            <div class="modal fade" id="modalDeuda{{ $cliente->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('clientes-mayorista.actualizar-deuda') }}">
                                            @csrf
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title">Gestionar Deuda - {{ $cliente->empresa }}</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="idCliente" value="{{ $cliente->id }}">
                                                <div class="form-group">
                                                    <label>Deuda Actual</label>
                                                    <input type="text" class="form-control" value="${{ number_format($cliente->debe, 2) }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nueva Deuda</label>
                                                    <input type="number" step="0.01" class="form-control" name="nuevaDeuda" value="{{ $cliente->debe }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-info">Actualizar Deuda</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">No hay clientes mayoristas registrados.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- MODAL AGREGAR CLIENTE -->
<div class="modal fade" id="modalAgregarCliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('clientes-mayorista.crear') }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Agregar Cliente Mayorista</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Empresa</label>
                        <input type="text" class="form-control" name="nuevaEmpresa" placeholder="Nombre de la empresa" required>
                    </div>
                    <div class="form-group">
                        <label>Contacto</label>
                        <input type="text" class="form-control" name="nuevoContacto" placeholder="Persona de contacto" required>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" class="form-control" name="nuevoTelefono" placeholder="Teléfono" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="nuevoCorreo" placeholder="Correo electrónico" required>
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <textarea class="form-control" name="nuevaDireccion" placeholder="Dirección completa" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Deuda Inicial</label>
                        <input type="number" step="0.01" class="form-control" name="nuevaDeuda" value="0">
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