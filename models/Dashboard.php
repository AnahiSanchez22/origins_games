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
}