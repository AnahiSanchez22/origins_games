<?php

require_once __DIR__ . '/../config/database.php';

class Dashboard
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }


    /**
     * Obtiene los datos principales del Dashboard.
     */
    public function getResumen()
    {
        $sql = "SELECT
                    (
                        SELECT COALESCE(SUM(total), 0)
                        FROM pedidos
                        WHERE estado IN ('pagado', 'enviado')
                    ) AS ventas_totales,

                    (
                        SELECT COUNT(*)
                        FROM pedidos
                    ) AS total_pedidos,

                    (
                        SELECT COUNT(*)
                        FROM usuarios
                    ) AS total_usuarios,

                    (
                        SELECT COUNT(*)
                        FROM productos
                    ) AS total_productos,

                    (
                        SELECT COUNT(*)
                        FROM productos
                        WHERE stock = 0
                    ) AS productos_sin_stock,

                    (
                        SELECT COUNT(*)
                        FROM productos
                        WHERE stock BETWEEN 1 AND 3
                    ) AS productos_stock_bajo";

        $stmt = $this->db->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Obtiene la cantidad de pedidos según su estado.
     */
    public function getPedidosPorEstado()
    {
        $sql = "SELECT
                    estado,
                    COUNT(*) AS cantidad
                FROM pedidos
                GROUP BY estado";

        $stmt = $this->db->query($sql);

        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [
            'pendiente' => 0,
            'pagado' => 0,
            'enviado' => 0,
            'cancelado' => 0
        ];

        foreach ($filas as $fila) {

            $estado = $fila['estado'];

            if (isset($resultado[$estado])) {
                $resultado[$estado] = (int) $fila['cantidad'];
            }
        }

        return $resultado;
    }


    /**
     * Obtiene los últimos pedidos realizados.
     */
    public function getPedidosRecientes($limite = 5)
    {
        $limite = max(1, (int) $limite);

        $sql = "SELECT
                    p.id,
                    p.total,
                    p.estado,
                    p.fecha_pedido,
                    u.nombre AS usuario_nombre,
                    u.email AS usuario_email
                FROM pedidos p
                INNER JOIN usuarios u
                    ON p.usuario_id = u.id
                ORDER BY
                    p.fecha_pedido DESC,
                    p.id DESC
                LIMIT " . $limite;

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Obtiene productos con stock bajo.
     */
    public function getProductosStockBajo($limite = 6)
    {
        $limite = max(1, (int) $limite);

        $sql = "SELECT
                    id,
                    nombre,
                    precio,
                    stock
                FROM productos
                WHERE stock <= 3
                ORDER BY
                    stock ASC,
                    nombre ASC
                LIMIT " . $limite;

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Obtiene las ventas agrupadas por mes
     * de los últimos 6 meses.
     *
     * Solo se consideran pedidos pagados o enviados.
     */
    public function getVentasPorMes()
    {
        $sql = "SELECT
                    YEAR(fecha_pedido) AS anio,
                    MONTH(fecha_pedido) AS mes,
                    COALESCE(SUM(total), 0) AS ventas
                FROM pedidos
                WHERE estado IN ('pagado', 'enviado')
                AND fecha_pedido >= DATE_SUB(
                    DATE_FORMAT(CURDATE(), '%Y-%m-01'),
                    INTERVAL 5 MONTH
                )
                GROUP BY
                    YEAR(fecha_pedido),
                    MONTH(fecha_pedido)
                ORDER BY
                    anio ASC,
                    mes ASC";

        $stmt = $this->db->query($sql);

        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];

        /*
         * Creamos los últimos 6 meses aunque alguno
         * no tenga ventas, para que la gráfica no
         * quede incompleta.
         */

        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        $ventasEncontradas = [];

        foreach ($filas as $fila) {

            $clave =
                (int) $fila['anio'] .
                '-' .
                str_pad(
                    (int) $fila['mes'],
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            $ventasEncontradas[$clave] =
                (float) $fila['ventas'];
        }


        for ($i = 5; $i >= 0; $i--) {

            $fecha = new DateTime(
                'first day of this month'
            );

            if ($i > 0) {
                $fecha->modify("-{$i} months");
            }

            $anio = (int) $fecha->format('Y');
            $mes = (int) $fecha->format('n');

            $clave =
                $anio .
                '-' .
                str_pad(
                    $mes,
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            $resultado[] = [
                'mes' => $meses[$mes],
                'anio' => $anio,
                'ventas' =>
                    $ventasEncontradas[$clave] ?? 0
            ];
        }

        return $resultado;
    }


    /**
     * Obtiene los productos más vendidos.
     *
     * Solo se consideran pedidos pagados o enviados.
     */
    public function getProductosMasVendidos($limite = 5)
    {
        $limite = max(1, (int) $limite);

        $sql = "SELECT
                    pr.nombre,
                    SUM(dp.cantidad) AS unidades_vendidas,
                    COALESCE(
                        SUM(
                            dp.cantidad * dp.precio_unitario
                        ),
                        0
                    ) AS ingresos
                FROM detalle_pedidos dp

                INNER JOIN pedidos p
                    ON p.id = dp.pedido_id

                INNER JOIN productos pr
                    ON pr.id = dp.producto_id

                WHERE p.estado IN ('pagado', 'enviado')

                GROUP BY
                    dp.producto_id,
                    pr.nombre

                ORDER BY
                    unidades_vendidas DESC,
                    ingresos DESC

                LIMIT " . $limite;

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}