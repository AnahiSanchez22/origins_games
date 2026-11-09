
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

        $favoritos = $this->favoritoModel->obtenerPorUsuario(
            $usuario_id
        );

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
        $this->verificarPost();

        $usuario_id = (int) $_SESSION['usuario']['id'];
        $producto_id = $this->validarId($_POST['producto_id'] ?? null);

        if ($producto_id === false) {
            $this->redireccionar('/origins_games/producto');
        }

        $this->favoritoModel->agregar(
            $usuario_id,
            $producto_id
        );

        $this->redireccionar(
            $this->obtenerRutaRetorno('/origins_games/producto')
        );
    }

    /**
     * Eliminar producto de favoritos.
     */
    public function eliminar()
    {
        $this->verificarPost();

        $usuario_id = (int) $_SESSION['usuario']['id'];
        $producto_id = $this->validarId($_POST['producto_id'] ?? null);

        if ($producto_id === false) {
            $this->redireccionar('/origins_games/favoritos');
        }

        $this->favoritoModel->eliminar(
            $usuario_id,
            $producto_id
        );

        $this->redireccionar(
            $this->obtenerRutaRetorno('/origins_games/favoritos')
        );
    }

    /**
     * Comprobar que la solicitud sea POST.
     */
    private function verificarPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redireccionar('/origins_games/favoritos');
        }
    }

    /**
     * Validar identificadores.
     */
    private function validarId($id)
    {
        if (
            !is_scalar($id)
            || filter_var($id, FILTER_VALIDATE_INT) === false
        ) {
            return false;
        }

        $id = (int) $id;

        return $id > 0 ? $id : false;
    }

    /**
     * Obtener una ruta de retorno segura dentro de la aplicación.
     */
    private function obtenerRutaRetorno($rutaPredeterminada)
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';

        if ($referer === '') {
            return $rutaPredeterminada;
        }

        $ruta = parse_url($referer);

        if (
            $ruta === false
            || !isset($ruta['host'])
            || !isset($_SERVER['HTTP_HOST'])
            || strcasecmp($ruta['host'], $_SERVER['HTTP_HOST']) !== 0
        ) {
            return $rutaPredeterminada;
        }

        $path = $ruta['path'] ?? '';

        if (
            strpos($path, '/origins_games/') !== 0
            || preg_match('/[\r\n]/', $path)
        ) {
            return $rutaPredeterminada;
        }

        return $path;
    }

    /**
     * Redireccionar y finalizar la ejecución.
     */
    private function redireccionar($ruta)
    {
        header('Location: ' . $ruta);
        exit();
    }
}
