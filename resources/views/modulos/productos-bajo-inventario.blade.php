@extends('layouts.plantilla')

@section('content')
<!-- 🧡 Card principal -->
<div class="card">
    <div class="card-header bg-warning text-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-exclamation-triangle"></i> Productos Bajos en Inventario
        </h3>
        <p class="mb-0 mt-2">Productos con stock menor a 10 unidades</p>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Precio Unitario</th>
                    </tr>
                </thead>
                <tbody>
                    @if($productos && count($productos) > 0)
                        @foreach($productos as $key => $producto)
                            <tr class="{{ $producto->stock < 5 ? 'table-danger' : '' }}">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ ucwords($producto->nombre) }}</td>
                                <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                                <td>
                                    <span class="badge {{ $producto->stock < 5 ? 'bg-danger' : 'bg-warning' }}">
                                        {{ $producto->stock }}
                                    </span>
                                </td>
                                <td>${{ number_format($producto->precio_unitario, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">🎉 ¡Excelente! No hay productos bajos en inventario.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection