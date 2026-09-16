<?php
/*
 * La vista recibe:
 * - $pedidos
 * - $pageTitle
 *
 * El header y footer son cargados
 * desde PedidoController.php.
 */
?>

<style>
    .orders-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.55);
        max-width: 1000px;
        margin: 20px auto;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table th {
        text-align: left;
        color: var(--yellow-accent);
        padding: 14px 12px;
        border-bottom: 1px solid var(--border-card);
        font-size: 0.85rem;
    }

    .orders-table td {
        padding: 16px 12px;
        border-bottom: 1px solid var(--border-card);
        color: var(--text-secondary);
    }

    .orders-table tbody tr:hover td {
        background: rgba(6, 182, 212, 0.04);
    }

    .order-number {
        color: var(--text-primary);
        font-weight: 800;
    }

    .order-total {
        color: var(--cyan-accent) !important;
        font-weight: 800;
    }

    .status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 14px;
        font-size: 0.78rem;
        font-weight: 700;
        border: 1px solid;
    }

    .status-pendiente {
        color: #facc15;
        border-color: rgba(250, 204, 21, 0.4);
        background: rgba(250, 204, 21, 0.08);
    }

    .status-pagado {
        color: #34d399;
        border-color: rgba(52, 211, 153, 0.4);
        background: rgba(52, 211, 153, 0.08);
    }

    .status-enviado {
        color: #38bdf8;
        border-color: rgba(56, 189, 248, 0.4);
        background: rgba(56, 189, 248, 0.08);
    }

    .status-cancelado {
        color: #f87171;
        border-color: rgba(248, 113, 113, 0.4);
        background: rgba(248, 113, 113, 0.08);
    }

    .btn-detail {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 18px;
        border: 1px solid var(--cyan-accent);
        color: var(--cyan-accent);
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-detail:hover {
        background: rgba(6, 182, 212, 0.12);
        box-shadow: 0 0 12px var(--glow-cyan);
    }

    .empty-orders {
        text-align: center;
        padding: 50px 20px;
    }

    @media (max-width: 750px) {
        .orders-card {
            padding: 18px;
            overflow-x: auto;
        }

        .orders-table {
            min-width: 700px;
        }
    }
</style>

<h2 class="section-title">
    Mis Pedidos
</h2>

<div class="orders-card">

    <?php if (!empty($pedidos)): ?>

        <table class="orders-table">

            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($pedidos as $pedido): ?>

                    <?php
                    $estado = strtolower(
                        trim(
                            $pedido['estado'] ?? ''
                        )
                    );

                    /*
                     * Convertimos el estado en una clase CSS.
                     */
                    $estadoClase = 'status-' .
                        preg_replace(
                            '/[^a-z]/',
                            '',
                            $estado
                        );

                    /*
                     * Texto que se mostrará al usuario.
                     */
                    $estadoTexto = ucfirst($estado);

                    /*
                     * Fecha del pedido.
                     */
                    $fecha = '—';

                    if (!empty($pedido['fecha_pedido'])) {

                        $timestamp = strtotime(
                            $pedido['fecha_pedido']
                        );

                        if ($timestamp !== false) {

                            $fecha = date(
                                'd/m/Y H:i',
                                $timestamp
                            );
                        }
                    }
                    ?>

                    <tr>

                        <!-- Número del pedido -->
                        <td class="order-number">
                            #<?= (int) $pedido['id'] ?>
                        </td>

                        <!-- Fecha -->
                        <td>
                            <?= htmlspecialchars($fecha) ?>
                        </td>

                        <!-- Estado -->
                        <td>
                            <span
                                class="status <?= htmlspecialchars($estadoClase) ?>"
                            >
                                <?= htmlspecialchars($estadoTexto) ?>
                            </span>
                        </td>

                        <!-- Total -->
                        <td class="order-total">
                            $
                            <?= number_format(
                                (float) $pedido['total'],
                                2
                            ) ?>
                        </td>

                        <!-- Detalle -->
                        <td>
                            <a
                                href="/origins_games/mis-pedidos/detalle?id=<?= (int) $pedido['id'] ?>"
                                class="btn-detail"
                            >
                                Ver detalle
                            </a>
                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php else: ?>

        <div class="empty-orders">

            <div
                style="
                    font-size: 3rem;
                    margin-bottom: 15px;
                "
            >
                📦
            </div>

            <h3
                style="
                    margin-bottom: 10px;
                    color: var(--text-primary);
                "
            >
                Todavía no tienes pedidos
            </h3>

            <p
                style="
                    color: var(--text-secondary);
                    margin-bottom: 25px;
                "
            >
                Cuando realices una compra,
                aparecerá aquí.
            </p>

            <a
                href="/origins_games/producto"
                class="btn btn-primary"
                style="width: auto;"
            >
                Ir al Catálogo
            </a>

        </div>

    <?php endif; ?>

</div>