<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ModeloFacturacion
{
    /*=============================================
    CREAR FACTURA
    =============================================*/
    public static function mdlCrearFactura($tabla, $datos)
    {
        try {
            $lastId = DB::table($tabla)->insertGetId([
                "numero_factura"     => $datos["numero_factura"],
                "cliente_nombre"     => $datos["cliente_nombre"],
                "cliente_documento"  => $datos["cliente_documento"],
                "total"              => $datos["total"],
                "fecha"              => now()
            ]);

            return $lastId;

        } catch (\Exception $e) {
            \Log::error("❌ Error al crear factura: " . $e->getMessage());
            return 0;
        }
    }

    /*=============================================
    CREAR DETALLE
    =============================================*/
    public static function mdlCrearDetalle($tabla, $datos)
    {
        try {
            DB::table($tabla)->insert([
                "factura_id"      => $datos["factura_id"],
                "producto_id"     => $datos["producto_id"],
                "cantidad"        => $datos["cantidad"],
                "precio_unitario" => $datos["precio_unitario"],
                "subtotal" => $datos["subtotal"] // ✅ AGREGAR ESTA LÍNEA

            ]);

        } catch (\Exception $e) {
            \Log::error("❌ Error al crear detalle de factura: " . $e->getMessage());
        }
    }

    /*=============================================
    MOSTRAR FACTURAS CON CLIENTE Y TOTAL
    =============================================*/
    public static function mdlMostrarFacturasConCliente($tabla = "facturas")
    {
        try {
            return DB::table($tabla . " as f")
                ->leftJoin("detalle_factura as d", "f.id", "=", "d.factura_id")
                ->select(
                    "f.id",
                    "f.numero_factura",
                    "f.fecha",
                    DB::raw("COALESCE(f.cliente_nombre, '') AS cliente"),
                    DB::raw("IFNULL(SUM(d.cantidad * d.precio_unitario), 0) AS total")
                )
                ->groupBy("f.id", "f.numero_factura", "f.cliente_nombre", "f.fecha")
                ->orderByDesc("f.id")
                ->get();

        } catch (\Exception $e) {
            \Log::error("❌ Error al mostrar facturas: " . $e->getMessage());
            return [];
        }
    }

    /*=============================================
    MOSTRAR FACTURAS (simple)
    =============================================*/
    public static function mdlMostrarFacturas($tabla = "facturas")
    {
        try {
            return DB::table($tabla)
                ->orderByDesc("id")
                ->get();

        } catch (\Exception $e) {
            \Log::error("❌ Error al obtener facturas: " . $e->getMessage());
            return [];
        }
    }

    /*=============================================
    ELIMINAR FACTURA Y DETALLES
    =============================================*/
    public static function mdlEliminarFactura($tablaFactura, $tablaDetalle, $idFactura)
    {
        try {
            // Eliminar detalles
            DB::table($tablaDetalle)->where("factura_id", $idFactura)->delete();

            // Eliminar factura
            $deleted = DB::table($tablaFactura)->where("id", $idFactura)->delete();

            return $deleted ? "ok" : "error";

        } catch (\Exception $e) {
            \Log::error("❌ Error al eliminar factura: " . $e->getMessage());
            return "error";
        }
    }
}