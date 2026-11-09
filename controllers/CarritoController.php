
<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Pedido.php';

class CarritoController
{
    /**
     * Iniciar sesión si todavía no está iniciada.
     */
    private function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Comprobar que la solicitud sea POST.
     */
    private function verificarPost($destino = '/origins_games/carrito')
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $destino);
            exit();
        }
    }


    /**
     * Mostrar carrito y sincronizar productos con la BD.
     */
    public function index()
    {
        $this->iniciarSesion();

        if (!empty($_SESSION['carrito'])) {

            $db = Database::connect();

            foreach ($_SESSION['carrito'] as $producto_id => &$item) {

                $stmt = $db->prepare(
                    "SELECT id, nombre, precio, stock
                     FROM productos
                     WHERE id = ?"
                );

                $stmt->execute([(int) $producto_id]);

                $producto = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$producto) {
                    unset($_SESSION['carrito'][$producto_id]);
                    continue;
                }

                $stock = (int) $producto['stock'];

                $item['nombre'] = $producto['nombre'];
                $item['precio'] = $producto['precio'];

                if ($stock <= 0) {
                    unset($_SESSION['carrito'][$producto_id]);
                    continue;
                }

                if ((int) $item['cantidad'] > $stock) {
                    $item['cantidad'] = $stock;
                }
            }

            unset($item);
        }

        $pageTitle = 'Carrito de Compras - Origins Games';

        require_once __DIR__ . '/../views/carrito/index.php';
    }


    /**
     * Agregar producto al carrito.
     */
    public function agregar()
    {
        $this->iniciarSesion();
        $this->verificarPost('/origins_games/producto');

        $producto_id = isset($_POST['producto_id'])
            ? (int) $_POST['producto_id']
            : 0;

        if ($producto_id <= 0) {
            header('Location: /origins_games/producto');
            exit();
        }

        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT id, nombre, precio, stock
             FROM productos
             WHERE id = ?"
        );

        $stmt->execute([$producto_id]);

        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            $_SESSION['mensaje_carrito'] = 'El producto no existe.';
            header('Location: /origins_games/producto');
            exit();
        }

        $stock = (int) $producto['stock'];

        if ($stock <= 0) {
            $_SESSION['mensaje_carrito'] = 'Este producto está agotado.';
            header('Location: /origins_games/producto');
            exit();
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $cantidadActual = isset($_SESSION['carrito'][$producto_id])
            ? (int) $_SESSION['carrito'][$producto_id]['cantidad']
            : 0;

        if (($cantidadActual + 1) > $stock) {

            $_SESSION['mensaje_carrito'] =
                'No puedes agregar más unidades. Stock disponible: ' . $stock . '.';

            $redirectUrl = $_SERVER['HTTP_REFERER']
                ?? '/origins_games/producto';

            header('Location: ' . $redirectUrl);
            exit();
        }

        if (isset($_SESSION['carrito'][$producto_id])) {

            $_SESSION['carrito'][$producto_id]['cantidad']++;

            $_SESSION['carrito'][$producto_id]['nombre'] =
                $producto['nombre'];

            $_SESSION['carrito'][$producto_id]['precio'] =
                $producto['precio'];

        } else {

            $_SESSION['carrito'][$producto_id] = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => 1
            ];
        }

        $redirectUrl = $_SERVER['HTTP_REFERER']
            ?? '/origins_games/producto';

        header('Location: ' . $redirectUrl);
        exit();
    }


    /**
     * Actualizar cantidad de un producto.
     */
    public function actualizar()
    {
        $this->iniciarSesion();
        $this->verificarPost();

        $producto_id = isset($_POST['producto_id'])
            ? (int) $_POST['producto_id']
            : 0;

        $cantidad = filter_var(
            $_POST['cantidad'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (
            $producto_id <= 0 ||
            $cantidad === false ||
            !isset($_SESSION['carrito'][$producto_id])
        ) {
            header('Location: /origins_games/carrito');
            exit();
        }

        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT stock
             FROM productos
             WHERE id = ?"
        );

        $stmt->execute([$producto_id]);

        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            unset($_SESSION['carrito'][$producto_id]);

            header('Location: /origins_games/carrito');
            exit();
        }

        $stock = (int) $producto['stock'];

        if ($cantidad <= 0 || $stock <= 0) {

            unset($_SESSION['carrito'][$producto_id]);

        } elseif ($cantidad > $stock) {

            $_SESSION['carrito'][$producto_id]['cantidad'] = $stock;

            $_SESSION['mensaje_carrito'] =
                'Solo hay ' . $stock . ' unidades disponibles.';

        } else {

            $_SESSION['carrito'][$producto_id]['cantidad'] = $cantidad;
        }

        header('Location: /origins_games/carrito');
        exit();
    }


    /**
     * Eliminar un producto del carrito.
     */
    public function eliminar()
    {
        $this->iniciarSesion();
        $this->verificarPost();

        $producto_id = isset($_POST['producto_id'])
            ? (int) $_POST['producto_id']
            : 0;

        if ($producto_id > 0 && isset($_SESSION['carrito'][$producto_id])) {
            unset($_SESSION['carrito'][$producto_id]);
        }

        header('Location: /origins_games/carrito');
        exit();
    }


    /**
     * Vaciar el carrito.
     */
    public function vaciar()
    {
        $this->iniciarSesion();
        $this->verificarPost();

        $_SESSION['carrito'] = [];

        header('Location: /origins_games/carrito');
        exit();
    }


    /**
     * Procesar el pago y registrar el pedido.
     */
    public function pagar()
    {
        $this->iniciarSesion();
        $this->verificarPost();

        if (empty($_SESSION['carrito'])) {
            header('Location: /origins_games/carrito');
            exit();
        }

        if (empty($_SESSION['usuario']['id'])) {

            $_SESSION['mensaje_carrito'] =
                'Debes iniciar sesión para realizar la compra.';

            header('Location: /origins_games/auth/login');
            exit();
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        if ($usuario_id <= 0) {
            header('Location: /origins_games/auth/login');
            exit();
        }

        /*
         * Construir los productos de la compra.
         * El modelo consulta los precios reales en la BD.
         */
        $items = [];

        foreach ($_SESSION['carrito'] as $producto_id => $item) {

            $cantidad = filter_var(
                $item['cantidad'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (
                (int) $producto_id <= 0 ||
                $cantidad === false ||
                $cantidad <= 0
            ) {
                $_SESSION['mensaje_carrito'] =
                    'Hay un producto con datos inválidos en el carrito.';

                header('Location: /origins_games/carrito');
                exit();
            }

            $items[] = [
                'id' => (int) $producto_id,
                'cantidad' => $cantidad
            ];
        }

        /*
         * Crear pedido y descontar inventario.
         */
        $pedidoModel = new Pedido();

        $pedido_id = $pedidoModel->crearPedidoTransaccion(
            $usuario_id,
            $items,
            0
        );

        /*
         * Si la compra falla.
         */
        if (!$pedido_id) {

            $this->sincronizarCarrito();

            $_SESSION['mensaje_carrito'] =
                'No se pudo realizar la compra. Verifica que los productos tengan stock disponible e inténtalo nuevamente.';

            header('Location: /origins_games/carrito');
            exit();
        }

        /*
         * Compra realizada correctamente.
         */
        $_SESSION['carrito'] = [];

        /*
         * Mostrar ventana emergente de confirmación.
         */
        echo "<script>
                alert('¡Compra registrada con éxito! Número de pedido: #" .
                (int) $pedido_id .
                "');
                window.location.href = '/origins_games/producto';
              </script>";

        exit();
    }


    /**
     * Sincronizar carrito con el inventario actual.
     */
    private function sincronizarCarrito()
    {
        if (empty($_SESSION['carrito'])) {
            return;
        }

        $db = Database::connect();

        foreach ($_SESSION['carrito'] as $producto_id => &$item) {

            $stmt = $db->prepare(
                "SELECT nombre, precio, stock
                 FROM productos
                 WHERE id = ?"
            );

            $stmt->execute([(int) $producto_id]);

            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {
                unset($_SESSION['carrito'][$producto_id]);
                continue;
            }

            $stock = (int) $producto['stock'];

            $item['nombre'] = $producto['nombre'];
            $item['precio'] = $producto['precio'];

            if ($stock <= 0) {

                unset($_SESSION['carrito'][$producto_id]);

            } elseif ((int) $item['cantidad'] > $stock) {

                $item['cantidad'] = $stock;
            }
        }

        unset($item);
    }
}
