<?php

require_once __DIR__ . '/../config/Database.php';

class CarritoController {

    public function index() {
        $pageTitle = 'Carrito de Compras - Origins Games';
        require_once __DIR__ . '/../views/carrito/index.php';
    }

    public function agregar() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        $producto_id = isset($_POST['producto_id']) ? (int)$_POST['producto_id'] : 0;
        
        if ($producto_id > 0) {
            $db = Database::connect();            

            $stmt = $db->prepare("SELECT id, nombre, precio FROM productos WHERE id = ?");
            $stmt->execute([$producto_id]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($producto) {
                if (!isset($_SESSION['carrito'])) {
                    $_SESSION['carrito'] = [];
                }

                if (isset($_SESSION['carrito'][$producto_id])) {
                    $_SESSION['carrito'][$producto_id]['cantidad'] += 1;
                } else {
                    $_SESSION['carrito'][$producto_id] = [
                        'nombre' => $producto['nombre'],
                        'precio' => $producto['precio'],
                        'cantidad' => 1
                    ];
                }
            }
        }
        
        // REDIRECCIÓN INTELIGENTE: Devuelve al usuario exactamente a la misma página del catálogo donde estaba
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/origins_games/producto';
        header("Location: " . $redirectUrl);
        exit();
    } // <--- Llave de cierre de agregar()

    public function actualizar() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $producto_id = isset($_POST['producto_id']) ? (int)$_POST['producto_id'] : 0;
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;

        if ($cantidad > 0 && isset($_SESSION['carrito'][$producto_id])) {
            $_SESSION['carrito'][$producto_id]['cantidad'] = $cantidad;
        } else {
            unset($_SESSION['carrito'][$producto_id]);
        }
        header("Location: /origins_games/carrito");
        exit();
    } // <--- Llave de cierre de actualizar()

    public function eliminar() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $producto_id = isset($_POST['producto_id']) ? (int)$_POST['producto_id'] : 0;
        if (isset($_SESSION['carrito'][$producto_id])) {
            unset($_SESSION['carrito'][$producto_id]);
        }
        header("Location: /origins_games/carrito");
        exit();
    } // <--- Llave de cierre de eliminar()

    public function vaciar() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $_SESSION['carrito'] = [];
        header("Location: /origins_games/carrito");
        exit();
    } // <--- Llave de cierre de vaciar()

    public function pagar() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        if (empty($_SESSION['carrito'])) {
            header("Location: /origins_games/carrito");
            exit();
        }

        $usuario_id = $_SESSION['usuario']['id'] ?? 1;
        // Corregido a Database::connect() para evitar futuros errores en el pago
        $db = Database::connect();

        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $total += ($item['precio'] * $item['cantidad']);
        }

        $stmt = $db->prepare("INSERT INTO pedidos (usuario_id, total, estado) VALUES (?, ?, 'pagado')");
        if ($stmt->execute([$usuario_id, $total])) {
            $pedido_id = $db->lastInsertId();

            $stmtDetalle = $db->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
            foreach ($_SESSION['carrito'] as $prod_id => $item) {
                $stmtDetalle->execute([$pedido_id, $prod_id, $item['cantidad'], $item['precio']]);
            }

            $_SESSION['carrito'] = [];
            echo "<script>
                    alert('¡Compra registrada con éxito! Número de pedido: #" . $pedido_id . "');
                    window.location.href = '/origins_games/producto';
                  </script>";
        }
        exit();
    } // <--- Llave de cierre de pagar()

} // <--- Llave de cierre de la clase CarritoController