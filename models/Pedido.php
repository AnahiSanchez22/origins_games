<?php
require_once __DIR__ . '/../config/database.php';

class Pedido {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function crearPedidoTransaccion($usuario_id, $items, $total) {
        try {
            $this->db->beginTransaction();

            // 1. Crear el encabezado del pedido
            $stmt = $this->db->prepare("INSERT INTO pedidos (usuario_id, total, estado) VALUES (:usuario_id, :total, 'pagado')");
            $stmt->execute([':usuario_id' => $usuario_id, ':total' => $total]);
            $pedido_id = $this->db->lastInsertId();

            // 2. Insertar detalles y descontar stock
            $stmtDetalle = $this->db->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario) VALUES (:pedido_id, :producto_id, :cantidad, :precio)");
            $stmtStock = $this->db->prepare("UPDATE productos SET stock = stock - :cant WHERE id = :id AND stock >= :cant");

            foreach ($items as $item) {
                // Insertar detalle
                $stmtDetalle->execute([
                    ':pedido_id'   => $pedido_id,
                    ':producto_id' => $item['id'],
                    ':cantidad'    => $item['cantidad'],
                    ':precio'      => $item['precio']
                ]);

                // Descontar inventario
                $stmtStock->execute([
                    ':cant' => $item['cantidad'],
                    ':id'   => $item['id']
                ]);
            }

            $this->db->commit();
            return $pedido_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}