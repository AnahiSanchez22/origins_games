<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    .cart-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.6);
        max-width: 900px;
        margin: 20px auto;
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }

    .cart-table th {
        color: var(--yellow-accent);
        text-align: left;
        padding: 12px;
        border-bottom: 1px solid var(--border-card);
        font-size: 0.95rem;
    }

    .cart-table td {
        padding: 16px 12px;
        border-bottom: 1px solid var(--border-card);
        vertical-align: middle;
    }

    .input-cantidad {
        background: #060d1e;
        color: var(--text-primary);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 6px 10px;
        width: 60px;
        text-align: center;
        font-family: inherit;
    }

    .btn-delete {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid #ef4444;
        border-radius: 12px;
        padding: 6px 14px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .btn-delete:hover {
        background: #ef4444;
        color: #fff;
    }

    .btn-secondary {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border-card);
        border-radius: 20px;
        padding: 8px 18px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .btn-secondary:hover {
        color: var(--text-primary);
        border-color: var(--cyan-accent);
    }
</style>

<h2 class="section-title">Carrito de Compras</h2>

<div class="cart-card">
    <?php if (!empty($_SESSION['carrito'])): ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_general = 0;
                foreach ($_SESSION['carrito'] as $id => $item): 
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total_general += $subtotal;
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($item['nombre']) ?></strong></td>
                    <td style="color: var(--cyan-accent); font-weight: 700;">$<?= number_format($item['precio'], 2) ?></td>
                    <td>
                        <form action="/origins_games/carrito/actualizar" method="POST" style="margin: 0;">
                            <input type="hidden" name="producto_id" value="<?= $id ?>">
                            <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>" min="1" class="input-cantidad" onchange="this.form.submit()">
                        </form>
                    </td>
                    <td style="color: var(--cyan-accent); font-weight: 700;">$<?= number_format($subtotal, 2) ?></td>
                    <td>
                        <form action="/origins_games/carrito/eliminar" method="POST" style="margin: 0;">
                            <input type="hidden" name="producto_id" value="<?= $id ?>">
                            <button type="submit" class="btn-delete">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-card); padding-top: 20px;">
            <div>
                <a href="/origins_games/producto" class="btn-secondary">← Volver al Catálogo</a>
                <form action="/origins_games/carrito/vaciar" method="POST" style="display: inline-block; margin-left: 10px;">
                    <button type="submit" class="btn-secondary" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.4);">Vaciar Carrito</button>
                </form>
            </div>

            <div style="text-align: right;">
                <div style="font-size: 1.4rem; margin-bottom: 16px;">
                    Total: <span style="color: var(--cyan-accent); font-weight: 800;">$<?= number_format($total_general, 2) ?></span>
                </div>
                <form action="/origins_games/carrito/pagar" method="POST" style="margin: 0;">
                    <button type="submit" class="btn btn-primary" style="width: auto; padding: 12px 36px;">Proceder al Pago</button>
                </form>
            </div>
        </div>

    <?php else: ?>
        <div style="text-align: center; padding: 40px 20px;">
            <p style="color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 24px;">Tu carrito está vacío.</p>
            <a href="/origins_games/producto" class="btn btn-primary" style="width: auto;">Ver Productos</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>