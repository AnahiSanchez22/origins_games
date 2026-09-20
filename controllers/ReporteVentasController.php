<?php

require_once __DIR__ . '/../models/ReporteVentas.php';
require_once __DIR__ . '/../config/AuthMiddleware.php';

class ReporteVentasController
{
    private $reporteModel;

    public function __construct()
    {
        // Solo administradores pueden acceder
        AuthMiddleware::requireAdmin();

        $this->reporteModel = new ReporteVentas();
    }


    public function index()
    {
        /*
         * Obtener filtros enviados por GET.
         */
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';


        /*
         * Validar fechas.
         */
        if (!empty($fechaInicio)) {

            $fechaValida = DateTime::createFromFormat(
                'Y-m-d',
                $fechaInicio
            );

            if (
                !$fechaValida ||
                $fechaValida->format('Y-m-d') !== $fechaInicio
            ) {
                $fechaInicio = '';
            }
        }


        if (!empty($fechaFin)) {

            $fechaValida = DateTime::createFromFormat(
                'Y-m-d',
                $fechaFin
            );

            if (
                !$fechaValida ||
                $fechaValida->format('Y-m-d') !== $fechaFin
            ) {
                $fechaFin = '';
            }
        }


        /*
         * Si las fechas están invertidas,
         * intercambiarlas.
         */
        if (
            !empty($fechaInicio) &&
            !empty($fechaFin) &&
            $fechaInicio > $fechaFin
        ) {
            $temporal = $fechaInicio;
            $fechaInicio = $fechaFin;
            $fechaFin = $temporal;
        }


        /*
         * Obtener información del reporte.
         */
        $resumen = $this->reporteModel->getResumen(
            $fechaInicio,
            $fechaFin
        );


        $ventas = $this->reporteModel->getVentas(
            $fechaInicio,
            $fechaFin
        );


        $productosVendidos =
            $this->reporteModel->getProductosVendidos(
                $fechaInicio,
                $fechaFin
            );


        /*
         * Título de la página.
         */
        $pageTitle = 'Reporte de Ventas - Origins Games';


        /*
         * Mostrar vista.
         */
        require_once __DIR__ .
            '/../views/layouts/header.php';

        require_once __DIR__ .
            '/../views/reportes/ventas.php';

        require_once __DIR__ .
            '/../views/layouts/footer.php';
    }
}