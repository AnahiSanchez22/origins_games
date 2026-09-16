<?php
/*
 * La cabecera y el pie de página
 * ya son cargados por PedidoController.
 */
?>

<style>
    .pedido-detalle-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .pedido-detalle-header h2 {
        color: var(--yellow-accent);
        margin: 0;
    }

    .pedido-detalle-header p {
        color: var(--text-secondary);
        margin-top: 6px;
    }

    .btn-volver {
        color: var(--cyan-accent) !important;
        border: 1px solid var(--cyan-accent);
        padding: 9px 15px;
        border-radius: 12px;
        font-size: .8rem;
        font-weight: 700;
        text-decoration: none;
    }

    .btn-volver:hover {
        background: rgba(0, 229, 255, .08);
    }

    .pedido-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
    }

    .pedido-panel {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .pedido-panel h3 {
        margin: 0;
        padding: 17px 20px;
        color: #fff;
        font-size: 1rem;
        border-bottom: 1px solid var(--border-card);
    }

    .pedido-info {
        padding: 20px;
    }

    .pedido-info-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,.05);
    }

    .pedido-info-row:last-child {
        border-bottom: none;
    }

    .pedido-info-row span {
        color: var(--text-secondary);
        font-size: .82rem;
    }

    .pedido-info-row strong {
        color: #fff;
        text-align: right;
        font-size: .82rem;
        max-width: 65%;
        word-break: break-word;
    }

    .pedido-producto {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 15px 20px;
        border-bottom: 1px solid rgba(255,255,255,.05);
    }

    .pedido-producto:last-child {
        border-bottom: none;
    }

    .pedido-producto img {
        width: 58px;
        height: 58px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid var(--border-card);
        background: #060d1e;
    }

    .producto-info {
        flex: 1;
    }

    .producto-info strong {
        color: #fff;
        display: block;
        margin-bottom: 5px;
    }

    .producto-info span {
        color: var(--text-secondary);
        font-size: .78rem;
    }

    .producto-subtotal {
        color: var(--cyan-accent);
        font-weight: 700;
        font-size: .85rem;
    }

    .pedido-total-final {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        border-top: 1px solid var(--border-card);
    }

    .pedido-total-final span {
        color: var(--text-secondary);
    }

    .pedido-total-final strong {
        color: var(--yellow-accent);
        font-size: 1.25rem;
    }

    .estado-form {
        padding: 20px;
    }

    .estado-form label {
        display: block;
        color: var(--text-secondary);
        font-size: .8rem;
        margin-bottom: 8px;
    }

    .estado-form select {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        background: #060d1e;
        border: 1px solid var(--border-card);
        color: #fff;
        margin-bottom: 12px;
    }

    .estado-form button {
        width: 100%;
        border: none;
        cursor: pointer;
    }

    @media (max-width: 850px) {
        .pedido-grid {
            grid-template-columns: 1fr;
        }

        .pedido-detalle-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>


<div class="pedido-detalle-header">

    <div>

        <h2>
            Pedido #<?= (int) $pedido['id'] ?>
        </h2>

        <p>
            <?= htmlspecialchars(
                $pedido['fecha_pedido'] ?? ''
            ) ?>
        </p>

    </div>

    <a
        href="/origins_games/pedidos"
        class="btn-volver"
    >
        ← Volver a pedidos
    </a>

</div>


<div class="pedido-grid">

    <!-- PRODUCTOS -->

    <div>

        <div class="pedido-panel">

            <h3>
                Productos del pedido
            </h3>


            <?php if (!empty($detalles)): ?>

                <?php foreach ($detalles as $detalle): ?>

                    <?php
                    $nombreProducto =
                        $detalle['producto']
                        ?? 'Producto';

                    $imagen =
                        $detalle['imagen']
                        ?? 'default.jpg';

                    $cantidad =
                        (int) ($detalle['cantidad'] ?? 0);

                    $precio =
                        (float) ($detalle['precio_unitario'] ?? 0);

                    $subtotal =
                        $precio * $cantidad;
                    ?>


                    <div class="pedido-producto">

                        <img
                            src="/origins_games/public/uploads/<?= htmlspecialchars($imagen) ?>"
                            onerror="this.onerror=null; this.src='/origins_games/public/uploads/default.jpg';"
                            alt="Producto"
                        >


                        <div class="producto-info">

                            <strong>
                                <?= htmlspecialchars($nombreProducto) ?>
                            </strong>

                            <span>
                                Cantidad:
                                <?= $cantidad ?>
                                ×
                                $
                                <?= number_format(
                                    $precio,
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                            </span>

                        </div>


                        <div class="producto-subtotal">

                            $
                            <?= number_format(
                                $subtotal,
                                0,
                                ',',
                                '.'
                            ) ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div style="padding:25px;color:var(--text-secondary);">
                    Este pedido no tiene productos registrados.
                </div>

            <?php endif; ?>


            <div class="pedido-total-final">

                <span>
                    Total
                </span>

                <strong>

                    $
                    <?= number_format(
                        (float) ($pedido['total'] ?? 0),
                        0,
                        ',',
                        '.'
                    ) ?>

                </strong>

            </div>

        </div>

    </div>


    <!-- INFORMACIÓN -->

    <div>

        <div class="pedido-panel">

            <h3>
                Información del cliente
            </h3>


            <div class="pedido-info">

                <div class="pedido-info-row">

                    <span>
                        Nombre
                    </span>

                    <strong>
                        <?= htmlspecialchars(
                            $pedido['cliente'] ?? 'No registrado'
                        ) ?>
                    </strong>

                </div>


                <div class="pedido-info-row">

                    <span>
                        Email
                    </span>

                    <strong>
                        <?= htmlspecialchars(
                            $pedido['email'] ?? 'No registrado'
                        ) ?>
                    </strong>

                </div>


                <div class="pedido-info-row">

                    <span>
                        Teléfono
                    </span>

                    <strong>
                        <?= htmlspecialchars(
                            $pedido['telefono'] ?? 'No registrado'
                        ) ?>
                    </strong>

                </div>


                <div class="pedido-info-row">

                    <span>
                        Dirección
                    </span>

                    <strong>
                        <?= htmlspecialchars(
                            $pedido['direccion'] ?? 'No registrada'
                        ) ?>
                    </strong>

                </div>

            </div>

        </div>


        <!-- ESTADO -->

        <div class="pedido-panel">

            <h3>
                Estado del pedido
            </h3>


            <div class="estado-form">

                <form
                    action="/origins_games/pedido/cambiarEstado"
                    method="POST"
                >

                    <input
                        type="hidden"
                        name="pedido_id"
                        value="<?= (int) $pedido['id'] ?>"
                    >


                    <label>
                        Estado actual
                    </label>


                    <select name="estado">

                        <option
                            value="pendiente"
                            <?= ($pedido['estado'] ?? '') === 'pendiente'
                                ? 'selected'
                                : '' ?>
                        >
                            Pendiente
                        </option>


                        <option
                            value="pagado"
                            <?= ($pedido['estado'] ?? '') === 'pagado'
                                ? 'selected'
                                : '' ?>
                        >
                            Pagado
                        </option>


                        <option
                            value="enviado"
                            <?= ($pedido['estado'] ?? '') === 'enviado'
                                ? 'selected'
                                : '' ?>
                        >
                            Enviado
                        </option>


                        <option
                            value="cancelado"
                            <?= ($pedido['estado'] ?? '') === 'cancelado'
                                ? 'selected'
                                : '' ?>
                        >
                            Cancelado
                        </option>

                    </select>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Actualizar Estado
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>