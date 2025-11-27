<?php

namespace App\Http\Controllers;  
use Illuminate\Http\Request;
use App\Models\ModeloClientesMayoristas; // ← AGREGAR ESTA LÍNEA

class ControladorClienteMayorista extends Controller
{
    /*=============================================
    MOSTRAR CLIENTES MAYORISTAS
    =============================================*/
    public function mostrar($item = null, $valor = null)
    {
        $clientes = ModeloClientesMayoristas::mdlMostrarClientes($item, $valor);
        return view('modulos.clientes-mayorista', compact('clientes'));
    }
    

    /*=============================================
    CREAR CLIENTE MAYORISTA
    =============================================*/
    public function crear(Request $request)
    {
        $datos = [
            'empresa' => strtolower(trim($request->input('nuevaEmpresa'))),
            'contacto' => strtolower(trim($request->input('nuevoContacto'))),
            'telefono' => trim($request->input('nuevoTelefono')),
            'correo' => strtolower(trim($request->input('nuevoCorreo'))),
            'direccion' => strtolower(trim($request->input('nuevaDireccion'))),
            'debe' => $request->input('nuevaDeuda') ?? 0
        ];

        // Validar duplicados por empresa
        $existe = ModeloClientesMayoristas::mdlMostrarClientes('empresa', $datos['empresa']);
        if ($existe) {
            return back()->with('error', '⚠️ La empresa ya está registrada');
        }

        $respuesta = ModeloClientesMayoristas::mdlIngresarCliente($datos);
        if ($respuesta == "ok") {
            return redirect()->route('clientes-mayorista.index')->with('success', '✅ Cliente mayorista creado correctamente');
        }

        return back()->with('error', 'No se pudo crear el cliente');
    }

    /*=============================================
    EDITAR CLIENTE MAYORISTA
    =============================================*/
    public function editar(Request $request)
    {
        $datos = [
            'id' => $request->input('idCliente'),
            'empresa' => strtolower(trim($request->input('editarEmpresa'))),
            'contacto' => strtolower(trim($request->input('editarContacto'))),
            'telefono' => trim($request->input('editarTelefono')),
            'correo' => strtolower(trim($request->input('editarCorreo'))),
            'direccion' => strtolower(trim($request->input('editarDireccion'))),
            'debe' => $request->input('editarDeuda')
        ];

        $respuesta = ModeloClientesMayoristas::mdlEditarCliente($datos);
        if ($respuesta == "ok") {
            return redirect()->route('clientes-mayorista.index')->with('success', '✅ Cliente actualizado correctamente');
        }

        return back()->with('error', 'No se pudo actualizar el cliente');
    }

    /*=============================================
    ELIMINAR CLIENTE MAYORISTA
    =============================================*/
    public function eliminar(Request $request)
    {
        $id = $request->input('idCliente');
        $respuesta = ModeloClientesMayoristas::mdlEliminarCliente($id);

        if ($respuesta == "ok") {
            return redirect()->route('clientes-mayorista.index')->with('success', '🗑️ Cliente eliminado');
        }

        return back()->with('error', 'No se pudo eliminar el cliente');
    }

    /*=============================================
    BUSCAR CLIENTES MAYORISTAS
    =============================================*/
    public function buscar(Request $request)
    {
        $termino = $request->input('termino');
        
        if ($termino) {
            $clientes = ModeloClientesMayoristas::mdlBuscarCliente($termino);
        } else {
            $clientes = ModeloClientesMayoristas::mdlMostrarClientes();
        }
        
        return view('modulos.clientes-mayorista', compact('clientes'));
    }

    /*=============================================
    ACTUALIZAR DEUDA
    =============================================*/
    public function actualizarDeuda(Request $request)
    {
        $id = $request->input('idCliente');
        $monto = $request->input('nuevaDeuda');

        $respuesta = ModeloClientesMayoristas::mdlActualizarDeuda($id, $monto);
        if ($respuesta == "ok") {
            return redirect()->route('clientes-mayorista.index')->with('success', '✅ Deuda actualizada correctamente');
        }

        return back()->with('error', 'No se pudo actualizar la deuda');
    }
}