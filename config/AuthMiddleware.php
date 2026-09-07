<?php

class AuthMiddleware {
    
    // Verifica si hay una sesión activa
    public static function isLogged() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['usuario']);
    }

    // Verifica si el usuario actual es Administrador
    public static function isAdmin() {
        if (!self::isLogged()) {
            return false;
        }

        // Revisa rol_id o rol sin generar warnings/notices
        $rolId = $_SESSION['usuario']['rol_id'] ?? null;
        $rol = $_SESSION['usuario']['rol'] ?? null;

        return $rolId == 1 || $rol === 'admin' || $rol === 'administrador';
    }

    // Protege rutas requiriendo inicio de sesión
    public static function requireLogin() {
        if (!self::isLogged()) {
            header('Location: /origins_games/auth/login');
            exit();
        }
    }

    // Protege rutas requiriendo rol de administrador
    public static function requireAdmin() {
        if (!self::isAdmin()) {
            header('Location: /origins_games/home');
            exit();
        }
    }
}