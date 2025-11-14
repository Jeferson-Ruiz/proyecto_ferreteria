<?php

namespace App\Http\Controllers;

use App\Models\ModeloFacturacion;
use App\Models\Producto;
use App\Models\ModeloUsuarios;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class ControladorFacturacion extends Controller
{
    /*=============================================
    CREAR FACTURA
    =============================================*/
    public static function ctrCrearFactura(Request $request)
    {
        if ($request->has("productos") && !empty($request->productos)
            && $request->has("cliente_nombre") && $request->has("cliente_documento")) {

            // iniciar sesión si no está
            if (!Session::isStarted()) {
                Session::start();
            }

            $productos = json_decode($request->productos, true);
            $cliente_nombre = trim($request->cliente_nombre);
            $cliente_documento = trim($request->cliente_documento);
            $usuario_id = Session::get("id_usuario");
            $numero_factura = "FAC-" . rand(1000, 9999);

            // Calcular total
            $total = 0;
            foreach ($productos as $p) {
                $total += $p["cantidad"] * $p["precio_unitario"];
            }

            // Guardar factura
            $datosFactura = [
                "numero_factura"     => $numero_factura,
                "cliente_nombre"     => $cliente_nombre,
                "cliente_documento"  => $cliente_documento,
                "usuario_id"         => $usuario_id,
                "total"              => $total
            ];

            $idFactura = ModeloFacturacion::mdlCrearFactura("facturas", $datosFactura);

            if ($idFactura > 0) {

                // Guardar detalle
                foreach ($productos as $p) {
                    $detalle = [
                        "factura_id"      => $idFactura,
                        "producto_id"     => $p["id"],
                        "cantidad"        => $p["cantidad"],
                        "precio_unitario" => $p["precio_unitario"],
                        "subtotal"        => $p["cantidad"] * $p["precio_unitario"]
                    ];

                    ModeloFacturacion::mdlCrearDetalle("detalle_factura", $detalle);
                }

                // Generar PDF
                self::generarFacturaPDF($numero_factura, $cliente_nombre, $productos, $total);

                // Simular tus alert + redirección
                return redirect()
                    ->route('listado.facturas')
                    ->with('alert', "Factura generada correctamente. PDF disponible.");
            }

            return back()->with('alert', '❌ Error al guardar la factura');
        }
    }

    /*=============================================
    ELIMINAR FACTURA
    =============================================*/
    public static function ctrEliminarFactura(Request $request)
    {
        if ($request->has("idFactura")) {

            $id = intval($request->idFactura);
            $res = ModeloFacturacion::mdlEliminarFactura("facturas", "detalle_factura", $id);

            if ($res == "ok") {
                return redirect()->route('listado.facturas')
                    ->with('alert', '🗑️ Factura eliminada.');
            } else {
                return back()->with('alert', '❌ Error al eliminar factura.');
            }
        }
    }

    /*=============================================
    GENERAR PDF DE FACTURA
    =============================================*/
    public static function generarFacturaPDF($numero, $cliente_nombre, $productos, $total)
    {
        // HTML igual al tuyo
        $html = '
        <h2 style="text-align:center;">🧾 Factura Electrónica</h2>
        <hr>
        <p><strong>Número:</strong> ' . $numero . '</p>
        <p><strong>Cliente:</strong> ' . htmlspecialchars($cliente_nombre) . '</p>
        <p><strong>Fecha:</strong> ' . date("Y-m-d H:i") . '</p>
        <hr>
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Precio Unitario</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>';

        foreach ($productos as $p) {
            $html .= '
            <tr>
              <td>' . htmlspecialchars($p["nombre"]) . '</td>
              <td>' . (int)$p["cantidad"] . '</td>
              <td>$' . number_format($p["precio_unitario"], 2) . '</td>
              <td>$' . number_format($p["cantidad"] * $p["precio_unitario"], 2) . '</td>
            </tr>';
        }

        $html .= '
          </tbody>
        </table>
        <hr>
        <h3 style="text-align:right;">Total: $' . number_format($total, 2) . '</h3>
        <p style="text-align:center;">Gracias por su compra 💚</p>';

        // Crear carpeta si no existe
        $rutaCarpeta = public_path("factura_pdf");
        if (!file_exists($rutaCarpeta)) {
            mkdir($rutaCarpeta, 0777, true);
        }

        // Instanciar Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Guardar PDF
        file_put_contents($rutaCarpeta . "/$numero.pdf", $dompdf->output());
    }

    /*=============================================
    MOSTRAR FACTURAS (wrappers)
    =============================================*/
    public static function ctrMostrarFacturasConCliente()
    {
        return ModeloFacturacion::mdlMostrarFacturasConCliente();
    }

    public static function ctrMostrarFacturas()
    {
        return ModeloFacturacion::mdlMostrarFacturas();
    }
}
