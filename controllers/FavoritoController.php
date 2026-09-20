<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/AuthMiddleware.php';
require_once __DIR__ . '/../models/Favorito.php';

class FavoritoController
{
    private $favoritoModel;

    public function __construct()
    {
        AuthMiddleware::requireLogin();

        $this->favoritoModel = new Favorito();
    }

    /**
     * Mostrar los favoritos del usuario.
     */
    public function index()
    {
        $usuario_id = (int) $_SESSION['usuario']['id'];

        $favoritos = $this->favoritoModel->obtenerPorUsuario($usuario_id);

        $pageTitle = 'Mis Favoritos - Origins Games';

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/usuarios/favoritos.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Agregar producto a favoritos.
     */
    public function agregar()
    {
        $usuario_id = (int) $_SESSION['usuario']['id'];

        $producto_id = isset($_POST['producto_id'])
            ? (int) $_POST['producto_id']
            : 0;

        if ($producto_id <= 0) {
            header('Location: /origins_games/producto');
            exit();
        }

        $resultado = $this->favoritoModel->agregar(
            $usuario_id,
            $producto_id
        );

        $referer = $_SERVER['HTTP_REFERER']
            ?? '/origins_games/producto';

        header('Location: ' . $referer);
        exit();
    }

    /**
     * Eliminar producto de favoritos.
     */
    public function eliminar()
    {
        $usuario_id = (int) $_SESSION['usuario']['id'];

        $producto_id = isset($_POST['producto_id'])
            ? (int) $_POST['producto_id']
            : 0;

        if ($producto_id <= 0) {
            header('Location: /origins_games/favoritos');
            exit();
        }

        $this->favoritoModel->eliminar(
            $usuario_id,
            $producto_id
        );

        $referer = $_SERVER['HTTP_REFERER']
            ?? '/origins_games/favoritos';

        header('Location: ' . $referer);
        exit();
    }
}