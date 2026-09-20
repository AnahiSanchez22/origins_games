<?php
$fechaInicio = $fechaInicio ?? '';
$fechaFin = $fechaFin ?? '';

$totalVentas = (float) ($resumen['total_ventas'] ?? 0);
$totalPedidos = (int) ($resumen['total_pedidos'] ?? 0);
$totalUnidades = (int) ($resumen['total_unidades'] ?? 0);

$ventas = $ventas ?? [];
$productosVendidos = $productosVendidos ?? [];
?>

<style>
    .reporte-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .reporte-header {
        margin-bottom: 25px;
    }

    .reporte-header h1 {
        color: var(--yellow-accent);
        margin-bottom: 8px;
        font-size: 2rem;
    }

    .reporte-header p {
        color: var(--text-secondary);
        margin: 0;
    }

    .filtros-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 18px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .18);
    }

    .filtros-form {
        display: grid;
        grid-template-columns: 1fr 1fr auto auto;
        gap: 15px;
        align-items: end;
    }

    .campo-filtro {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .campo-filtro label {
        color: var(--text-primary);
        font-size: .9rem;
        font-weight: 700;
    }

    .campo-filtro input {
        width: 100%;
        box-sizing: border-box;
        padding: 11px 12px;
        border-radius: 10px;
        border: 1px solid var(--border-card);
        background: rgba(0, 0, 0, .25);
        color: var(--text-primary);
        outline: none;
    }

    .campo-filtro input:focus {
        border-color: var(--yellow-accent);
    }

    .btn-reporte {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 18px;
        border-radius: 10px;
        border: 1px solid var(--yellow-accent);
        background: var(--yellow-accent);
        color: #111;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
        white-space: nowrap;
    }

    .btn-reporte:hover {
        transform: translateY(-1px);
        filter: brightness(1.08);
    }

    .btn-limpiar {
        background: transparent;
        color: var(--text-primary);
        border-color: var(--border-card);
    }

    .btn-limpiar:hover {
        border-color: var(--yellow-accent);
        color: var(--yellow-accent);
    }

    .resumen-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 30px;
    }

    .resumen-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .18);
    }

    .resumen-label {
        color: var(--text-secondary);
        font-size: .85rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .resumen-valor {
        color: var(--text-primary);
        font-size: 1.8rem;
        font-weight: 900;
    }

    .resumen-card.ventas .resumen-valor {
        color: #34d399;
    }

    .resumen-card.pedidos .resumen-valor {
        color: var(--yellow-accent);
    }

    .resumen-card.unidades .resumen-valor {
        color: #60a5fa;
    }

    .seccion-reporte {
        margin-bottom: 30px;
    }

    .seccion-titulo {
        color: var(--yellow-accent);
        font-size: 1.25rem;
        margin-bottom: 15px;
    }

    .tabla-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .18);
    }

    .tabla-scroll {
        overflow-x: auto;
    }

    .reporte-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    .reporte-table th,
    .reporte-table td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid var(--border-card);
    }

    .reporte-table th {
        color: var(--yellow-accent);
        font-size: .82rem;
        text-transform: uppercase;
        letter-spacing: .4px;
        background: rgba(0, 0, 0, .15);
    }

    .reporte-table td {
        color: var(--text-primary);
        font-size: .9rem;
    }

    .reporte-table tbody tr:last-child td {
        border-bottom: none;
    }

    .reporte-table tbody tr:hover {
        background: rgba(255, 255, 255, .025);
    }

    .estado {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 800;
        text-transform: capitalize;
    }

    .estado-pagado {
        color: #34d399;
        background: rgba(52, 211, 153, .12);
    }

    .estado-enviado {
        color: #60a5fa;
        background: rgba(96, 165, 250, .12);
    }

    .estado-pendiente {
        color: #fbbf24;
        background: rgba(251, 191, 36, .12);
    }

    .estado-cancelado {
        color: #f87171;
        background: rgba(248, 113, 113, .12);
    }

    .total-venta {
        color: #34d399 !important;
        font-weight: 900;
    }

    .cantidad-producto {
        color: var(--yellow-accent) !important;
        font-weight: 900;
    }

    .sin-registros {
        padding: 30px;
        text-align: center;
        color: var(--text-secondary);
    }

    .rango-activo {
        margin-top: 10px;
        color: var(--text-secondary);
        font-size: .85rem;
    }

    @media (max-width: 850px) {
        .filtros-form {
            grid-template-columns: 1fr 1fr;
        }

        .resumen-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 550px) {
        .reporte-container {
            padding: 15px;
        }

        .filtros-form {
            grid-template-columns: 1fr;
        }

        .reporte-header h1 {
            font-size: 1.6rem;
        }
    }
</style>

<div class="reporte-container">

    <div class="reporte-header">
        <h1>Reporte de Ventas</h1>
        <p>Consulta y analiza las ventas realizadas en Origins Games.</p>

        <?php if (!empty($fechaInicio) || !empty($fechaFin)): ?>
            <div class="rango-activo">
                <?php if (!empty($fechaInicio) && !empty($fechaFin)): ?>
                    Mostrando resultados desde
                    <strong><?= htmlspecialchars($fechaInicio) ?></strong>
                    hasta
                    <strong><?= htmlspecialchars($fechaFin) ?></strong>.
                <?php elseif (!empty($fechaInicio)): ?>
                    Mostrando resultados desde
                    <strong><?= htmlspecialchars($fechaInicio) ?></strong>.
                <?php elseif (!empty($fechaFin)): ?>
                    Mostrando resultados hasta
                    <strong><?= htmlspecialchars($fechaFin) ?></strong>.
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- FILTROS -->
    <div class="filtros-card">

        <form
            action="/origins_games/reporte-ventas"
            method="GET"
            class="filtros-form"
        >

            <div class="campo-filtro">
                <label for="fecha_inicio">Fecha inicial</label>

                <input
                    type="date"
                    id="fecha_inicio"
                    name="fecha_inicio"
                    value="<?= htmlspecialchars($fechaInicio) ?>"
                >
            </div>

            <div class="campo-filtro">
                <label for="fecha_fin">Fecha final</label>

                <input
                    type="date"
                    id="fecha_fin"
                    name="fecha_fin"
                    value="<?= htmlspecialchars($fechaFin) ?>"
                >
            </div>

            <button type="submit" class="btn-reporte">
                Filtrar
            </button>

            <a
                href="/origins_games/reporte-ventas"
                class="btn-reporte btn-limpiar"
            >
                Limpiar
            </a>

        </form>

    </div>

    <!-- RESUMEN -->
    <div class="resumen-grid">

        <div class="resumen-card ventas">
            <div class="resumen-label">
                Ventas Totales
            </div>

            <div class="resumen-valor">
                $<?= number_format($totalVentas, 0, ',', '.') ?>
            </div>
        </div>

        <div class="resumen-card pedidos">
            <div class="resumen-label">
                Pedidos
            </div>

            <div class="resumen-valor">
                <?= number_format($totalPedidos, 0, ',', '.') ?>
            </div>
        </div>

        <div class="resumen-card unidades">
            <div class="resumen-label">
                Unidades Vendidas
            </div>

            <div class="resumen-valor">
                <?= number_format($totalUnidades, 0, ',', '.') ?>
            </div>
        </div>

    </div>

    <!-- VENTAS -->
    <section class="seccion-reporte">

        <h2 class="seccion-titulo">
            Detalle de Ventas
        </h2>

        <div class="tabla-card">
            <div class="tabla-scroll">

                <table class="reporte-table">

                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($ventas)): ?>

                            <?php foreach ($ventas as $venta): ?>

                                <?php
                                $estado = strtolower(
                                    trim($venta['estado'] ?? '')
                                );

                                $claseEstado = 'estado-' . $estado;
                                ?>

                                <tr>

                                    <td>
                                        #<?= (int) ($venta['id'] ?? 0) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $venta['fecha_pedido'] ?? ''
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $venta['cliente'] ?? 'Sin nombre'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $venta['email'] ?? ''
                                        ) ?>
                                    </td>

                                    <td>
                                        <span class="estado <?= htmlspecialchars($claseEstado) ?>">
                                            <?= htmlspecialchars($estado) ?>
                                        </span>
                                    </td>

                                    <td class="total-venta">
                                        $<?= number_format(
                                            (float) ($venta['total'] ?? 0),
                                            0,
                                            ',',
                                            '.'
                                        ) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6">
                                    <div class="sin-registros">
                                        No hay ventas registradas
                                        para el período seleccionado.
                                    </div>
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>
        </div>

    </section>

    <!-- PRODUCTOS VENDIDOS -->
    <section class="seccion-reporte">

        <h2 class="seccion-titulo">
            Productos Vendidos
        </h2>

        <div class="tabla-card">
            <div class="tabla-scroll">

                <table class="reporte-table">

                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Unidades Vendidas</th>
                            <th>Total Vendido</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($productosVendidos)): ?>

                            <?php foreach ($productosVendidos as $producto): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars(
                                            $producto['producto'] ?? 'Producto'
                                        ) ?>
                                    </td>

                                    <td class="cantidad-producto">
                                        <?= number_format(
                                            (int) ($producto['cantidad_vendida'] ?? 0),
                                            0,
                                            ',',
                                            '.'
                                        ) ?>
                                    </td>

                                    <td class="total-venta">
                                        $<?= number_format(
                                            (float) ($producto['total_vendido'] ?? 0),
                                            0,
                                            ',',
                                            '.'
                                        ) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="3">
                                    <div class="sin-registros">
                                        No hay productos vendidos
                                        para el período seleccionado.
                                    </div>
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>
        </div>

    </section>

</div>