<?php

require_once __DIR__ . '/../config/AuthMiddleware.php';
require_once __DIR__ . '/../models/Pedido.php';

class PedidoController
{
    private $pedidoModel;

    public function __construct()
    {
        AuthMiddleware::requireLogin();

        $this->pedidoModel = new Pedido();
    }

    /**
     * ================================
     * CLIENTE
     * ================================
     */

    /**
     * Mostrar pedidos del usuario
     */
    public function misPedidos()
    {
        $usuario_id = $_SESSION['usuario']['id'];

        $pedidos = $this->pedidoModel
            ->obtenerPedidosPorUsuario($usuario_id);

        $pageTitle = 'Mis Pedidos';

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/pedidos/mis-pedidos.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Mostrar detalle de un pedido del usuario
     */
    public function detalle()
    {
        $usuario_id = $_SESSION['usuario']['id'];

        $pedido_id = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;

        if ($pedido_id <= 0) {
            header('Location: /origins_games/mis-pedidos');
            exit();
        }

        $pedido = $this->pedidoModel
            ->obtenerPedidoUsuario(
                $pedido_id,
                $usuario_id
            );

        if (!$pedido) {
            header('Location: /origins_games/mis-pedidos');
            exit();
        }

        $detalles = $this->pedidoModel
            ->obtenerDetallesPedido($pedido_id);

        $pageTitle = 'Detalle del Pedido #' . $pedido_id;

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/pedidos/detalle.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * ================================
     * ADMINISTRACIÓN
     * ================================
     */

    /**
     * Mostrar todos los pedidos.
     */
    public function admin()
    {
        AuthMiddleware::requireAdmin();

        $pedidos = $this->pedidoModel
            ->obtenerTodosLosPedidos();

        $pageTitle = 'Gestión de Pedidos';

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/pedidos/admin.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Ver detalle de cualquier pedido
     * desde administración.
     */
    public function ver()
    {
        AuthMiddleware::requireAdmin();

        $pedido_id = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;

        if ($pedido_id <= 0) {
            header('Location: /origins_games/pedidos');
            exit();
        }

        $pedido = $this->pedidoModel
            ->obtenerPedidoAdmin($pedido_id);

        if (!$pedido) {
            header('Location: /origins_games/pedidos');
            exit();
        }

        $detalles = $this->pedidoModel
            ->obtenerDetallesPedido($pedido_id);

        $pageTitle = 'Pedido #' . $pedido_id;

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/pedidos/ver.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Cambiar estado de un pedido.
     */
    public function cambiarEstado()
    {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /origins_games/pedidos');
            exit();
        }

        $pedido_id = isset($_POST['pedido_id'])
            ? (int) $_POST['pedido_id']
            : 0;

        $estado = isset($_POST['estado'])
            ? trim($_POST['estado'])
            : '';

        if ($pedido_id <= 0 || $estado === '') {
            header('Location: /origins_games/pedidos');
            exit();
        }

        $resultado = $this->pedidoModel
            ->cambiarEstado(
                $pedido_id,
                $estado
            );

        if ($resultado) {
            $_SESSION['mensaje'] =
                'El estado del pedido #' .
                $pedido_id .
                ' fue actualizado correctamente.';
        } else {
            $_SESSION['mensaje'] =
                'No fue posible actualizar el estado del pedido.';
        }

        header(
            'Location: /origins_games/pedido/ver?id=' .
            $pedido_id
        );

        exit();
    }
}