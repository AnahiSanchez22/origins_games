<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../config/AuthMiddleware.php';

// Calcular total de items acumulados en el carrito
$cant_carrito = 0;
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $prod) {
        $cant_carrito += isset($prod['cantidad']) ? (int)$prod['cantidad'] : 1;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Origins Games' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
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
                radial-gradient(circle at 50% 0%, rgba(14, 165, 233, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 50% 100%, rgba(3, 7, 18, 1) 0%, transparent 100%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none !important;
        }

        .navbar {
            background: rgba(3, 7, 18, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glow-cyan);
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.15);
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--yellow-accent);
            letter-spacing: -0.5px;
            text-shadow: 0 0 10px var(--glow-yellow);
        }

        .navbar .logo span {
            color: var(--text-primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .navbar nav a {
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .navbar nav a:hover {
            color: var(--text-primary);
            text-shadow: 0 0 8px var(--glow-cyan);
        }

        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-nav-outline {
            border: 1px solid var(--cyan-accent);
            color: var(--cyan-accent) !important;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.85rem !important;
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.2);
            transition: all 0.2s ease !important;
        }

        .btn-nav-outline:hover {
            background: rgba(6, 182, 212, 0.15);
            box-shadow: 0 0 15px var(--glow-cyan);
        }

        .btn-nav-solid {
            background: var(--yellow-accent);
            color: #000000 !important;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
            box-shadow: 0 0 10px var(--glow-yellow);
            transition: all 0.2s ease !important;
        }

        .btn-nav-solid:hover {
            background: var(--yellow-hover);
            box-shadow: 0 0 18px var(--glow-yellow);
        }

        .badge-admin {
            color: #f43f5e !important;
            border: 1px solid rgba(244, 63, 94, 0.3);
            padding: 6px 12px;
            border-radius: 12px;
        }

        .btn-logout {
            color: #ef4444 !important;
            margin-left: 10px;
        }

        .user-info {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            flex: 1;
            width: 100%;
        }

        /* Formulario Login / Registro */
        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 20px;
            padding: 40px 48px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.6);
            max-width: 480px;
            margin: 40px auto;
            width: 100%;
        }

        .form-card h2, .form-card h3 {
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
            box-shadow: 0 0 10px var(--glow-cyan);
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
            text-shadow: 0 0 8px var(--glow-cyan);
        }

        /* Botones */
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
            box-shadow: 0 0 12px var(--glow-yellow);
            width: 100%;
        }

        .btn-primary:hover {
            background: var(--yellow-hover);
            transform: translateY(-2px);
            box-shadow: 0 0 20px var(--glow-yellow);
        }

        /* Grid de Productos */
        .section-title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 800;
            margin: 40px 0 24px;
            color: var(--yellow-accent);
            text-shadow: 0 0 10px var(--glow-yellow);
        }

        .grid-products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .card-item {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 18px;
            padding: 24px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-item:hover {
            border-color: var(--cyan-accent);
            box-shadow: 0 0 20px var(--glow-cyan);
            transform: translateY(-4px);
        }

        .card-icon {
            font-size: 2.2rem;
            margin-bottom: 16px;
            display: inline-block;
            padding: 16px;
            background: rgba(6, 182, 212, 0.1);
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

        /* Layout Citas */
        .citas-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .citas-layout {
                grid-template-columns: 1fr;
            }
        }

        .footer {
            background: rgba(3, 7, 18, 0.95);
            border-top: 1px solid var(--glow-cyan);
            padding: 20px;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <a href="/origins_games/home" class="logo">Origins<span>Games</span></a>
        
        <nav class="nav-links">
            <a href="/origins_games/home">Inicio</a>
            <a href="/origins_games/producto">Catálogo</a>
            <a href="/origins_games/cita">Soporte Técnico</a>
            <a href="/origins_games/carrito">Carrito (<?= $cant_carrito ?>)</a>
            <?php if (AuthMiddleware::isLogged()): ?>

    <a href="/origins_games/mis-pedidos">
        Mis Pedidos
    </a>

<?php endif; ?>

<?php if (AuthMiddleware::isAdmin()): ?>

    <a href="/origins_games/dashboard" class="badge-admin">
        Dashboard
    </a>

    <a href="/origins_games/cita/admin" class="badge-admin">
        Gestión Citas
    </a>

    <a href="/origins_games/producto/admin" class="badge-admin">
        Inventario
    </a>

    <a href="/origins_games/usuario" class="badge-admin">
        Usuarios
    </a>

 <?php endif; ?>
        </nav>

        <div class="auth-buttons">
            <?php if (AuthMiddleware::isLogged()): ?>
                <span class="user-info">Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? '') ?></span>
                <a href="/origins_games/auth/logout" class="btn-logout">Salir</a>
            <?php else: ?>
                <a href="/origins_games/auth/login" class="btn-nav-outline">Iniciar Sesión</a>
                <a href="/origins_games/auth/register" class="btn-nav-solid">Registrarse</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">