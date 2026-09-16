<style>
    .dashboard-wrap {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    .dashboard-title {
        margin-bottom: 24px;
    }

    .dashboard-title h2 {
        color: #fff;
        font-size: 1.9rem;
        margin-bottom: 6px;
    }

    .dashboard-title p {
        color: var(--text-secondary);
        font-size: .9rem;
    }

    /* TARJETAS PRINCIPALES */

    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .dashboard-card {
        background: rgba(11, 19, 41, .82);
        border: 1px solid var(--border-card);
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,.35);
        transition: .2s ease;
    }

    .dashboard-card:hover {
        border-color: var(--cyan-accent);
        box-shadow: 0 0 18px var(--glow-cyan);
        transform: translateY(-2px);
    }

    .dashboard-card-label {
        color: var(--text-secondary);
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 10px;
    }

    .dashboard-card-value {
        color: #fff;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .dashboard-card-value.yellow {
        color: var(--yellow-accent);
    }

    .dashboard-card-value.cyan {
        color: var(--cyan-accent);
    }

    .dashboard-card-value.red {
        color: #f87171;
    }

    /* COLUMNAS */

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1.65fr 1fr;
        gap: 20px;
    }

    /* PANELES */

    .dashboard-panel {
        background: rgba(11, 19, 41, .78);
        border: 1px solid var(--border-card);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,.35);
        margin-bottom: 20px;
    }

    .dashboard-panel h3 {
        padding: 18px 20px;
        color: #fff;
        font-size: 1rem;
        border-bottom: 1px solid var(--border-card);
    }

    /* TABLA */

    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dashboard-table th,
    .dashboard-table td {
        padding: 13px 16px;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,.05);
        font-size: .84rem;
    }

    .dashboard-table th {
        color: var(--cyan-accent);
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .dashboard-table td {
        color: var(--text-secondary);
    }

    .dashboard-table strong {
        color: #fff;
    }

    /* ESTADOS */

    .dashboard-statuses {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        padding: 18px;
    }

    .dashboard-status {
        border: 1px solid var(--border-card);
        border-radius: 14px;
        padding: 15px;
        background: rgba(6,13,30,.65);
    }

    .dashboard-status span {
        display: block;
        color: var(--text-secondary);
        font-size: .78rem;
        text-transform: capitalize;
        margin-bottom: 5px;
    }

    .dashboard-status strong {
        color: #fff;
        font-size: 1.35rem;
    }

    .status-pendiente strong {
        color: #fbbf24;
    }

    .status-pagado strong {
        color: #34d399;
    }

    .status-enviado strong {
        color: #38bdf8;
    }

    .status-cancelado strong {
        color: #f87171;
    }

    /* STOCK */

    .stock-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 14px 18px;
        border-bottom: 1px solid rgba(255,255,255,.05);
    }

    .stock-item:last-child {
        border-bottom: 0;
    }

    .stock-name {
        color: #fff;
        font-size: .84rem;
    }

    .stock-number {
        min-width: 44px;
        text-align: center;
        padding: 5px 8px;
        border-radius: 10px;
        color: #f87171;
        border: 1px solid rgba(248,113,113,.35);
        background: rgba(248,113,113,.08);
        font-size: .78rem;
        font-weight: 700;
    }

    /* ENLACES */

    .dashboard-link {
        display: inline-block;
        margin: 0 18px 18px;
        color: var(--cyan-accent) !important;
        font-size: .82rem;
        font-weight: 700;
    }

    .dashboard-link:hover {
        color: #67e8f9 !important;
        text-shadow: 0 0 8px var(--glow-cyan);
    }

    .dashboard-empty {
        padding: 24px;
        color: var(--text-secondary);
        text-align: center;
    }

    /* RESPONSIVE */

    @media (max-width: 950px) {

        .dashboard-cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {

        .dashboard-cards {
            grid-template-columns: 1fr;
        }

        .dashboard-statuses {
            grid-template-columns: 1fr;
        }
    }
</style>


<div class="dashboard-wrap">

    <!-- TÍTULO -->

    <div class="dashboard-title">

        <h2>Dashboard</h2>

        <p>
            Resumen general de Origins Games.
        </p>

    </div>


    <!-- TARJETAS PRINCIPALES -->

    <div class="dashboard-cards">

        <!-- VENTAS -->

        <div class="dashboard-card">

            <div class="dashboard-card-label">
                Ventas totales
            </div>

            <div class="dashboard-card-value yellow">

                $
                <?= number_format(
                    (float) $resumen['ventas_totales'],
                    0,
                    ',',
                    '.'
                ) ?>

            </div>

        </div>


        <!-- PEDIDOS -->

        <div class="dashboard-card">

            <div class="dashboard-card-label">
                Pedidos
            </div>

            <div class="dashboard-card-value cyan">

                <?= (int) $resumen['total_pedidos'] ?>

            </div>

        </div>


        <!-- USUARIOS -->

        <div class="dashboard-card">

            <div class="dashboard-card-label">
                Usuarios
            </div>

            <div class="dashboard-card-value">

                <?= (int) $resumen['total_usuarios'] ?>

            </div>

        </div>


        <!-- SIN STOCK -->

        <div class="dashboard-card">

            <div class="dashboard-card-label">
                Productos sin stock
            </div>

            <div class="dashboard-card-value red">

                <?= (int) $resumen['productos_sin_stock'] ?>

            </div>

        </div>

    </div>


    <!-- CONTENIDO -->

    <div class="dashboard-grid">

        <!-- COLUMNA IZQUIERDA -->

        <div>

            <!-- PEDIDOS RECIENTES -->

            <div class="dashboard-panel">

                <h3>
                    Pedidos recientes
                </h3>


                <?php if (!empty($pedidosRecientes)): ?>

                    <div style="overflow-x:auto;">

                        <table class="dashboard-table">

                            <thead>

                                <tr>

                                    <th>
                                        Pedido
                                    </th>

                                    <th>
                                        Cliente
                                    </th>

                                    <th>
                                        Estado
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php foreach ($pedidosRecientes as $pedido): ?>

                                    <tr>

                                        <td>

                                            <strong>
                                                #<?= (int) $pedido['id'] ?>
                                            </strong>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $pedido['usuario_nombre']
                                            ) ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                ucfirst($pedido['estado'])
                                            ) ?>

                                        </td>


                                        <td>

                                            $
                                            <?= number_format(
                                                (float) $pedido['total'],
                                                0,
                                                ',',
                                                '.'
                                            ) ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>


                    <a
                        class="dashboard-link"
                        href="/origins_games/pedido/admin"
                    >
                        Ver todos los pedidos →
                    </a>

                <?php else: ?>

                    <div class="dashboard-empty">

                        No hay pedidos registrados.

                    </div>

                <?php endif; ?>

            </div>


            <!-- STOCK BAJO -->

            <div class="dashboard-panel">

                <h3>
                    Productos con stock bajo
                </h3>


                <?php if (!empty($productosStockBajo)): ?>

                    <?php foreach ($productosStockBajo as $producto): ?>

                        <div class="stock-item">

                            <span class="stock-name">

                                <?= htmlspecialchars(
                                    $producto['nombre']
                                ) ?>

                            </span>


                            <span class="stock-number">

                                <?= (int) $producto['stock'] ?>

                            </span>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="dashboard-empty">

                        No hay productos con stock bajo.

                    </div>

                <?php endif; ?>


                <a
                    class="dashboard-link"
                    href="/origins_games/producto/admin"
                >
                    Ir a inventario →
                </a>

            </div>

        </div>


        <!-- COLUMNA DERECHA -->

        <div>

            <!-- ESTADOS -->

            <div class="dashboard-panel">

                <h3>
                    Estado de pedidos
                </h3>


                <div class="dashboard-statuses">

                    <div class="dashboard-status status-pendiente">

                        <span>
                            Pendientes
                        </span>

                        <strong>
                            <?= (int) $pedidosEstado['pendiente'] ?>
                        </strong>

                    </div>


                    <div class="dashboard-status status-pagado">

                        <span>
                            Pagados
                        </span>

                        <strong>
                            <?= (int) $pedidosEstado['pagado'] ?>
                        </strong>

                    </div>


                    <div class="dashboard-status status-enviado">

                        <span>
                            Enviados
                        </span>

                        <strong>
                            <?= (int) $pedidosEstado['enviado'] ?>
                        </strong>

                    </div>


                    <div class="dashboard-status status-cancelado">

                        <span>
                            Cancelados
                        </span>

                        <strong>
                            <?= (int) $pedidosEstado['cancelado'] ?>
                        </strong>

                    </div>

                </div>

            </div>


            <!-- RESUMEN DEL SISTEMA -->

            <div class="dashboard-panel">

                <h3>
                    Resumen del sistema
                </h3>


                <div class="stock-item">

                    <span class="stock-name">
                        Productos registrados
                    </span>

                    <strong>
                        <?= (int) $resumen['total_productos'] ?>
                    </strong>

                </div>


                <div class="stock-item">

                    <span class="stock-name">
                        Stock bajo (1 - 3)
                    </span>

                    <strong>
                        <?= (int) $resumen['productos_stock_bajo'] ?>
                    </strong>

                </div>

            </div>

        </div>

    </div>

</div>