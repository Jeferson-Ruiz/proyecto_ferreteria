@extends('layouts.plantilla')

@section('content')
<!-- Contenedor principal -->
<div class="content-wrapper">
  <!-- Encabezado de la página -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Panel de control</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Contenido principal -->
  <section class="content">
    <div class="container-fluid">
      <!-- 🧡 Bienvenida -->
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <h2 class="mb-3">👋 Bienvenido al Panel de Control de la <strong>Ferretería San Miguel</strong></h2>
          <p class="lead text-muted">
            Desde aquí puedes administrar <strong>usuarios, roles, productos, categorías</strong> y la <strong>facturación</strong>.
          </p>
          <hr>
          <p>Selecciona una opción del menú lateral para comenzar.</p>
        </div>
      </div>

      <!-- 📊 Dashboard de estadísticas -->
      <div class="row mt-4">
        <!-- Usuarios -->
        <div class="col-lg-3 col-6">
          <a href="{{ route('usuarios.index') }}" class="small-box bg-info enlace-dashboard">
            <div class="inner">
            <h3>{{ $totalUsuarios }}</h3>
              <p>Usuarios Registrados</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
          </a>
        </div>

        <!-- Productos -->
        <div class="col-lg-3 col-6">
          <a href="{{ route('productos.index') }}" class="small-box bg-success enlace-dashboard">
            <div class="inner">
            <h3>{{ $totalProductos }}</h3>
              <p>Productos en Inventario</p>
            </div>
            <div class="icon">
              <i class="fas fa-boxes"></i>
            </div>
          </a>
        </div>

        <!-- Categorías -->
        <div class="col-lg-3 col-6">
          <a href="{{ route('categorias.index') }}" class="small-box bg-warning enlace-dashboard">
            <div class="inner">
            <h3>{{ $totalCategorias }}</h3>
              <p>Categorías</p>
            </div>
            <div class="icon">
              <i class="fas fa-tags"></i>
            </div>
          </a>
        </div>

        <!-- Facturación -->
        <div class="col-lg-3 col-6">
          <a href="{{ route('listado.facturas') }}" class="small-box bg-danger enlace-dashboard">
            <div class="inner">
            <h3>${{ number_format($totalFacturas, 2) }}</h3>
              <p>Ventas</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-invoice-dollar"></i>
            </div>
          </a>
        </div>
        <!-- Productos bajos en inventario -->
        <div class="col-lg-3 col-6">
            <a href="{{ route('productos.bajo-inventario') }}" class="small-box bg-warning enlace-dashboard">
                <div class="inner">
                    <h3>{{ $productosBajoInventario }}</h3>
                    <p>Productos bajos stock</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </a>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection