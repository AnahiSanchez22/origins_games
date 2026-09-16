<?php

require_once __DIR__ . '/../config/Database.php';

class HomeController
{
    public function index()
    {
        $pageTitle = "Inicio - Origins Games";

        $productosDestacados = [];

        try {

            $db = Database::connect();

            /*
             * Traemos también el stock actual.
             *
             * Así la página de inicio siempre muestra
             * el estado real del inventario.
             */
            $stmt = $db->query("
                SELECT
                    id,
                    nombre,
                    descripcion,
                    precio,
                    imagen,
                    stock
                FROM productos
                ORDER BY id DESC
                LIMIT 4
            ");

            $productosDestacados =
                $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {

            $productosDestacados = [];
        }


        require_once __DIR__ .
            '/../views/layouts/header.php';

        require_once __DIR__ .
            '/../views/home/index.php';

        require_once __DIR__ .
            '/../views/layouts/footer.php';
    }
}