<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    .pedidos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .pedidos-header h2 {
        margin: 0;
        color: var(--yellow-accent);
        font-size: 2rem;
    }

    .pedidos-header p {
        margin-top: 6px;
        color: var(--text-secondary);
        font-size: .9rem;
    }

    .pedidos-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,.35);
    }

    .pedidos-table-wrap {
        overflow-x: auto;
    }

    .pedidos-table {
        width: 100%;
        border-collapse: collapse;
    }

    .pedidos-table th {
        padding: 16px;
        text-align: left;
        color: var(--cyan-accent);
        font-size: .75rem;
        text-transform: uppercase;
        background: #060d1e;
        border-bottom: 1px solid var(--border-card);
    }

    .pedidos-table td {
        padding: 16px;
        color: var(--text-secondary);
        border-bottom: 1px solid rgba(255,255,255,.05);
        font-size: .85rem;
    }

    .pedidos-table tr:last-child td {
        border-bottom: none;
    }

    .pedido-id {
        color: #fff;
        font-weight: 800;
    }

    .pedido-cliente {
        color: #fff;
        font-weight: 600;
    }

    .pedido-total {
        color: var(--cyan-accent);
        font-weight: 800;
    }

    .estado-badge {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .estado-pendiente {
        color: #fbbf24;
        border: 1px solid rgba(251,191,36,.4);
        background: rgba(251,191,36,.08);
    }

    .estado-pagado {
        color: #34d399;
        border: 1px solid rgba(52,211,153,.4);
        background: rgba(52,211,153,.08);
    }

    .estado-enviado {
        color: #38bdf8;
        border: 1px solid rgba(56,189,248,.4);
        background: rgba(56,189,248,.08);
    }

    .estado-cancelado {
        color: #f87171;
        border: 1px solid rgba(248,113,113,.4);
        background: rgba(248,113,113,.08);
    }

    .btn-ver-pedido {
        display: inline-block;
        padding: 7px 13px;
        border-radius: 10px;
        border: 1px solid var(--cyan-accent);
        color: var(--cyan-accent) !important;
        font-size: .75rem;
        font-weight: 700;
    }

    .btn-ver-pedido:hover {
        background: rgba(6,182,212,.1);
        box-shadow: 0 0 10px var(--glow-cyan);
    }

    .sin-pedidos {
        padding: 40px;
        text-align: center;
        color: var(--text-secondary);
    }

    @media (max-width: 700px) {
        .pedidos-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>


<div class="pedidos-header">

    <div>

        <h2>Gestión de Pedidos</h2>

        <p>
            Consulta y administra los pedidos realizados en Origins Games.
        </p>

    </div>

</div>


<div class="pedidos-card">

    <?php if (!empty($pedidos)): ?>

        <div class="pedidos-table-wrap">

            <table class="pedidos-table">

                <thead>

                    <tr>

                        <th>Pedido</th>

                        <th>Cliente</th>

                        <th>Fecha</th>

                        <th>Total</th>

                        <th>Estado</th>

                        <th>Acción</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($pedidos as $pedido): ?>

                        <tr>

                            <td>
                                <span class="pedido-id">
                                    #<?= (int) $pedido['id'] ?>
                                </span>
                            </td>

                            <td>

                                <div class="pedido-cliente">
                                    <?= htmlspecialchars($pedido['cliente']) ?>
                                </div>

                                <div style="font-size:.72rem; margin-top:3px;">
                                    <?= htmlspecialchars($pedido['email']) ?>
                                </div>

                            </td>

                            <td>
                                <?= htmlspecialchars($pedido['fecha_pedido']) ?>
                            </td>

                            <td>

                                <span class="pedido-total">

                                    $
                                    <?= number_format(
                                        (float) $pedido['total'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>

                                </span>

                            </td>

                            <td>

                                <span class="estado-badge estado-<?= htmlspecialchars($pedido['estado']) ?>">

                                    <?= htmlspecialchars(
                                        ucfirst($pedido['estado'])
                                    ) ?>

                                </span>

                            </td>

                            <td>

                                <a
                                    class="btn-ver-pedido"
                                    href="/origins_games/pedido/ver?id=<?= (int) $pedido['id'] ?>"
                                >
                                    Ver pedido
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="sin-pedidos">
            No hay pedidos registrados.
        </div>

    <?php endif; ?>

</div>


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>