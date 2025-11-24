<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- LOGO / NOMBRE -->
  <a href="{{ url('/inicio') }}" class="brand-link">
    <span class="brand-text font-weight-light">Ferretería San Miguel</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
        
        <!-- INICIO -->
        <li class="nav-item">
          <a href="{{ url('/inicio') }}" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>Inicio</p>
          </a>
        </li>

        <!-- USUARIOS -->
        <li class="nav-item">
        <a href="{{ url('/usuarios') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Usuarios</p>
          </a>
        </li>

        <!-- ROLES -->
        <li class="nav-item">
          <a href="{{ url('/roles') }}" class="nav-link">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>Roles</p>
          </a>
        </li>

        <!-- CATEGORÍAS -->
        <li class="nav-item">
          <a href="{{ url('/categorias') }}" class="nav-link">
            <i class="nav-icon fas fa-tags"></i>
            <p>Categorías</p>
          </a>
        </li>

        <!-- PRODUCTOS -->
        <li class="nav-item">
          <a href="{{ url('/productos') }}" class="nav-link">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Productos</p>
          </a>
        </li>

        <!-- PROVEEDORES -->
        <li class="nav-item">
          <a href="{{ url('/proveedores') }}" class="nav-link">
           <i class="nav-icon fas fa-truck"></i>
            <p>Proveedores</p>
          </a>
        </li>

        <!-- FACTURACIÓN -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>Facturación<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/facturas') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Nueva Factura</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/listado-facturas') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado de Facturas</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- CERRAR SESIÓN -->
        <li class="nav-item">
          <a href="{{ url('/logout') }}" class="nav-link text-danger">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Cerrar sesión</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>