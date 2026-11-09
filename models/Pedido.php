
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
     * Los precios y el total se calculan desde la base de datos.
     */
    public function crearPedidoTransaccion(
        $usuario_id,
        $items,
        $total
    ) {
        try {

            $usuario_id = (int) $usuario_id;

            if ($usuario_id <= 0) {
                throw new Exception('Usuario inválido.');
            }

            if (!is_array($items) || empty($items)) {
                throw new Exception('El carrito está vacío.');
            }

            $this->db->beginTransaction();

            /*
             * Consultar productos y bloquear sus registros
             * mientras se procesa la compra.
             */
            $stmtProducto = $this->db->prepare(
                "SELECT
                    id,
                    nombre,
                    precio,
                    stock
                 FROM productos
                 WHERE id = ?
                 FOR UPDATE"
            );

            $productosValidados = [];
            $productosProcesados = [];
            $totalCentavos = 0;

            /*
             * Verificar productos, cantidades y precios.
             */
            foreach ($items as $item) {

                $producto_id = (int) ($item['id'] ?? 0);
                $cantidad = filter_var(
                    $item['cantidad'] ?? null,
                    FILTER_VALIDATE_INT
                );

                if ($producto_id <= 0 || $cantidad === false || $cantidad <= 0) {
                    throw new Exception('Producto o cantidad inválida.');
                }

                if (isset($productosProcesados[$producto_id])) {
                    throw new Exception('Hay productos duplicados en el carrito.');
                }

                $productosProcesados[$producto_id] = true;

                $stmtProducto->execute([$producto_id]);

                $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

                if (!$producto) {
                    throw new Exception('El producto no existe.');
                }

                $stock = (int) $producto['stock'];

                if ($stock < $cantidad) {
                    throw new Exception(
                        'Stock insuficiente para ' . $producto['nombre']
                    );
                }

                /*
                 * Convertir el precio de la base de datos
                 * a centavos para calcular el total.
                 */
                $precio = (float) $producto['precio'];

                if (!is_finite($precio) || $precio <= 0) {
                    throw new Exception('El precio del producto no es válido.');
                }

                $precioCentavos = (int) round($precio * 100);

                $totalCentavos += $precioCentavos * $cantidad;

                $productosValidados[] = [
                    'id' => $producto_id,
                    'cantidad' => $cantidad,
                    'precio' => number_format(
                        $precioCentavos / 100,
                        2,
                        '.',
                        ''
                    )
                ];
            }

            /*
             * Verificar que el total no supere
             * la capacidad de DECIMAL(10,2).
             */
            if ($totalCentavos <= 0 || $totalCentavos > 9999999999) {
                throw new Exception('El total del pedido no es válido.');
            }

            $totalCalculado = number_format(
                $totalCentavos / 100,
                2,
                '.',
                ''
            );

            /*
             * Crear pedido utilizando el total calculado
             * con los precios reales de la base de datos.
             */
            $stmt = $this->db->prepare(
                "INSERT INTO pedidos
                (
                    usuario_id,
                    total,
                    estado
                )
                VALUES (?, ?, 'pagado')"
            );

            $stmt->execute([
                $usuario_id,
                $totalCalculado
            ]);

            $pedido_id = (int) $this->db->lastInsertId();

            if ($pedido_id <= 0) {
                throw new Exception('No fue posible crear el pedido.');
            }

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
             * Guardar cada producto con su precio real
             * y descontar el inventario.
             */
            foreach ($productosValidados as $producto) {

                $stmtDetalle->execute([
                    $pedido_id,
                    $producto['id'],
                    $producto['cantidad'],
                    $producto['precio']
                ]);

                $stmtStock->execute([
                    $producto['cantidad'],
                    $producto['id'],
                    $producto['cantidad']
                ]);

                if ($stmtStock->rowCount() !== 1) {
                    throw new Exception(
                        'No fue posible actualizar el stock.'
                    );
                }
            }

            /*
             * Confirmar toda la operación.
             */
            $this->db->commit();

            return $pedido_id;

        } catch (Exception $e) {

            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            error_log(
                'Error al crear pedido: ' . $e->getMessage()
            );

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

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        return $stmt->fetch(PDO::FETCH_ASSOC);
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
                pr.nombre AS producto,
                pr.imagen AS imagen
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

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Obtener todos los pedidos para administración.
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

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Obtener un pedido para administración.
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
                u.email,
                u.telefono,
                u.direccion
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

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Cambiar estado de un pedido.
     */
    public function cambiarEstado(
        $pedido_id,
        $estado
    ) {
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

        return $stmt->execute([
            ':estado' => $estado,
            ':pedido_id' => $pedido_id
        ]);
    }
}
