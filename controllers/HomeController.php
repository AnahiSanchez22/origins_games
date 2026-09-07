<?php
require_once __DIR__ . '/../config/Database.php';

class HomeController {

    public function index() {
        $pageTitle = "Inicio - Origins Games";
        
        $productosDestacados = [];
        try {
            $db = Database::connect();
            // Consultamos los últimos 4 productos agregados para mostrarlos como recomendados
            $stmt = $db->query("SELECT id, nombre, descripcion, precio, imagen FROM productos ORDER BY id DESC LIMIT 4");
            $productosDestacados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $productosDestacados = [];
        }

        // Si tienes tu vista en views/home/index.php o directo en views/home.php:
        // Asegúrate de que la ruta coincida con la ubicación real de tu archivo.
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/home/index.php'; // O 'views/home.php' según prefieras
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}