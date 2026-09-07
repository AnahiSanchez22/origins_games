<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h2 class="section-title" style="text-align: center; color: var(--yellow-accent); margin-bottom: 20px;">Catálogo Gamer</h2>

<div style="display: flex; gap: 12px; justify-content: center; margin-bottom: 30px; flex-wrap: wrap;">
    <a href="/origins_games/producto?categoria=todos" class="<?= ($cat ?? 'todos') === 'todos' ? 'btn-nav-solid' : 'btn-nav-outline' ?>">Todos</a>
    <a href="/origins_games/producto?categoria=accesorios" class="<?= ($cat ?? '') === 'accesorios' ? 'btn-nav-solid' : 'btn-nav-outline' ?>">Accesorios</a>
    <a href="/origins_games/producto?categoria=consolas" class="<?= ($cat ?? '') === 'consolas' ? 'btn-nav-solid' : 'btn-nav-outline' ?>">Consolas</a>
    <a href="/origins_games/producto?categoria=juegos" class="<?= ($cat ?? '') === 'juegos' ? 'btn-nav-solid' : 'btn-nav-outline' ?>">Juegos</a>
</div>

<div class="grid-products" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
    <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $p): ?>
            <div class="card-item" style="background: var(--bg-card); border: 1px solid var(--border-card); border-radius: 16px; padding: 20px; text-align: center; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <img src="/origins_games/public/uploads/<?= htmlspecialchars($p['imagen'] ?? 'default.jpg') ?>" 
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/200?text=Origins+Games';"
                         alt="<?= htmlspecialchars($p['nombre']) ?>" 
                         style="width: 100%; height: 160px; object-fit: cover; border-radius: 12px; margin-bottom: 12px;">
                    
                    <h3 style="color: var(--text-primary); font-size: 1.1rem; margin-bottom: 8px;"><?= htmlspecialchars($p['nombre'] ?? '') ?></h3>
                    <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 15px;">
                        <?= htmlspecialchars($p['descripcion'] ?? '') ?>
                    </p>
                </div>
                <div>
                    <div class="card-price" style="font-size: 1.2rem; font-weight: bold; color: var(--cyan-accent); margin-bottom: 12px;">
                        $<?= number_format($p['precio'] ?? 0, 2) ?>
                    </div>
                    
                    <!-- CORREGIDO: Ahora usa un formulario POST para enviar el producto_id -->
                    <form action="/origins_games/carrito/agregar" method="POST" style="margin: 0;">
                        <input type="hidden" name="producto_id" value="<?= $p['id'] ?>">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Añadir al carrito</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; grid-column: 1 / -1; color: var(--text-secondary);">No se encontraron productos en esta categoría.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

