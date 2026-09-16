<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Pedido.php';

class CarritoController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        /*
         * Revisar el stock actual de los productos
         * que ya están en el carrito.
         */
        if (!empty($_SESSION['carrito'])) {

            $db = Database::connect();

            foreach ($_SESSION['carrito'] as $producto_id => &$item) {

                $stmt = $db->prepare(
                    "SELECT id, nombre, precio, stock
                     FROM productos
                     WHERE id = ?"
                );

                $stmt->execute(array((int)$producto_id));

                $producto = $stmt->fetch(PDO::FETCH_ASSOC);

                /*
                 * Si el producto ya no existe,
                 * lo quitamos del carrito.
                 */
                if (!$producto) {
                    unset($_SESSION['carrito'][$producto_id]);
                    continue;
                }

                $stock = (int)$producto['stock'];

                /*
                 * Actualizamos nombre y precio.
                 */
                $item['nombre'] = $producto['nombre'];
                $item['precio'] = $producto['precio'];

                /*
                 * Si quedó sin stock, quitamos
                 * el producto del carrito.
                 */
                if ($stock <= 0) {
                    unset($_SESSION['carrito'][$producto_id]);
                    continue;
                }

                /*
                 * Si la cantidad del carrito supera
                 * el stock actual, la ajustamos.
                 */
                if ((int)$item['cantidad'] > $stock) {
                    $item['cantidad'] = $stock;
                }
            }

            unset($item);
        }

        $pageTitle = 'Carrito de Compras - Origins Games';

        require_once __DIR__ . '/../views/carrito/index.php';
    }


    public function agregar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $producto_id = isset($_POST['producto_id'])
            ? (int)$_POST['producto_id']
            : 0;

        if ($producto_id <= 0) {
            header("Location: /origins_games/producto");
            exit();
        }

        $db = Database::connect();

        /*
         * Consultamos también el stock.
         */
        $stmt = $db->prepare(
            "SELECT id, nombre, precio, stock
             FROM productos
             WHERE id = ?"
        );

        $stmt->execute(array($producto_id));

        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {

            echo "<script>
                    alert('El producto no existe.');
                    window.location.href='/origins_games/producto';
                  </script>";
            exit();
        }

        $stock = (int)$producto['stock'];

        /*
         * No permitir agregar productos agotados.
         */
        if ($stock <= 0) {

            echo "<script>
                    alert('Este producto está agotado.');
                    window.location.href='/origins_games/producto';
                  </script>";
            exit();
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = array();
        }

        /*
         * Cantidad que ya existe en el carrito.
         */
        $cantidadActual = 0;

        if (isset($_SESSION['carrito'][$producto_id])) {

            $cantidadActual =
                (int)$_SESSION['carrito'][$producto_id]['cantidad'];
        }

        /*
         * No superar el stock.
         */
        if (($cantidadActual + 1) > $stock) {

            echo "<script>
                    alert('No puedes agregar más unidades. Stock disponible: " .
                    $stock .
                    "');
                    window.location.href=document.referrer || '/origins_games/producto';
                  </script>";
            exit();
        }

        if (isset($_SESSION['carrito'][$producto_id])) {

            $_SESSION['carrito'][$producto_id]['cantidad']++;

            $_SESSION['carrito'][$producto_id]['nombre'] =
                $producto['nombre'];

            $_SESSION['carrito'][$producto_id]['precio'] =
                $producto['precio'];

        } else {

            $_SESSION['carrito'][$producto_id] = array(
                'nombre'   => $producto['nombre'],
                'precio'   => $producto['precio'],
                'cantidad' => 1
            );
        }

        $redirectUrl =
            isset($_SERVER['HTTP_REFERER'])
            ? $_SERVER['HTTP_REFERER']
            : '/origins_games/producto';

        header("Location: " . $redirectUrl);
        exit();
    }


    public function actualizar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $producto_id = isset($_POST['producto_id'])
            ? (int)$_POST['producto_id']
            : 0;

        $cantidad = isset($_POST['cantidad'])
            ? (int)$_POST['cantidad']
            : 0;

        if (
            $producto_id <= 0 ||
            !isset($_SESSION['carrito'][$producto_id])
        ) {

            header("Location: /origins_games/carrito");
            exit();
        }

        $db = Database::connect();

        /*
         * Consultar stock actual.
         */
        $stmt = $db->prepare(
            "SELECT stock
             FROM productos
             WHERE id = ?"
        );

        $stmt->execute(array($producto_id));

        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {

            unset($_SESSION['carrito'][$producto_id]);

            header("Location: /origins_games/carrito");
            exit();
        }

        $stock = (int)$producto['stock'];

        /*
         * Cantidad 0 = eliminar.
         */
        if ($cantidad <= 0) {

            unset($_SESSION['carrito'][$producto_id]);

        } elseif ($stock <= 0) {

            unset($_SESSION['carrito'][$producto_id]);

        } elseif ($cantidad > $stock) {

            $_SESSION['carrito'][$producto_id]['cantidad'] =
                $stock;

            $_SESSION['mensaje_carrito'] =
                'Solo hay ' . $stock .
                ' unidades disponibles.';

        } else {

            $_SESSION['carrito'][$producto_id]['cantidad'] =
                $cantidad;
        }

        header("Location: /origins_games/carrito");
        exit();
    }


    public function eliminar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $producto_id = isset($_POST['producto_id'])
            ? (int)$_POST['producto_id']
            : 0;

        if (isset($_SESSION['carrito'][$producto_id])) {

            unset($_SESSION['carrito'][$producto_id]);
        }

        header("Location: /origins_games/carrito");
        exit();
    }


    public function vaciar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['carrito'] = array();

        header("Location: /origins_games/carrito");
        exit();
    }


    public function pagar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        /*
         * Carrito vacío.
         */
        if (empty($_SESSION['carrito'])) {

            header("Location: /origins_games/carrito");
            exit();
        }

        /*
         * Verificar usuario.
         */
        if (empty($_SESSION['usuario']['id'])) {

            echo "<script>
                    alert('Debes iniciar sesión para realizar la compra.');
                    window.location.href='/origins_games/auth/login';
                  </script>";
            exit();
        }

        $usuario_id =
            (int)$_SESSION['usuario']['id'];


        /*
         * Construir los productos de la compra.
         */
        $items = array();

        foreach ($_SESSION['carrito'] as $producto_id => $item) {

            $items[] = array(
                'id'       => (int)$producto_id,
                'nombre'   => $item['nombre'],
                'precio'   => (float)$item['precio'],
                'cantidad' => (int)$item['cantidad']
            );
        }


        /*
         * Calcular total.
         */
        $total = 0;

        foreach ($items as $item) {

            $total +=
                $item['precio'] *
                $item['cantidad'];
        }


        /*
         * Crear pedido y descontar stock.
         */
        $pedidoModel = new Pedido();

        $pedido_id =
            $pedidoModel->crearPedidoTransaccion(
                $usuario_id,
                $items,
                $total
            );


        /*
         * Si falló por stock u otro problema.
         */
        if (!$pedido_id) {

            /*
             * Actualizar el carrito para reflejar
             * el stock actual.
             */
            $this->sincronizarCarrito();

            echo "<script>
                    alert('No se pudo realizar la compra. Verifica que los productos tengan stock disponible.');
                    window.location.href='/origins_games/carrito';
                  </script>";
            exit();
        }


        /*
         * Compra realizada correctamente.
         */
        $_SESSION['carrito'] = array();


        echo "<script>
                alert('¡Compra registrada con éxito! Número de pedido: #" .
                $pedido_id .
                "');
                window.location.href='/origins_games/producto';
              </script>";

        exit();
    }


    /*
     * Sincronizar carrito con inventario.
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

            $stmt->execute(array((int)$producto_id));

            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {

                unset($_SESSION['carrito'][$producto_id]);
                continue;
            }

            $stock = (int)$producto['stock'];

            $item['nombre'] =
                $producto['nombre'];

            $item['precio'] =
                $producto['precio'];

            if ($stock <= 0) {

                unset($_SESSION['carrito'][$producto_id]);

            } elseif ((int)$item['cantidad'] > $stock) {

                $item['cantidad'] = $stock;
            }
        }

        unset($item);
    }
}