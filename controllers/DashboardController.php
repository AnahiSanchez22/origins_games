<?php

require_once __DIR__ . '/../models/Dashboard.php';
require_once __DIR__ . '/../config/AuthMiddleware.php';

class DashboardController
{
    private $dashboardModel;

    public function __construct()
    {
        // Solo administradores pueden acceder
        AuthMiddleware::requireAdmin();

        $this->dashboardModel = new Dashboard();
    }

    public function index()
    {
        // Resumen general
        $resumen = $this->dashboardModel->getResumen();

        // Estados de los pedidos
        $pedidosEstado = $this->dashboardModel->getPedidosPorEstado();

        // Últimos pedidos
        $pedidosRecientes = $this->dashboardModel->getPedidosRecientes(5);

        // Productos con poco stock
        $productosStockBajo = $this->dashboardModel->getProductosStockBajo(6);

        $pageTitle = 'Dashboard - Origins Games';

        require_once __DIR__ . '/../views/layouts/header.php';

        require_once __DIR__ . '/../views/dashboard/index.php';

        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}