
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$url = str_replace(
    '/origins_games',
    '',
    $url
);

require_once __DIR__ . '/controllers/CarritoController.php';


switch ($url) {


    // =========================================================
    // HOME
    // =========================================================

    case '':
    case '/':
    case '/home':

        require_once __DIR__ . '/controllers/HomeController.php';

        (new HomeController())->index();

        break;


    // =========================================================
    // AUTENTICACIÓN
    // =========================================================

    case '/auth/login':

        require_once __DIR__ . '/controllers/AuthController.php';

        (new AuthController())->login();

        break;


    case '/auth/register':

        require_once __DIR__ . '/controllers/AuthController.php';

        (new AuthController())->register();

        break;


    case '/auth/logout':

        require_once __DIR__ . '/controllers/AuthController.php';

        (new AuthController())->logout();

        break;


    // =========================================================
    // PERFIL
    // =========================================================

    case '/perfil':

        require_once __DIR__ . '/controllers/PerfilController.php';

        (new PerfilController())->index();

        break;


    case '/perfil/editar':

        require_once __DIR__ . '/controllers/PerfilController.php';

        (new PerfilController())->editar();

        break;


    // =========================================================
    // FAVORITOS - CLIENTE
    // =========================================================

    case '/favoritos':

        require_once __DIR__ . '/controllers/FavoritoController.php';

        (new FavoritoController())->index();

        break;


    case '/favoritos/agregar':

        require_once __DIR__ . '/controllers/FavoritoController.php';

        (new FavoritoController())->agregar();

        break;


    case '/favoritos/eliminar':

        require_once __DIR__ . '/controllers/FavoritoController.php';

        (new FavoritoController())->eliminar();

        break;


    // =========================================================
    // GESTIÓN DE USUARIOS - ADMIN
    // =========================================================

    case '/usuario':
    case '/usuario/index':

        require_once __DIR__ . '/controllers/UsuarioController.php';

        (new UsuarioController())->index();

        break;


    case '/usuario/create':

        require_once __DIR__ . '/controllers/UsuarioController.php';

        (new UsuarioController())->create();

        break;


    case '/usuario/edit':

        require_once __DIR__ . '/controllers/UsuarioController.php';

        $id = $_GET['id'] ?? null;

        (new UsuarioController())->edit($id);

        break;


    case '/usuario/delete':

        require_once __DIR__ . '/controllers/UsuarioController.php';

        $id = $_GET['id'] ?? null;

        (new UsuarioController())->delete($id);

        break;


    // =========================================================
    // PRODUCTOS
    // =========================================================

    case '/producto':

        require_once __DIR__ . '/controllers/ProductoController.php';

        (new ProductoController())->index();

        break;


    case '/producto/admin':

        require_once __DIR__ . '/controllers/ProductoController.php';

        (new ProductoController())->admin();

        break;


    case '/producto/guardar':

        require_once __DIR__ . '/controllers/ProductoController.php';

        (new ProductoController())->guardar();

        break;


    case '/producto/eliminar':

        require_once __DIR__ . '/controllers/ProductoController.php';

        (new ProductoController())->eliminar();

        break;


    // =========================================================
    // CITAS / SOPORTE TÉCNICO
    // =========================================================

    case '/cita':

        require_once __DIR__ . '/controllers/CitaController.php';

        (new CitaController())->index();

        break;


    case '/cita/guardar':

        require_once __DIR__ . '/controllers/CitaController.php';

        (new CitaController())->guardar();

        break;


    case '/cita/admin':

        require_once __DIR__ . '/controllers/CitaController.php';

        (new CitaController())->admin();

        break;


    case '/cita/cambiarEstado':

        require_once __DIR__ . '/controllers/CitaController.php';

        (new CitaController())->cambiarEstado();

        break;


    case '/cita/cancelar':

        require_once __DIR__ . '/controllers/CitaController.php';

        (new CitaController())->cancelar();

        break;


    // =========================================================
    // CARRITO
    // =========================================================

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


    // =========================================================
    // MIS PEDIDOS - CLIENTE
    // =========================================================

    case '/mis-pedidos':

        require_once __DIR__ . '/controllers/PedidoController.php';

        (new PedidoController())->misPedidos();

        break;


    case '/mis-pedidos/detalle':

        require_once __DIR__ . '/controllers/PedidoController.php';

        (new PedidoController())->detalle();

        break;


    // =========================================================
    // DASHBOARD - ADMIN
    // =========================================================

    case '/dashboard':

        require_once __DIR__ . '/controllers/DashboardController.php';

        (new DashboardController())->index();

        break;


    // =========================================================
    // PEDIDOS - ADMIN
    // =========================================================

    case '/pedido/admin':

        require_once __DIR__ . '/controllers/PedidoController.php';

        (new PedidoController())->admin();

        break;


    case '/pedido/ver':

        require_once __DIR__ . '/controllers/PedidoController.php';

        (new PedidoController())->ver();

        break;


    case '/pedido/cambiarEstado':

        require_once __DIR__ . '/controllers/PedidoController.php';

        (new PedidoController())->cambiarEstado();

        break;


    // =========================================================
    // REPORTE DE VENTAS - ADMIN
    // =========================================================

    case '/reporte-ventas':

        require_once __DIR__ . '/controllers/ReporteVentasController.php';

        (new ReporteVentasController())->index();

        break;


    // =========================================================
    // QUIÉNES SOMOS
    // =========================================================

    case '/quienes-somos':

        require_once __DIR__ . '/views/home/quienes-somos.php';

        break;


    // =========================================================
    // ERROR 404
    // =========================================================

    default:

        http_response_code(404);

        echo "
        <div
            style='
                background:#030712;
                color:#fff;
                font-family:sans-serif;
                text-align:center;
                padding:50px;
                min-height:100vh;
            '
        >

            <h1 style='color:#facc15;'>
                Error 404
            </h1>

            <p style='color:#94a3b8;'>
                La ruta
                <b>" . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . "</b>
                no existe en Origins Games.
            </p>

            <br>

            <a
                href='/origins_games/home'
                style='
                    background:#facc15;
                    color:#000;
                    padding:10px 20px;
                    border-radius:20px;
                    text-decoration:none;
                    font-weight:bold;
                '
            >
                Volver al Inicio
            </a>

        </div>";

        break;
}
