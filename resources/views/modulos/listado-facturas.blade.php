@extends('layouts.plantilla')

@section('content')
<!-- 🧡 Card principal -->
<div class="card">
  <div class="card-header bg-primary text-white">
    <h3 class="card-title text-center"><i class="fas fa-list"></i> Listado de Facturas</h3>
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
        @if($facturas && count($facturas) > 0)
          @foreach($facturas as $i => $f)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>{{ $f->numero_factura }}</td>
              <td>{{ ucwords($f->cliente )}}</td>
              <td>{{ $f->fecha }}</td>
              <td>${{ number_format($f->total, 2) }}</td>
              <td>
                <!-- Ver PDF generado -->
                <a href="{{ asset('factura_pdf/' . $f->numero_factura . '.pdf') }}" target="_blank" class="btn btn-info btn-sm">
                  <i class="fas fa-file-pdf"></i> Ver
                </a>

                <!-- Eliminar factura -->
                <form method="POST" action="{{ route('facturas.eliminar') }}" style="display: inline;">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" name="idFactura" value="{{ $f->id }}">
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar factura?')">
                    <i class="fas fa-trash"></i> Eliminar
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        @else
          <tr><td colspan="6" class="text-center">No hay facturas registradas.</td></tr>
        @endif
      </tbody>
    </table>
  </div>
</div>
@endsection