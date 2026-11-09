
<?php

require_once __DIR__ . '/../models/ReporteVentas.php';
require_once __DIR__ . '/../config/AuthMiddleware.php';
require_once __DIR__ . '/../config/PdfReportGenerator.php';

class ReporteVentasController
{
    private $reporteModel;

    public function __construct()
    {
        // Solo los administradores pueden acceder a los reportes.
        AuthMiddleware::requireAdmin();

        $this->reporteModel = new ReporteVentas();
    }

    /**
     * Muestra el reporte de ventas en pantalla.
     */
    public function index()
    {
        [$fechaInicio, $fechaFin] = $this->obtenerFiltros();

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

        $pageTitle = 'Reporte de Ventas - Origins Games';

        require_once __DIR__ . '/../views/layouts/header.php';

        require_once __DIR__ . '/../views/reportes/ventas.php';

        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Genera y descarga el reporte en PDF.
     */
    public function exportarPdf()
    {
        // Configurar la hora de Colombia.
        date_default_timezone_set('America/Bogota');

        [$fechaInicio, $fechaFin] = $this->obtenerFiltros();

        // Consultar información de la base de datos.
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

        // Crear documento horizontal.
        $pdf = new PdfReportGenerator('landscape');

        $pdf->addPage();

        // ================================================
        // ENCABEZADO
        // ================================================

        $pdf->addText(
            40,
            555,
            'ORIGINS GAMES',
            19,
            [0.98, 0.78, 0.08],
            true
        );

        $pdf->addText(
            40,
            532,
            'REPORTE DE VENTAS',
            14,
            [0.08, 0.75, 0.82],
            true
        );

        $fechaGeneracion = date('d/m/Y H:i');

        $pdf->addText(
            610,
            555,
            'Generado: ' . $fechaGeneracion,
            9,
            [0.35, 0.39, 0.45]
        );

        // ================================================
        // PERÍODO CONSULTADO
        // ================================================

        if (
            $fechaInicio !== ''
            || $fechaFin !== ''
        ) {
            $rango = 'Periodo: '
                . (
                    $fechaInicio !== ''
                    ? $this->formatearFecha($fechaInicio)
                    : 'Sin fecha inicial'
                )
                . ' - '
                . (
                    $fechaFin !== ''
                    ? $this->formatearFecha($fechaFin)
                    : 'Sin fecha final'
                );

        } else {

            $rango = 'Periodo: Todas las fechas registradas';
        }

        $pdf->addText(
            40,
            510,
            $rango,
            10,
            [0.18, 0.22, 0.28]
        );

        $pdf->addLine(
            40,
            498,
            802,
            498,
            [0.98, 0.78, 0.08],
            1.5
        );

        // ================================================
        // RESUMEN GENERAL
        // ================================================

        $pdf->addText(
            40,
            475,
            'RESUMEN GENERAL',
            11,
            [0.08, 0.75, 0.82],
            true
        );

        $pdf->addText(
            40,
            455,
            'Ventas totales: $'
            . $this->formatearNumero(
                $resumen['total_ventas'] ?? 0
            )
            . ' COP',
            10
        );

        $pdf->addText(
            310,
            455,
            'Pedidos: '
            . number_format(
                (int) ($resumen['total_pedidos'] ?? 0),
                0,
                ',',
                '.'
            ),
            10
        );

        $pdf->addText(
            520,
            455,
            'Unidades vendidas: '
            . number_format(
                (int) ($resumen['total_unidades'] ?? 0),
                0,
                ',',
                '.'
            ),
            10
        );

        // ================================================
        // DETALLE DE VENTAS
        // ================================================

        $pdf->addText(
            40,
            425,
            'DETALLE DE VENTAS',
            11,
            [0.08, 0.75, 0.82],
            true
        );

        $columnasVentas = [
            ['text' => 'Pedido', 'width' => 55],
            ['text' => 'Fecha', 'width' => 105],
            ['text' => 'Cliente', 'width' => 180],
            ['text' => 'Correo', 'width' => 220],
            ['text' => 'Estado', 'width' => 85],
            ['text' => 'Total', 'width' => 117]
        ];

        $anchosVentas = [55, 105, 180, 220, 85, 117];

        $pdf->drawTableHeader(
            40,
            405,
            $columnasVentas
        );

        $y = 387;

        if (empty($ventas)) {

            $pdf->addText(
                45,
                $y,
                'No hay ventas registradas para el periodo seleccionado.',
                9
            );

            $y -= 20;

        } else {

            foreach ($ventas as $venta) {

                // Crear una nueva página cuando no haya espacio.
                if ($y < 55) {

                    $pdf->addPage();

                    $y = 555;

                    $pdf->addText(
                        40,
                        $y,
                        'DETALLE DE VENTAS (continuacion)',
                        11,
                        [0.08, 0.75, 0.82],
                        true
                    );

                    $y -= 20;

                    $pdf->drawTableHeader(
                        40,
                        $y,
                        $columnasVentas
                    );

                    $y -= 18;
                }

                $pdf->drawTableRow(
                    40,
                    $y,
                    [
                        '#' . (int) ($venta['id'] ?? 0),

                        $this->formatearFechaHora(
                            $venta['fecha_pedido'] ?? ''
                        ),

                        $venta['cliente'] ?? 'Sin nombre',

                        $venta['email'] ?? '',

                        ucfirst($venta['estado'] ?? ''),

                        '$' . $this->formatearNumero(
                            $venta['total'] ?? 0
                        ) . ' COP'
                    ],
                    $anchosVentas
                );

                $y -= 18;
            }
        }

        // ================================================
        // PRODUCTOS VENDIDOS
        // ================================================

        if ($y < 125) {

            $pdf->addPage();

            $y = 555;

        } else {

            $y -= 18;
        }

        $pdf->addText(
            40,
            $y,
            'PRODUCTOS VENDIDOS',
            11,
            [0.08, 0.75, 0.82],
            true
        );

        $y -= 20;

        $columnasProductos = [
            ['text' => 'Producto', 'width' => 430],
            ['text' => 'Unidades vendidas', 'width' => 160],
            ['text' => 'Total vendido', 'width' => 172]
        ];

        $anchosProductos = [430, 160, 172];

        $pdf->drawTableHeader(
            40,
            $y,
            $columnasProductos
        );

        $y -= 18;

        if (empty($productosVendidos)) {

            $pdf->addText(
                45,
                $y,
                'No hay productos vendidos para el periodo seleccionado.',
                9
            );

        } else {

            foreach ($productosVendidos as $producto) {

                if ($y < 55) {

                    $pdf->addPage();

                    $y = 555;

                    $pdf->addText(
                        40,
                        $y,
                        'PRODUCTOS VENDIDOS (continuacion)',
                        11,
                        [0.08, 0.75, 0.82],
                        true
                    );

                    $y -= 20;

                    $pdf->drawTableHeader(
                        40,
                        $y,
                        $columnasProductos
                    );

                    $y -= 18;
                }

                $pdf->drawTableRow(
                    40,
                    $y,
                    [
                        $producto['producto'] ?? 'Producto',

                        number_format(
                            (int) (
                                $producto['cantidad_vendida'] ?? 0
                            ),
                            0,
                            ',',
                            '.'
                        ),

                        '$' . $this->formatearNumero(
                            $producto['total_vendido'] ?? 0
                        ) . ' COP'
                    ],
                    $anchosProductos
                );

                $y -= 18;
            }
        }

        // ================================================
        // DESCARGA
        // ================================================

        $nombreArchivo =
            'reporte_ventas_' . date('Y-m-d_H-i-s') . '.pdf';

        $pdf->output($nombreArchivo);

        exit;
    }

    /**
     * Obtiene y valida las fechas enviadas por GET.
     */
    private function obtenerFiltros()
    {
        $fechaInicio = trim(
            $_GET['fecha_inicio'] ?? ''
        );

        $fechaFin = trim(
            $_GET['fecha_fin'] ?? ''
        );

        if (!$this->esFechaValida($fechaInicio)) {
            $fechaInicio = '';
        }

        if (!$this->esFechaValida($fechaFin)) {
            $fechaFin = '';
        }

        // Ordenar fechas si se ingresaron invertidas.
        if (
            $fechaInicio !== ''
            && $fechaFin !== ''
            && $fechaInicio > $fechaFin
        ) {
            [$fechaInicio, $fechaFin] = [
                $fechaFin,
                $fechaInicio
            ];
        }

        return [$fechaInicio, $fechaFin];
    }

    /**
     * Comprueba que la fecha tenga formato Y-m-d válido.
     */
    private function esFechaValida($fecha)
    {
        if ($fecha === '') {
            return true;
        }

        $fechaObjeto = DateTime::createFromFormat(
            '!Y-m-d',
            $fecha
        );

        return $fechaObjeto !== false
            && $fechaObjeto->format('Y-m-d') === $fecha;
    }

    /**
     * Convierte una fecha al formato día/mes/año.
     */
    private function formatearFecha($fecha)
    {
        $fechaObjeto = DateTime::createFromFormat(
            '!Y-m-d',
            $fecha
        );

        return $fechaObjeto
            ? $fechaObjeto->format('d/m/Y')
            : $fecha;
    }

    /**
     * Convierte fecha y hora para mostrarla en el PDF.
     */
    private function formatearFechaHora($fecha)
    {
        if ($fecha === '') {
            return '';
        }

        $marcaTiempo = strtotime($fecha);

        return $marcaTiempo !== false
            ? date('d/m/Y H:i', $marcaTiempo)
            : $fecha;
    }

    /**
     * Formatea valores monetarios.
     */
    private function formatearNumero($numero)
    {
        return number_format(
            (float) $numero,
            0,
            ',',
            '.'
        );
    }
}
