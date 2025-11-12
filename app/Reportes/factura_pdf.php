<?php
require_once "../modelos/conexion.php";
require_once "../modelos/facturacion.modelo.php";
require_once "../modelos/productos.modelo.php";
require_once "../modelos/usuarios.modelo.php";

require_once "../../extensiones/fpdf/fpdf.php";

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode('Factura de Venta'), 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

if (!isset($_GET["id"])) {
    die("❌ ID de factura no especificado");
}

$idFactura = $_GET["id"];

try {
    $conexion = Conexion::conectar();

    // ✅ CONSULTA PRINCIPAL DE FACTURA (con nombre_completo en lugar de usuario)
    $stmt = $conexion->prepare("
        SELECT 
            f.id,
            f.numero_factura,
            f.fecha,
            f.total,
            f.cliente_nombre,
            f.cliente_documento,
            u.nombre_completo AS usuario
        FROM facturas f
        LEFT JOIN usuarios u ON f.usuario_id = u.id
        WHERE f.id = :id
    ");
    $stmt->bindParam(":id", $idFactura, PDO::PARAM_INT);
    $stmt->execute();
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        die("❌ No se encontró la factura.");
    }

    // ✅ CONSULTA DETALLES
    $stmtDetalles = $conexion->prepare("
        SELECT 
            p.nombre AS producto,
            d.cantidad,
            d.precio_unitario,
            d.subtotal
        FROM detalle_factura d
        INNER JOIN productos p ON d.producto_id = p.id
        WHERE d.factura_id = :id
    ");
    $stmtDetalles->bindParam(":id", $idFactura, PDO::PARAM_INT);
    $stmtDetalles->execute();
    $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

    // ✅ GENERAR PDF
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(0, 10, utf8_decode('Número de factura: ') . $factura["numero_factura"], 0, 1);
    $pdf->Cell(0, 10, utf8_decode('Fecha: ') . $factura["fecha"], 0, 1);
    $pdf->Cell(0, 10, utf8_decode('Cliente: ') . $factura["cliente_nombre"], 0, 1);
    $pdf->Cell(0, 10, utf8_decode('Documento: ') . $factura["cliente_documento"], 0, 1);
    $pdf->Cell(0, 10, utf8_decode('Atendido por: ') . $factura["usuario"], 0, 1);

    $pdf->Ln(10);

    // ✅ Encabezado de tabla
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(80, 8, utf8_decode('Producto'), 1);
    $pdf->Cell(30, 8, utf8_decode('Cantidad'), 1);
    $pdf->Cell(40, 8, utf8_decode('Precio Unitario'), 1);
    $pdf->Cell(40, 8, utf8_decode('Subtotal'), 1);
    $pdf->Ln();

    // ✅ Contenido de tabla
    $pdf->SetFont('Arial', '', 11);
    foreach ($detalles as $detalle) {
        $pdf->Cell(80, 8, utf8_decode($detalle["producto"]), 1);
        $pdf->Cell(30, 8, $detalle["cantidad"], 1, 0, 'C');
        $pdf->Cell(40, 8, "$" . number_format($detalle["precio_unitario"], 2), 1, 0, 'R');
        $pdf->Cell(40, 8, "$" . number_format($detalle["subtotal"], 2), 1, 0, 'R');
        $pdf->Ln();
    }

    // ✅ Total
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(150, 10, 'Total:', 0, 0, 'R');
    $pdf->Cell(40, 10, "$" . number_format($factura["total"], 2), 0, 1, 'R');

    $pdf->Output();

} catch (PDOException $e) {
    die("Error al generar el PDF: " . $e->getMessage());
}
?>
