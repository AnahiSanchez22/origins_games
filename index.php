<?php
// 1. Asegurar que la sesión esté iniciada para todo el proyecto
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Obtener la URL solicitada y limpiarla quitando la carpeta base
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = str_replace('/origins_games', '', $url);

// 3. Incluir el controlador del carrito por defecto (o puedes requerirlos bajo demanda)
require_once __DIR__ . '/controllers/CarritoController.php';

// 4. Estructura SWITCH unificada y ordenada
switch ($url) {
    case '':
    case '/':
    case '/home':
        require_once 'controllers/HomeController.php';
        (new HomeController())->index();
        break;

    case '/auth/login':
        require_once 'controllers/AuthController.php';
        (new AuthController())->login();
        break;

    case '/auth/register':
        require_once 'controllers/AuthController.php';
        (new AuthController())->register();
        break;

    case '/auth/logout':
        require_once 'controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    // --- Rutas de Productos ---
    case '/producto':
        require_once 'controllers/ProductoController.php';
        (new ProductoController())->index();
        break;

    case '/producto/admin':
        require_once 'controllers/ProductoController.php';
        (new ProductoController())->admin();
        break;

    case '/producto/guardar':
        require_once 'controllers/ProductoController.php';
        (new ProductoController())->guardar();
        break;

    case '/producto/eliminar':
        require_once 'controllers/ProductoController.php';
        (new ProductoController())->eliminar();
        break;

    // --- Rutas de Citas / Soporte Técnico ---
    case '/cita':
        require_once 'controllers/CitaController.php';
        (new CitaController())->index();
        break;

    case '/cita/guardar':
        require_once 'controllers/CitaController.php';
        (new CitaController())->guardar();
        break;

    case '/cita/admin':
        require_once 'controllers/CitaController.php';
        (new CitaController())->admin();
        break;

    case '/cita/cambiarEstado':
        require_once 'controllers/CitaController.php';
        (new CitaController())->cambiarEstado();
        break;

    case '/cita/cancelar':
        require_once 'controllers/CitaController.php';
        (new CitaController())->cancelar();
        break;

    // --- Rutas del Carrito ---
    case '/carrito':
    case '/carrito/index':
        (new CarritoController())->index();
        break;

    case '/carrito/agregar':
        (new CarritoController())->agregar();
        break;

    case '/carrito/actualizar':
        (new CarritoController())->actualizar();
        break;

    case '/carrito/eliminar':
        (new CarritoController())->eliminar();
        break;

    case '/carrito/vaciar':
        (new CarritoController())->vaciar();
        break;

    case '/carrito/pagar':
        (new CarritoController())->pagar();
        break;

    // --- Default (Error 404) SIEMPRE al final ---
    default:
        http_response_code(404);
        echo "<div style='background: #030712; color: #fff; font-family: sans-serif; text-align: center; padding: 50px;'>";
        echo "<h1 style='color: #facc15;'>Error 404</h1>";
        echo "<p style='color: #94a3b8;'>La ruta <b>" . htmlspecialchars($url) . "</b> no existe en Origins Games.</p>";
        echo "<br><a href='/origins_games/home' style='background: #facc15; color: #000; padding: 10px 20px; border-radius: 20px; text-decoration: none; font-weight: bold;'>Volver al Inicio</a>";
        echo "</div>";
        break;
}