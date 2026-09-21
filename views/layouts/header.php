<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/AuthMiddleware.php';
require_once __DIR__ . '/../../config/Flash.php';

$flash = Flash::get();


// ============================================================
// CANTIDAD DE PRODUCTOS EN EL CARRITO
// ============================================================

$cant_carrito = 0;

if (
    isset($_SESSION['carrito']) &&
    is_array($_SESSION['carrito'])
) {
    foreach ($_SESSION['carrito'] as $prod) {
        $cant_carrito += isset($prod['cantidad'])
            ? (int) $prod['cantidad']
            : 1;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= $pageTitle ?? 'Origins Games' ?>
    </title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

        /* =====================================================
           MENSAJES DEL SISTEMA
        ===================================================== */

        .flash-message {
            width: 100%;

            margin-bottom: 25px;

            padding: 15px 18px;

            border-radius: 14px;

            display: flex;

            align-items: center;

            gap: 12px;

            font-size: .9rem;

            font-weight: 600;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, .35);

            backdrop-filter: blur(12px);
        }


        .flash-icon {
            width: 30px;

            height: 30px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            flex-shrink: 0;

            font-size: 1rem;

            font-weight: 800;
        }


        .flash-text {
            flex: 1;

            line-height: 1.4;
        }


        /* =====================================================
           MENSAJE DE ÉXITO
        ===================================================== */

        .flash-success {
            background: rgba(16, 185, 129, .12);

            border: 1px solid rgba(16, 185, 129, .4);

            color: #6ee7b7;
        }


        .flash-success .flash-icon {
            background: rgba(16, 185, 129, .18);

            color: #34d399;
        }


        /* =====================================================
           MENSAJE DE ERROR
        ===================================================== */

        .flash-error {
            background: rgba(239, 68, 68, .12);

            border: 1px solid rgba(239, 68, 68, .4);

            color: #fca5a5;
        }


        .flash-error .flash-icon {
            background: rgba(239, 68, 68, .18);

            color: #f87171;
        }


        /* =====================================================
           MENSAJE DE ADVERTENCIA
        ===================================================== */

        .flash-warning {
            background: rgba(250, 204, 21, .12);

            border: 1px solid rgba(250, 204, 21, .4);

            color: #fde68a;
        }


        .flash-warning .flash-icon {
            background: rgba(250, 204, 21, .18);

            color: #facc15;
        }


        /* =====================================================
           MENSAJE DE INFORMACIÓN
        ===================================================== */

        .flash-info {
            background: rgba(6, 182, 212, .12);

            border: 1px solid rgba(6, 182, 212, .4);

            color: #67e8f9;
        }


        .flash-info .flash-icon {
            background: rgba(6, 182, 212, .18);

            color: #22d3ee;
        }


        :root {
            --bg-dark: #030712;
            --bg-card: #0b1329;
            --border-card: #1e293b;

            --yellow-accent: #facc15;
            --yellow-hover: #eab308;

            --cyan-accent: #06b6d4;

            --text-primary: #ffffff;
            --text-secondary: #94a3b8;

            --glow-cyan: rgba(6, 182, 212, 0.4);
            --glow-yellow: rgba(250, 204, 21, 0.4);
        }


        * {
            box-sizing: border-box;

            margin: 0;

            padding: 0;
        }


        body {
            font-family: 'Plus Jakarta Sans', sans-serif;

            background-color: var(--bg-dark);

            background-image:
                radial-gradient(
                    circle at 50% 0%,
                    rgba(14, 165, 233, 0.15) 0%,
                    transparent 50%
                ),
                radial-gradient(
                    circle at 50% 100%,
                    rgba(3, 7, 18, 1) 0%,
                    transparent 100%
                );

            color: var(--text-primary);

            min-height: 100vh;

            display: flex;

            flex-direction: column;
        }


        a {
            text-decoration: none !important;
        }


        /* =====================================================
           NAVBAR
        ===================================================== */

        .navbar {
            background: rgba(3, 7, 18, 0.9);

            backdrop-filter: blur(12px);

            border-bottom: 1px solid var(--glow-cyan);

            box-shadow:
                0 4px 20px rgba(6, 182, 212, 0.15);

            padding: 16px 40px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 20px;

            position: sticky;

            top: 0;

            z-index: 1000;
        }


        /* =====================================================
           LOGO
        ===================================================== */

        .navbar .logo {
            font-size: 1.5rem;

            font-weight: 800;

            color: var(--yellow-accent);

            letter-spacing: -0.5px;

            text-shadow:
                0 0 10px var(--glow-yellow);

            white-space: nowrap;
        }


        .navbar .logo span {
            color: var(--text-primary);
        }


        /* =====================================================
           LINKS PRINCIPALES
        ===================================================== */

        .nav-links {
            display: flex;

            align-items: center;

            justify-content: center;

            gap: 20px;

            flex: 1;
        }


        .navbar nav > a {
            color: var(--text-secondary);

            font-size: 0.9rem;

            font-weight: 600;

            transition: all 0.2s ease;

            white-space: nowrap;
        }


        .navbar nav > a:hover {
            color: var(--text-primary);

            text-shadow:
                0 0 8px var(--glow-cyan);
        }


        /* =====================================================
           MENÚ DE ADMINISTRACIÓN
        ===================================================== */

        .admin-menu {
            position: relative;

            display: flex;

            align-items: center;
        }


        .admin-menu-button {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            padding: 8px 14px;

            border-radius: 14px;

            border: 1px solid rgba(244, 63, 94, 0.35);

            background: rgba(244, 63, 94, 0.05);

            color: #f43f5e !important;

            font-size: 0.88rem !important;

            font-weight: 700 !important;

            white-space: nowrap;

            cursor: pointer;

            transition: all 0.2s ease;
        }


        .admin-menu-button:hover {
            background: rgba(244, 63, 94, 0.12);

            border-color: rgba(244, 63, 94, 0.65);

            box-shadow:
                0 0 14px rgba(244, 63, 94, 0.15);

            color: #fb7185 !important;
        }


        .admin-arrow {
            font-size: 0.65rem;

            transition:
                transform 0.2s ease;
        }


        .admin-menu:hover .admin-arrow {
            transform: rotate(180deg);
        }


        /* =====================================================
           DROPDOWN ADMIN
        ===================================================== */

        .admin-dropdown {
            position: absolute;

            top: calc(100% + 10px);

            right: 0;

            width: 230px;

            padding: 8px;

            background: rgba(11, 19, 41, 0.98);

            border: 1px solid rgba(244, 63, 94, 0.3);

            border-radius: 16px;

            box-shadow:
                0 15px 35px rgba(0, 0, 0, 0.5),
                0 0 20px rgba(244, 63, 94, 0.08);

            opacity: 0;

            visibility: hidden;

            transform: translateY(-8px);

            transition:
                opacity 0.2s ease,
                transform 0.2s ease,
                visibility 0.2s ease;

            z-index: 2000;
        }


        .admin-menu:hover .admin-dropdown {
            opacity: 1;

            visibility: visible;

            transform: translateY(0);
        }


        .admin-dropdown a {
            display: block;

            padding: 11px 13px;

            border-radius: 10px;

            color: var(--text-secondary) !important;

            font-size: 0.88rem !important;

            font-weight: 600 !important;

            transition: all 0.2s ease;
        }


        .admin-dropdown a:hover {
            background: rgba(244, 63, 94, 0.09);

            color: #ffffff !important;

            transform: translateX(3px);
        }


        .admin-dropdown .admin-main-link {
            color: var(--yellow-accent) !important;
        }


        .admin-dropdown .admin-main-link:hover {
            background: rgba(250, 204, 21, 0.08);

            color: var(--yellow-accent) !important;
        }


        .admin-dropdown-divider {
            height: 1px;

            background: var(--border-card);

            margin: 6px 4px;
        }


        /* =====================================================
           AUTENTICACIÓN
        ===================================================== */

        .auth-buttons {
            display: flex;

            align-items: center;

            gap: 12px;

            white-space: nowrap;
        }


        .btn-nav-outline {
            border: 1px solid var(--cyan-accent);

            color: var(--cyan-accent) !important;

            padding: 8px 20px;

            border-radius: 20px;

            font-size: 0.85rem !important;

            box-shadow:
                0 0 10px rgba(6, 182, 212, 0.2);

            transition: all 0.2s ease !important;
        }


        .btn-nav-outline:hover {
            background: rgba(6, 182, 212, 0.15);

            box-shadow:
                0 0 15px var(--glow-cyan);
        }


        .btn-nav-solid {
            background: var(--yellow-accent);

            color: #000000 !important;

            padding: 8px 20px;

            border-radius: 20px;

            font-weight: 700 !important;

            font-size: 0.85rem !important;

            box-shadow:
                0 0 10px var(--glow-yellow);

            transition: all 0.2s ease !important;
        }


        .btn-nav-solid:hover {
            background: var(--yellow-hover);

            box-shadow:
                0 0 18px var(--glow-yellow);
        }


        .btn-logout {
            color: #ef4444 !important;

            margin-left: 5px;
        }


        .btn-logout:hover {
            color: #f87171 !important;

            text-shadow:
                0 0 8px rgba(239, 68, 68, 0.35);
        }


        .user-profile-menu {
            position: relative;

            display: flex;

            align-items: center;
        }


        .user-profile-button {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            color: var(--text-secondary) !important;

            font-size: 0.85rem;

            font-weight: 600;

            max-width: 170px;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

            cursor: pointer;

            transition: all 0.2s ease;
        }


        .user-profile-button:hover {
            color: var(--text-primary) !important;

            text-shadow:
                0 0 8px var(--glow-cyan);
        }


        .user-profile-arrow {
            font-size: 0.6rem;

            transition:
                transform 0.2s ease;
        }


        .user-profile-menu:hover .user-profile-arrow {
            transform: rotate(180deg);
        }


        .user-profile-dropdown {
            position: absolute;

            top: calc(100% + 10px);

            right: 0;

            width: 160px;

            padding: 8px;

            background: rgba(11, 19, 41, 0.98);

            border: 1px solid rgba(6, 182, 212, 0.3);

            border-radius: 14px;

            box-shadow:
                0 15px 35px rgba(0, 0, 0, 0.5),
                0 0 20px rgba(6, 182, 212, 0.08);

            opacity: 0;

            visibility: hidden;

            transform: translateY(-8px);

            transition:
                opacity 0.2s ease,
                transform 0.2s ease,
                visibility 0.2s ease;

            z-index: 2000;
        }


        .user-profile-menu:hover .user-profile-dropdown {
            opacity: 1;

            visibility: visible;

            transform: translateY(0);
        }


        .user-profile-dropdown a {
            display: block;

            padding: 10px 12px;

            border-radius: 9px;

            color: var(--text-secondary) !important;

            font-size: 0.85rem !important;

            font-weight: 600 !important;

            transition: all 0.2s ease;
        }


        .user-profile-dropdown a:hover {
            background: rgba(6, 182, 212, 0.09);

            color: #ffffff !important;

            transform: translateX(3px);
        }


        .user-profile-divider {
            height: 1px;

            background: var(--border-card);

            margin: 6px 4px;
        }


        .user-profile-dropdown .user-profile-logout {
            color: #ef4444 !important;
        }


        .user-profile-dropdown .user-profile-logout:hover {
            background: rgba(239, 68, 68, 0.09);

            color: #f87171 !important;

            text-shadow:
                0 0 8px rgba(239, 68, 68, 0.35);
        }


        /* =====================================================
           CONTENEDOR
        ===================================================== */

        .container {
            max-width: 1200px;

            margin: 0 auto;

            padding: 40px 20px;

            flex: 1;

            width: 100%;
        }


        /* =====================================================
           FORMULARIO LOGIN / REGISTRO
        ===================================================== */

        .form-card {
            background: var(--bg-card);

            border: 1px solid var(--border-card);

            border-radius: 20px;

            padding: 40px 48px;

            box-shadow:
                0 12px 40px rgba(0, 0, 0, 0.6);

            max-width: 480px;

            margin: 40px auto;

            width: 100%;
        }


        .form-card h2,
        .form-card h3 {
            font-size: 1.8rem;

            margin-bottom: 24px;

            text-align: center;

            color: var(--text-primary);
        }


        .form-group {
            margin-bottom: 20px;
        }


        .form-group label {
            display: block;

            margin-bottom: 8px;

            color: var(--text-secondary);

            font-size: 0.95rem;
        }


        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;

            padding: 14px 18px;

            background: #060d1e;

            border: 1px solid var(--border-card);

            border-radius: 12px;

            color: #fff;

            font-family: inherit;

            font-size: 0.95rem;
        }


        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;

            border-color: var(--cyan-accent);

            box-shadow:
                0 0 10px var(--glow-cyan);
        }


        .auth-footer-text {
            margin-top: 24px;

            text-align: center;

            color: var(--text-secondary);

            font-size: 0.95rem;

            display: block;
        }


        .auth-footer-text a {
            color: var(--cyan-accent);

            font-weight: 600;

            margin-left: 6px;
        }


        .auth-footer-text a:hover {
            color: #38bdf8;

            text-shadow:
                0 0 8px var(--glow-cyan);
        }


        /* =====================================================
           BOTONES
        ===================================================== */

        .btn {
            padding: 12px 24px;

            border-radius: 20px;

            border: none;

            font-weight: 700;

            font-size: 0.95rem;

            cursor: pointer;

            display: inline-block;

            transition: all 0.2s ease;

            text-align: center;
        }


        .btn-primary {
            background: var(--yellow-accent);

            color: #000;

            box-shadow:
                0 0 12px var(--glow-yellow);

            width: 100%;
        }


        .btn-primary:hover {
            background: var(--yellow-hover);

            transform: translateY(-2px);

            box-shadow:
                0 0 20px var(--glow-yellow);
        }


        /* =====================================================
           GRID DE PRODUCTOS
        ===================================================== */

        .section-title {
            text-align: center;

            font-size: 1.8rem;

            font-weight: 800;

            margin: 40px 0 24px;

            color: var(--yellow-accent);

            text-shadow:
                0 0 10px var(--glow-yellow);
        }


        .grid-products {
            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(280px, 1fr)
                );

            gap: 24px;
        }


        .card-item {
            background: var(--bg-card);

            border: 1px solid var(--border-card);

            border-radius: 18px;

            padding: 24px;

            text-align: center;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.5);

            transition: all 0.3s ease;

            display: flex;

            flex-direction: column;

            justify-content: space-between;
        }


        .card-item:hover {
            border-color: var(--cyan-accent);

            box-shadow:
                0 0 20px var(--glow-cyan);

            transform: translateY(-4px);
        }


        .card-icon {
            font-size: 2.2rem;

            margin-bottom: 16px;

            display: inline-block;

            padding: 16px;

            background:
                rgba(6, 182, 212, 0.1);

            border-radius: 50%;

            border: 1px solid var(--cyan-accent);
        }


        .card-item h3 {
            font-size: 1.2rem;

            margin-bottom: 8px;

            color: var(--text-primary);
        }


        .card-price {
            color: var(--cyan-accent);

            font-weight: 800;

            font-size: 1.3rem;

            margin: 12px 0;
        }


        /* =====================================================
           LAYOUT CITAS
        ===================================================== */

        .citas-layout {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 40px;

            align-items: start;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {
            background:
                rgba(3, 7, 18, 0.95);

            border-top:
                1px solid var(--glow-cyan);

            padding: 20px;

            text-align: center;

            color: var(--text-secondary);

            font-size: 0.85rem;

            margin-top: auto;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1100px) {

            .navbar {
                padding: 16px 24px;
            }

            .nav-links {
                gap: 14px;
            }

            .navbar nav > a {
                font-size: 0.82rem;
            }

            .user-profile-button {
                max-width: 130px;
            }
        }


        @media (max-width: 900px) {

            .navbar {
                flex-wrap: wrap;
            }

            .nav-links {
                order: 3;

                width: 100%;

                flex-basis: 100%;

                justify-content: center;

                flex-wrap: wrap;

                padding-top: 8px;
            }

            .auth-buttons {
                margin-left: auto;
            }

            .citas-layout {
                grid-template-columns: 1fr;
            }
        }


        @media (max-width: 600px) {

            .navbar {
                padding: 14px 16px;
            }

            .navbar .logo {
                font-size: 1.3rem;
            }

            .nav-links {
                gap: 10px;
            }

            .navbar nav > a {
                font-size: 0.78rem;
            }

            .admin-menu-button {
                padding: 7px 11px;

                font-size: 0.78rem !important;
            }

            .admin-dropdown {
                right: auto;

                left: 50%;

                transform:
                    translateX(-50%)
                    translateY(-8px);
            }

            .admin-menu:hover .admin-dropdown {
                transform:
                    translateX(-50%)
                    translateY(0);
            }

            .auth-buttons {
                gap: 7px;
            }

            .btn-nav-outline,
            .btn-nav-solid {
                padding: 7px 12px;
            }

            .container {
                padding: 25px 15px;
            }

            .form-card {
                padding: 30px 22px;
            }

        }

    </style>

</head>


<body>

<header class="navbar">

    <!-- LOGO -->

    <a
        href="/origins_games/home"
        class="logo"
    >
        Origins<span>Games</span>
    </a>


    <!-- NAV -->

    <nav class="nav-links">

        <a href="/origins_games/home">
            Inicio
        </a>


        <a href="/origins_games/producto">
            Catálogo
        </a>


        <a href="/origins_games/cita">
            Soporte Técnico
        </a>


        <a href="/origins_games/carrito">
            Carrito (<?= $cant_carrito ?>)
        </a>


        <?php if (AuthMiddleware::isLogged()): ?>

            <a href="/origins_games/mis-pedidos">
                Mis Pedidos
            </a>

        <?php endif; ?>


        <?php if (AuthMiddleware::isAdmin()): ?>

            <!-- MENÚ ADMINISTRACIÓN -->

            <div class="admin-menu">

                <a
                    href="#"
                    class="admin-menu-button"
                    onclick="return false;"
                >
                    Administración

                    <span class="admin-arrow">
                        ▼
                    </span>
                </a>


                <div class="admin-dropdown">

                    <a
                        href="/origins_games/dashboard"
                        class="admin-main-link"
                    >
                        Dashboard
                    </a>


                    <div class="admin-dropdown-divider"></div>


                    <a href="/origins_games/cita/admin">
                        Gestión de Citas
                    </a>


                    <a href="/origins_games/producto/admin">
                        Inventario
                    </a>


                    <a href="/origins_games/usuario">
                        Usuarios
                    </a>


                    <a href="/origins_games/pedido/admin">
                        Pedidos
                    </a>


                    <a href="/origins_games/reporte-ventas">
                        Reporte de Ventas
                    </a>

                </div>

            </div>

        <?php endif; ?>

    </nav>


    <!-- AUTENTICACIÓN -->

    <div class="auth-buttons">

        <?php if (AuthMiddleware::isLogged()): ?>

            <div class="user-profile-menu">

                <a
                    href="#"
                    class="user-profile-button"
                    onclick="return false;"
                >

                    <?= htmlspecialchars(
                        $_SESSION['usuario']['nombre'] ?? 'Usuario'
                    ) ?>

                    <span class="user-profile-arrow">
                        ▼
                    </span>

                </a>


                <div class="user-profile-dropdown">

                    <a href="/origins_games/perfil">
                        Mi Perfil
                    </a>


                    <a href="/origins_games/favoritos">
                        Favoritos
                    </a>


                    <div class="user-profile-divider"></div>


                    <a
                        href="/origins_games/auth/logout"
                        class="user-profile-logout"
                    >
                        Cerrar sesión
                    </a>

                </div>

            </div>

        <?php else: ?>

            <a
                href="/origins_games/auth/login"
                class="btn-nav-outline"
            >
                Iniciar Sesión
            </a>


            <a
                href="/origins_games/auth/register"
                class="btn-nav-solid"
            >
                Registrarse
            </a>

        <?php endif; ?>

    </div>

</header>


<main class="container">

    <?php if ($flash): ?>

        <?php
            $tipo = $flash['tipo'] ?? 'success';

            $iconos = [
                'success' => '✓',
                'error'   => '!',
                'warning' => '⚠',
                'info'    => 'i'
            ];

            $icono = $iconos[$tipo] ?? '✓';
        ?>

        <div
            class="flash-message flash-<?= htmlspecialchars($tipo) ?>"
        >

            <div class="flash-icon">
                <?= $icono ?>
            </div>

            <div class="flash-text">
                <?= htmlspecialchars($flash['mensaje']) ?>
            </div>

        </div>

    <?php endif; ?>