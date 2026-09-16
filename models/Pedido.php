<?php

require_once __DIR__ . '/../config/database.php';

class Pedido
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Crear pedido y descontar stock de forma segura.
     */
    public function crearPedidoTransaccion(
        $usuario_id,
        $items,
        $total
    ) {
        try {
            $this->db->beginTransaction();

            /*
             * Verificar stock antes de crear el pedido.
             */
            $stmtProducto = $this->db->prepare(
                "SELECT id, nombre, precio, stock
                 FROM productos
                 WHERE id = ?
                 FOR UPDATE"
            );

            foreach ($items as $item) {

                $producto_id = (int) $item['id'];
                $cantidad = (int) $item['cantidad'];

                if ($cantidad <= 0) {
                    throw new Exception(
                        'Cantidad inválida.'
                    );
                }

                $stmtProducto->execute([
                    $producto_id
                ]);

                $producto = $stmtProducto->fetch(
                    PDO::FETCH_ASSOC
                );

                if (!$producto) {
                    throw new Exception(
                        'El producto no existe.'
                    );
                }

                $stock = (int) $producto['stock'];

                if ($stock < $cantidad) {
                    throw new Exception(
                        'Stock insuficiente para ' .
                        $producto['nombre']
                    );
                }
            }

            /*
             * Crear pedido.
             */
            $stmt = $this->db->prepare(
                "INSERT INTO pedidos
                (usuario_id, total, estado)
                VALUES (?, ?, 'pagado')"
            );

            $stmt->execute([
                $usuario_id,
                $total
            ]);

            $pedido_id = $this->db->lastInsertId();

            /*
             * Preparar inserción del detalle.
             */
            $stmtDetalle = $this->db->prepare(
                "INSERT INTO detalle_pedidos
                (
                    pedido_id,
                    producto_id,
                    cantidad,
                    precio_unitario
                )
                VALUES (?, ?, ?, ?)"
            );

            /*
             * Preparar descuento de stock.
             */
            $stmtStock = $this->db->prepare(
                "UPDATE productos
                 SET stock = stock - ?
                 WHERE id = ?
                 AND stock >= ?"
            );

            /*
             * Guardar cada producto y descontar inventario.
             */
            foreach ($items as $item) {

                $producto_id = (int) $item['id'];
                $cantidad = (int) $item['cantidad'];
                $precio = (float) $item['precio'];

                /*
                 * Detalle del pedido.
                 */
                $stmtDetalle->execute([
                    $pedido_id,
                    $producto_id,
                    $cantidad,
                    $precio
                ]);

                /*
                 * Descontar inventario.
                 */
                $stmtStock->execute([
                    $cantidad,
                    $producto_id,
                    $cantidad
                ]);

                /*
                 * Si no se actualizó exactamente
                 * una fila, cancelar toda la compra.
                 */
                if ($stmtStock->rowCount() != 1) {
                    throw new Exception(
                        'No fue posible actualizar el stock.'
                    );
                }
            }

            /*
             * Todo salió correctamente.
             */
            $this->db->commit();

            return $pedido_id;

        } catch (Exception $e) {

            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return false;
        }
    }

    /**
     * Obtener todos los pedidos de un usuario.
     */
    public function obtenerPedidosPorUsuario($usuario_id)
    {
        $sql = "
            SELECT
                p.id,
                p.total,
                p.estado,
                p.fecha_pedido
            FROM pedidos p
            WHERE p.usuario_id = :usuario_id
            ORDER BY p.fecha_pedido DESC, p.id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':usuario_id' => $usuario_id
        ]);

        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Obtener un pedido específico,
     * comprobando que pertenezca al usuario.
     */
    public function obtenerPedidoUsuario(
        $pedido_id,
        $usuario_id
    ) {
        $sql = "
            SELECT
                p.id,
                p.usuario_id,
                p.total,
                p.estado,
                p.fecha_pedido,
                u.nombre AS cliente,
                u.email
            FROM pedidos p
            INNER JOIN usuarios u
                ON u.id = p.usuario_id
            WHERE p.id = :pedido_id
              AND p.usuario_id = :usuario_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':pedido_id' => $pedido_id,
            ':usuario_id' => $usuario_id
        ]);

        return $stmt->fetch(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Obtener los productos que pertenecen
     * a un pedido.
     */
    public function obtenerDetallesPedido($pedido_id)
    {
        $sql = "
            SELECT
                dp.id,
                dp.producto_id,
                dp.cantidad,
                dp.precio_unitario,
                pr.nombre AS producto
            FROM detalle_pedidos dp
            INNER JOIN productos pr
                ON pr.id = dp.producto_id
            WHERE dp.pedido_id = :pedido_id
            ORDER BY dp.id ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':pedido_id' => $pedido_id
        ]);

        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Obtener todos los pedidos para administración.
     *
     * Incluye los datos básicos del cliente.
     */
    public function obtenerTodosLosPedidos()
    {
        $sql = "
            SELECT
                p.id,
                p.usuario_id,
                p.total,
                p.estado,
                p.fecha_pedido,
                u.nombre AS cliente,
                u.email
            FROM pedidos p
            INNER JOIN usuarios u
                ON u.id = p.usuario_id
            ORDER BY p.fecha_pedido DESC, p.id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Obtener un pedido para administración.
     *
     * No limita el pedido a un usuario específico.
     */
    public function obtenerPedidoAdmin($pedido_id)
    {
        $sql = "
            SELECT
                p.id,
                p.usuario_id,
                p.total,
                p.estado,
                p.fecha_pedido,
                u.nombre AS cliente,
                u.email
            FROM pedidos p
            INNER JOIN usuarios u
                ON u.id = p.usuario_id
            WHERE p.id = :pedido_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':pedido_id' => $pedido_id
        ]);

        return $stmt->fetch(
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Cambiar el estado de un pedido.
     *
     * Estados permitidos actualmente:
     * pendiente, pagado, enviado, cancelado.
     */
    public function cambiarEstado($pedido_id, $estado)
    {
        $estadosPermitidos = [
            'pendiente',
            'pagado',
            'enviado',
            'cancelado'
        ];

        if (!in_array($estado, $estadosPermitidos, true)) {
            return false;
        }

        $sql = "
            UPDATE pedidos
            SET estado = :estado
            WHERE id = :pedido_id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':estado' => $estado,
            ':pedido_id' => $pedido_id
        ]);

        return $stmt->rowCount() > 0;
    }
}