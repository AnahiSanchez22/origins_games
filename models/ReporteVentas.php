<?php

require_once __DIR__ . '/../config/database.php';


class ReporteVentas
{
    private $db;


    public function __construct()
    {
        $this->db = Database::connect();
    }


    /**
     * ========================================================
     * RESUMEN DEL REPORTE
     * ========================================================
     *
     * Se consideran ventas los pedidos:
     *
     * - pagado
     * - enviado
     *
     * El cálculo de ventas y pedidos se hace por separado
     * del cálculo de unidades para evitar duplicar los
     * valores cuando un pedido tiene varios productos.
     */
    public function getResumen(
        $fechaInicio = null,
        $fechaFin = null
    ) {

        // ====================================================
        // VENTAS Y PEDIDOS
        // ====================================================

        $condiciones = [
            "p.estado IN ('pagado', 'enviado')"
        ];

        $parametros = [];


        if (!empty($fechaInicio)) {

            $condiciones[] =
                "DATE(p.fecha_pedido) >= :fecha_inicio";

            $parametros[':fecha_inicio'] =
                $fechaInicio;
        }


        if (!empty($fechaFin)) {

            $condiciones[] =
                "DATE(p.fecha_pedido) <= :fecha_fin";

            $parametros[':fecha_fin'] =
                $fechaFin;
        }


        $where = implode(
            ' AND ',
            $condiciones
        );


        /*
         * IMPORTANTE:
         *
         * Aquí NO hacemos JOIN con detalle_pedidos.
         *
         * Así cada pedido se cuenta una sola vez y
         * p.total no se multiplica por la cantidad
         * de productos del pedido.
         */
        $sqlVentas = "
            SELECT

                COALESCE(
                    SUM(p.total),
                    0
                ) AS total_ventas,

                COUNT(p.id)
                AS total_pedidos

            FROM pedidos p

            WHERE $where
        ";


        $stmtVentas =
            $this->db->prepare($sqlVentas);


        $stmtVentas->execute(
            $parametros
        );


        $resultadoVentas =
            $stmtVentas->fetch(
                PDO::FETCH_ASSOC
            );


        if (!$resultadoVentas) {

            $resultadoVentas = [
                'total_ventas' => 0,
                'total_pedidos' => 0
            ];
        }


        // ====================================================
        // UNIDADES VENDIDAS
        // ====================================================

        $condicionesUnidades = [
            "p.estado IN ('pagado', 'enviado')"
        ];

        $parametrosUnidades = [];


        if (!empty($fechaInicio)) {

            $condicionesUnidades[] =
                "DATE(p.fecha_pedido) >= :fecha_inicio";

            $parametrosUnidades[':fecha_inicio'] =
                $fechaInicio;
        }


        if (!empty($fechaFin)) {

            $condicionesUnidades[] =
                "DATE(p.fecha_pedido) <= :fecha_fin";

            $parametrosUnidades[':fecha_fin'] =
                $fechaFin;
        }


        $whereUnidades =
            implode(
                ' AND ',
                $condicionesUnidades
            );


        $sqlUnidades = "
            SELECT

                COALESCE(
                    SUM(dp.cantidad),
                    0
                ) AS total_unidades

            FROM detalle_pedidos dp

            INNER JOIN pedidos p
                ON p.id = dp.pedido_id

            WHERE $whereUnidades
        ";


        $stmtUnidades =
            $this->db->prepare(
                $sqlUnidades
            );


        $stmtUnidades->execute(
            $parametrosUnidades
        );


        $resultadoUnidades =
            $stmtUnidades->fetch(
                PDO::FETCH_ASSOC
            );


        // ====================================================
        // RESULTADO FINAL
        // ====================================================

        return [

            /*
             * Estos nombres coinciden con los que utiliza
             * la vista views/reportes/ventas.php
             */
            'total_ventas' =>
                (float) (
                    $resultadoVentas['total_ventas']
                    ?? 0
                ),

            'total_pedidos' =>
                (int) (
                    $resultadoVentas['total_pedidos']
                    ?? 0
                ),

            'total_unidades' =>
                (int) (
                    $resultadoUnidades['total_unidades']
                    ?? 0
                )
        ];
    }


    /**
     * ========================================================
     * DETALLE DE VENTAS
     * ========================================================
     */
    public function getVentas(
        $fechaInicio = null,
        $fechaFin = null
    ) {

        $condiciones = [
            "p.estado IN ('pagado', 'enviado')"
        ];

        $parametros = [];


        if (!empty($fechaInicio)) {

            $condiciones[] =
                "DATE(p.fecha_pedido) >= :fecha_inicio";

            $parametros[':fecha_inicio'] =
                $fechaInicio;
        }


        if (!empty($fechaFin)) {

            $condiciones[] =
                "DATE(p.fecha_pedido) <= :fecha_fin";

            $parametros[':fecha_fin'] =
                $fechaFin;
        }


        $where =
            implode(
                ' AND ',
                $condiciones
            );


        $sql = "
            SELECT

                p.id AS id,

                p.fecha_pedido,

                u.nombre AS cliente,

                u.email,

                p.estado,

                p.total

            FROM pedidos p

            INNER JOIN usuarios u
                ON u.id = p.usuario_id

            WHERE $where

            ORDER BY

                p.fecha_pedido DESC,

                p.id DESC
        ";


        $stmt =
            $this->db->prepare($sql);


        $stmt->execute(
            $parametros
        );


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }


    /**
     * ========================================================
     * PRODUCTOS VENDIDOS
     * ========================================================
     */
    public function getProductosVendidos(
        $fechaInicio = null,
        $fechaFin = null
    ) {

        $condiciones = [
            "p.estado IN ('pagado', 'enviado')"
        ];

        $parametros = [];


        if (!empty($fechaInicio)) {

            $condiciones[] =
                "DATE(p.fecha_pedido) >= :fecha_inicio";

            $parametros[':fecha_inicio'] =
                $fechaInicio;
        }


        if (!empty($fechaFin)) {

            $condiciones[] =
                "DATE(p.fecha_pedido) <= :fecha_fin";

            $parametros[':fecha_fin'] =
                $fechaFin;
        }


        $where =
            implode(
                ' AND ',
                $condiciones
            );


        $sql = "
            SELECT

                pr.nombre AS producto,

                SUM(dp.cantidad)
                AS cantidad_vendida,

                SUM(
                    dp.cantidad *
                    dp.precio_unitario
                )
                AS total_vendido

            FROM detalle_pedidos dp

            INNER JOIN pedidos p
                ON p.id = dp.pedido_id

            INNER JOIN productos pr
                ON pr.id = dp.producto_id

            WHERE $where

            GROUP BY

                dp.producto_id,

                pr.nombre

            ORDER BY

                cantidad_vendida DESC,

                pr.nombre ASC
        ";


        $stmt =
            $this->db->prepare($sql);


        $stmt->execute(
            $parametros
        );


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }
}