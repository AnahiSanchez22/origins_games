<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Flash
{
    public static function set($mensaje, $tipo = 'success')
    {
        $_SESSION['flash'] = [
            'mensaje' => $mensaje,
            'tipo' => $tipo
        ];
    }

    public static function get()
    {
        if (!isset($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];

        unset($_SESSION['flash']);

        return $flash;
    }
}