<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = str_replace('/origins_games', '', $url);

require_once __DIR__ . '/controllers/CarritoController.php';

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

    // --- Rutas de Perfil de Usuario (Cliente / Admin) ---
    case '/perfil':
        require_once 'controllers/PerfilController.php';
        (new PerfilController())->index();
        break;

    case '/perfil/editar':
        require_once 'controllers/PerfilController.php';
        (new PerfilController())->editar();
        break;

    // --- Rutas de Gestión de Usuarios (Admin) ---
    case '/usuario':
    case '/usuario/index':
        require_once 'controllers/UsuarioController.php';
        (new UsuarioController())->index();
        break;

    case '/usuario/create':
        require_once 'controllers/UsuarioController.php';
        (new UsuarioController())->create();
        break;

    case '/usuario/edit':
        require_once 'controllers/UsuarioController.php';
        $id = $_GET['id'] ?? null;
        (new UsuarioController())->edit($id);
        break;

    case '/usuario/delete':
        require_once 'controllers/UsuarioController.php';
        $id = $_GET['id'] ?? null;
        (new UsuarioController())->delete($id);
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

        // --- Rutas de Mis Pedidos ---
case '/mis-pedidos':
    require_once 'controllers/PedidoController.php';
    (new PedidoController())->misPedidos();
    break;

case '/mis-pedidos/detalle':
    require_once 'controllers/PedidoController.php';
    (new PedidoController())->detalle();
    break;

            // --- Dashboard de Administrador ---
    case '/dashboard':
        require_once 'controllers/DashboardController.php';
        (new DashboardController())->index();
        break;

    // --- Gestión de Pedidos ---
    case '/pedido/admin':
        require_once 'controllers/PedidoController.php';
        (new PedidoController())->admin();
        break;
    // --- Dashboard ---
    case '/dashboard':
        require_once 'controllers/DashboardController.php';
        (new DashboardController())->index();
        break;

    // --- Gestión de Pedidos ---
    case '/pedido/admin':
        require_once 'controllers/PedidoController.php';
        (new PedidoController())->admin();
        break;

    case '/pedido/ver':
        require_once 'controllers/PedidoController.php';
        (new PedidoController())->ver();
        break;

    case '/pedido/cambiarEstado':
        require_once 'controllers/PedidoController.php';
        (new PedidoController())->cambiarEstado();
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