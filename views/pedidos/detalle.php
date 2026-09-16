<?php

$estado = strtolower(trim($pedido['estado'] ?? ''));

$estadoClase = 'status-' . preg_replace(
    '/[^a-z]/',
    '',
    $estado
);

$estadoTexto = ucfirst($estado);

/*
 * Posición del pedido dentro del proceso.
 */
$pasoActual = 0;

if ($estado === 'pendiente') {
    $pasoActual = 1;
} elseif ($estado === 'pagado') {
    $pasoActual = 2;
} elseif ($estado === 'enviado') {
    $pasoActual = 3;
}

?>

<style>
    .detail-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 20px;
        padding: 30px;
        max-width: 900px;
        margin: 20px auto;
        box-shadow: 0 12px 40px rgba(0, 0, 0, .55);
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding-bottom: 22px;
        border-bottom: 1px solid var(--border-card);
        margin-bottom: 20px;
    }

    .detail-header h3 {
        color: var(--text-primary);
        font-size: 1.4rem;
        margin: 0;
    }

    .detail-date {
        color: var(--text-secondary);
        font-size: .85rem;
        margin-top: 6px;
    }

    .status {
        display: inline-block;
        padding: 7px 14px;
        border-radius: 14px;
        font-size: .8rem;
        font-weight: 700;
        border: 1px solid;
    }

    .status-pendiente {
        color: #facc15;
        border-color: rgba(250, 204, 21, .4);
        background: rgba(250, 204, 21, .08);
    }

    .status-pagado {
        color: #34d399;
        border-color: rgba(52, 211, 153, .4);
        background: rgba(52, 211, 153, .08);
    }

    .status-enviado {
        color: #38bdf8;
        border-color: rgba(56, 189, 248, .4);
        background: rgba(56, 189, 248, .08);
    }

    .status-cancelado {
        color: #f87171;
        border-color: rgba(248, 113, 113, .4);
        background: rgba(248, 113, 113, .08);
    }

    .tracking {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        margin: 30px 0;
    }

    .tracking-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 100px;
        color: var(--text-secondary);
        font-size: .78rem;
        text-align: center;
    }

    .tracking-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid var(--border-card);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        background: var(--bg-dark);
    }

    .tracking-step.active {
        color: var(--cyan-accent);
    }

    .tracking-step.active .tracking-circle {
        border-color: var(--cyan-accent);
        color: var(--cyan-accent);
        box-shadow: 0 0 12px var(--glow-cyan);
    }

    .tracking-line {
        width: 70px;
        height: 2px;
        background: var(--border-card);
        margin-bottom: 27px;
    }

    .tracking-line.active {
        background: var(--cyan-accent);
    }

    .cancelled-message {
        text-align: center;
        padding: 15px;
        margin-bottom: 25px;
        color: #f87171;
        background: rgba(248, 113, 113, .06);
        border: 1px solid rgba(248, 113, 113, .25);
        border-radius: 12px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .order-detail-table {
        width: 100%;
        border-collapse: collapse;
    }

    .order-detail-table th {
        color: var(--yellow-accent);
        text-align: left;
        padding: 12px;
        border-bottom: 1px solid var(--border-card);
        font-size: .85rem;
    }

    .order-detail-table td {
        padding: 16px 12px;
        border-bottom: 1px solid var(--border-card);
        color: var(--text-secondary);
    }

    .product-name {
        color: var(--text-primary);
        font-weight: 700;
    }

    .price {
        color: var(--cyan-accent) !important;
        font-weight: 700;
    }

    .total-box {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 15px;
        padding-top: 22px;
        font-size: 1.25rem;
    }

    .total-box strong {
        color: var(--cyan-accent);
        font-size: 1.45rem;
    }

    .back-link {
        display: inline-block;
        margin-top: 25px;
        color: var(--text-secondary);
        border: 1px solid var(--border-card);
        padding: 9px 18px;
        border-radius: 20px;
        transition: all .2s ease;
    }

    .back-link:hover {
        color: var(--text-primary);
        border-color: var(--cyan-accent);
    }

    @media (max-width: 700px) {

        .detail-card {
            padding: 18px;
            margin: 15px 10px;
        }

        .detail-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .tracking {
            overflow-x: auto;
            justify-content: flex-start;
            padding: 10px 5px;
        }

        .tracking-step {
            min-width: 85px;
        }

        .tracking-line {
            width: 45px;
        }

        .order-detail-table {
            min-width: 650px;
        }

        .total-box {
            justify-content: space-between;
        }
    }
</style>


<h2 class="section-title">
    Detalle del Pedido #<?= (int)$pedido['id'] ?>
</h2>


<div class="detail-card">

    <div class="detail-header">

        <div>

            <h3>
                Pedido #<?= (int)$pedido['id'] ?>
            </h3>

            <div class="detail-date">

                Fecha:

                <?php
                if (!empty($pedido['fecha_pedido'])) {

                    $fecha = strtotime($pedido['fecha_pedido']);

                    if ($fecha !== false) {
                        echo htmlspecialchars(
                            date('d/m/Y H:i', $fecha)
                        );
                    } else {
                        echo '—';
                    }

                } else {
                    echo '—';
                }
                ?>

            </div>

        </div>


        <span class="status <?= htmlspecialchars($estadoClase) ?>">
            <?= htmlspecialchars($estadoTexto) ?>
        </span>

    </div>


    <?php if ($estado !== 'cancelado'): ?>

        <div class="tracking">

            <div
                class="tracking-step <?= $pasoActual >= 1 ? 'active' : '' ?>"
            >

                <div class="tracking-circle">
                    1
                </div>

                Pendiente

            </div>


            <div
                class="tracking-line <?= $pasoActual >= 2 ? 'active' : '' ?>"
            ></div>


            <div
                class="tracking-step <?= $pasoActual >= 2 ? 'active' : '' ?>"
            >

                <div class="tracking-circle">
                    2
                </div>

                Pagado

            </div>


            <div
                class="tracking-line <?= $pasoActual >= 3 ? 'active' : '' ?>"
            ></div>


            <div
                class="tracking-step <?= $pasoActual >= 3 ? 'active' : '' ?>"
            >

                <div class="tracking-circle">
                    3
                </div>

                Enviado

            </div>

        </div>

    <?php else: ?>

        <div class="cancelled-message">
            Este pedido fue cancelado.
        </div>

    <?php endif; ?>


    <div class="table-wrapper">

        <table class="order-detail-table">

            <thead>

                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Subtotal</th>
                </tr>

            </thead>


            <tbody>

                <?php if (!empty($detalles)): ?>

                    <?php foreach ($detalles as $detalle): ?>

                        <?php
                        $cantidad = (int)($detalle['cantidad'] ?? 0);

                        $precioUnitario = (float)(
                            $detalle['precio_unitario'] ?? 0
                        );

                        $subtotal = $precioUnitario * $cantidad;

                        $nombreProducto = $detalle['producto']
                            ?? 'Producto no disponible';
                        ?>

                        <tr>

                            <td class="product-name">
                                <?= htmlspecialchars($nombreProducto) ?>
                            </td>


                            <td>
                                <?= $cantidad ?>
                            </td>


                            <td class="price">
                                $
                                <?= number_format(
                                    $precioUnitario,
                                    2
                                ) ?>
                            </td>


                            <td class="price">
                                $
                                <?= number_format(
                                    $subtotal,
                                    2
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="4"
                            style="
                                text-align:center;
                                padding:30px;
                            "
                        >
                            No hay productos registrados en este pedido.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>


    <div class="total-box">

        <span>
            Total:
        </span>

        <strong>
            $
            <?= number_format(
                (float)($pedido['total'] ?? 0),
                2
            ) ?>
        </strong>

    </div>


    <a
        href="/origins_games/mis-pedidos"
        class="back-link"
    >
        ← Volver a Mis Pedidos
    </a>

</div>