@extends('layouts.plantilla')

@section('contenido')
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid mt-4">
      <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4 fw-bold">Gestión de Usuarios</h2>

        <!-- Mensajes de alerta -->
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- FORMULARIO DE REGISTRO -->
        <form method="POST" class="mb-4" action="{{ route('usuarios.crear') }}">
            @csrf
            <div class="row gx-2">
                <div class="col-lg-3 col-md-6 mb-2">
                    <input type="text" class="form-control" name="nuevoNombre" placeholder="Nombre completo" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-2">
                    <input type="number" class="form-control" name="nuevoDocumento" placeholder="Documento" required>
                </div>
                <div class="col-lg-3 col-md-6 mb-2">
                    <input type="email" class="form-control" name="nuevoCorreo" placeholder="Correo electrónico" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-2">
                    <input type="password" class="form-control" name="nuevaContrasena" placeholder="Contraseña" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-2">
                    <select name="nuevoRol" class="form-control" required>
                        <option value="">Seleccionar rol</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-2">
                <i class="fas fa-user-plus"></i> Registrar Usuario
            </button>
        </form>

        <!-- TABLA DE USUARIOS -->
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $key => $usuario)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $usuario->nombre_completo }}</td>
                            <td>{{ $usuario->documento }}</td>
                            <td>{{ $usuario->correo }}</td>
                            <td>{{ $usuario->rol ?? 'Sin rol' }}</td>
                            <td>
                                <!-- Botón editar -->
                                <button 
                                    type="button" 
                                    class="btn btn-warning btn-sm" 
                                    data-toggle="modal" 
                                    data-target="#editar{{ $usuario->id }}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>

                                <!-- Botón eliminar -->
                                <form action="{{ route('usuarios.eliminar', $usuario->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- MODAL EDITAR -->
                        <div class="modal fade" id="editar{{ $usuario->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('usuarios.editar') }}">
                                        @csrf
                                        <input type="hidden" name="idUsuario" value="{{ $usuario->id }}">

                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title">Editar Usuario</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <input type="text" class="form-control mb-2" name="editarNombre" value="{{ $usuario->nombre_completo }}" required>
                                            <input type="number" class="form-control mb-2" name="editarDocumento" value="{{ $usuario->documento }}" required>
                                            <input type="email" class="form-control mb-2" name="editarCorreo" value="{{ $usuario->correo }}" required>

                                            <select name="editarRol" class="form-control mb-2" required>
                                                @foreach($roles as $rol)
                                                    <option value="{{ $rol->id }}" {{ $usuario->rol_id == $rol->id ? 'selected' : '' }}>
                                                        {{ $rol->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
