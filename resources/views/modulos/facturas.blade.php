@extends('layouts.plantilla')

@section('content')
<!-- 🧡 Mostrar mensajes -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- 🧡 Card principal -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title text-center"><i class="fas fa-file-invoice-dollar"></i> Nueva Factura</h3>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('facturas.crear') }}" id="formFactura">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre del cliente:</label>
                        <input type="text" name="cliente_nombre" class="form-control" placeholder="Ej: Juan Pérez" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Documento del cliente:</label>
                        <input type="text" name="cliente_documento" class="form-control" placeholder="Ej: 12345678" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Buscar producto:</label>
                <input type="text" id="buscarProducto" class="form-control" placeholder="Escriba el nombre del producto...">
                <div id="listaProductos" class="list-group mt-2"></div>
            </div>

            <table class="table table-bordered mt-4" id="tablaDetalle">
                <thead class="bg-secondary text-white">
                <tr>
                    <th>Producto</th>
                    <th width="100">Cantidad</th>
                    <th width="120">Precio</th>
                    <th width="120">Subtotal</th>
                    <th width="50">❌</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="text-right mt-3">
                <h4>Total: $<span id="totalFactura">0.00</span></h4>
            </div>

            <input type="hidden" name="productos" id="productosJSON">

            <div class="text-right">
                <button type="submit" class="btn btn-success btn-lg" id="btnGuardar">
                    <i class="fas fa-save"></i> Guardar Factura
                </button>
            </div>
        </form>
    </div>
</div>

<style>
#listaProductos { max-height: 200px; overflow-y: auto; }
.table td, .table th { vertical-align: middle !important; }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const productos = @json($productos);
    const buscarInput = document.getElementById("buscarProducto");
    const lista = document.getElementById("listaProductos");
    const tbody = document.querySelector("#tablaDetalle tbody");
    const totalEl = document.getElementById("totalFactura");
    const inputJSON = document.getElementById("productosJSON");
    const form = document.getElementById("formFactura");
    const btnGuardar = document.getElementById("btnGuardar");
    let carrito = [];

    // Buscar productos
    buscarInput.addEventListener("keyup", () => {
        const texto = buscarInput.value.toLowerCase();
        lista.innerHTML = "";
        if (texto.trim() === "") return;
        
        const filtrados = productos.filter(p => 
            p.nombre.toLowerCase().includes(texto)
        );
        
        filtrados.forEach(p => {
            const item = document.createElement("button");
            item.type = "button";
            item.className = "list-group-item list-group-item-action";
            item.textContent = `${p.nombre} - $${p.precio_unitario}`;
            item.onclick = () => agregarProducto(p);
            lista.appendChild(item);
        });
    });

    function agregarProducto(prod) {
        const existe = carrito.find(p => p.id === prod.id);
        if (existe) {
            existe.cantidad++;
        } else {
            carrito.push({
                id: prod.id,
                nombre: prod.nombre, 
                precio_unitario: parseFloat(prod.precio_unitario),
                cantidad: 1
            });
        }
        renderCarrito();
        lista.innerHTML = "";
        buscarInput.value = "";
    }

    function renderCarrito() {
        tbody.innerHTML = "";
        let total = 0;
        
        carrito.forEach((p, i) => {
            const subtotal = p.cantidad * p.precio_unitario;
            total += subtotal;
            
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${p.nombre}</td>
                <td>
                    <input type="number" min="1" value="${p.cantidad}" 
                           class="form-control form-control-sm" 
                           onchange="actualizarCantidad(${i}, this.value)">
                </td>
                <td>$${p.precio_unitario.toFixed(2)}</td>
                <td>$${subtotal.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" 
                            onclick="eliminarProducto(${i})">X</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
        
        totalEl.textContent = total.toFixed(2);
        inputJSON.value = JSON.stringify(carrito);
    }

    window.eliminarProducto = (index) => {
        carrito.splice(index, 1);
        renderCarrito();
    };

    window.actualizarCantidad = (index, cantidad) => {
        const nuevaCantidad = parseInt(cantidad) || 1;
        carrito[index].cantidad = nuevaCantidad;
        renderCarrito();
    };

    // ✅ SOLUCIÓN CORREGIDA - Formulario tradicional
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        
        // Validaciones básicas
        if (carrito.length === 0) {
            alert('❌ Debe agregar al menos un producto');
            return;
        }

        const clienteNombre = document.querySelector('input[name="cliente_nombre"]').value.trim();
        const clienteDocumento = document.querySelector('input[name="cliente_documento"]').value.trim();
        
        if (!clienteNombre || !clienteDocumento) {
            alert('❌ Complete todos los datos del cliente');
            return;
        }

        // Actualizar el hidden input
        inputJSON.value = JSON.stringify(carrito);
        
        // Deshabilitar botón
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

        // ✅ ENVÍO TRADICIONAL - Sin Fetch API
        this.submit();
    
        // Mostrar alerta después de enviar
        setTimeout(() => {
            alert('✅ La factura fue creada con éxito');
            // El botón se reseteará cuando la página recargue
        }, 100);
    });
});
</script>
@endsection