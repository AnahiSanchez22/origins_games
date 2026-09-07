<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$editarProd = null;
if (isset($_GET['edit'])) {
    $stmtEdit = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
    $stmtEdit->execute([$_GET['edit']]);
    $editarProd = $stmtEdit->fetch(PDO::FETCH_ASSOC);
}
?>

<h2 style="font-size: 2rem; color: var(--yellow-accent); margin-bottom: 24px;">Gestión de Inventario</h2>

<div class="citas-layout" style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 24px;">
    <!-- Formulario Crear/Editar -->
    <div class="form-card" style="margin: 0; max-width: 100%;">
        <h3 style="font-size: 1.4rem; margin-bottom: 20px; color: var(--cyan-accent);">
            <?= $editarProd ? 'Editar Producto' : 'Agregar Producto' ?>
        </h3>
        
        <form action="/origins_games/producto/guardar" method="POST" enctype="multipart/form-data">
            <?php if ($editarProd): ?>
                <input type="hidden" name="id" value="<?= $editarProd['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Nombre del Producto *:</label>
                <input type="text" name="nombre" required value="<?= htmlspecialchars($editarProd['nombre'] ?? '') ?>" placeholder="Ej. Control DualSense">
            </div>

            <div class="form-group">
                <label>Categoría:</label>
                <select name="categoria_id">
                    <option value="1" <?= ($editarProd['categoria_id'] ?? '') == 1 ? 'selected' : '' ?>>Accesorios</option>
                    <option value="2" <?= ($editarProd['categoria_id'] ?? '') == 2 ? 'selected' : '' ?>>Consolas</option>
                    <option value="3" <?= ($editarProd['categoria_id'] ?? '') == 3 ? 'selected' : '' ?>>Juegos</option>
                </select>
            </div>

            <div class="form-group">
                <label>Precio ($) *:</label>
                <input type="number" step="0.01" name="precio" required value="<?= $editarProd['precio'] ?? '' ?>" placeholder="59.99">
            </div>

            <div class="form-group">
                <label>Imagen del Producto:</label>
                <?php if ($editarProd && !empty($editarProd['imagen'])): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="/origins_games/public/uploads/<?= htmlspecialchars($editarProd['imagen']) ?>" 
                             alt="Vista previa" 
                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-card);">
                    </div>
                <?php endif; ?>
                <input type="file" name="imagen" accept="image/*">
            </div>

            <div class="form-group">
                <label>Descripción:</label>
                <textarea name="descripcion" rows="3" placeholder="Detalles del producto..."><?= htmlspecialchars($editarProd['descripcion'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <?= $editarProd ? 'Actualizar Producto' : 'Guardar Producto' ?>
            </button>

            <?php if ($editarProd): ?>
                <a href="/origins_games/producto/admin" style="display: block; text-align: center; margin-top: 10px; color: var(--text-secondary); text-decoration: none;">Cancelar edición</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Lista de Productos -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-card); border-radius: 20px; padding: 24px;">
        <h3 style="font-size: 1.4rem; color: var(--text-primary); margin-bottom: 20px;">Lista de Productos</h3>
        
        <?php if (!empty($productos)): ?>
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <?php foreach ($productos as $p): ?>
                    <div style="background: #060d1e; padding: 16px; border-radius: 12px; border: 1px solid var(--border-card); display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="/origins_games/public/uploads/<?= htmlspecialchars($p['imagen'] ?? 'default.jpg') ?>" 
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/50?text=🎮';"
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            
                            <div>
                                <strong style="color: var(--text-primary); font-size: 1.05rem;"><?= htmlspecialchars($p['nombre']) ?></strong>
                                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 4px;">
                                    Precio: <strong style="color: var(--cyan-accent);">$<?= number_format($p['precio'], 2) ?></strong>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 8px;">
                            <a href="/origins_games/producto/admin?edit=<?= $p['id'] ?>" 
                               style="color: var(--yellow-accent); font-weight: 600; font-size: 0.85rem; padding: 6px 12px; border: 1px solid var(--yellow-accent); border-radius: 8px; text-decoration: none;">
                               Editar
                            </a>
                            <a href="/origins_games/producto/eliminar?id=<?= $p['id'] ?>" 
                               onclick="return confirm('¿Seguro que deseas eliminar este producto?')" 
                               style="color: #ef4444; font-weight: 600; font-size: 0.85rem; padding: 6px 12px; border: 1px solid #ef4444; border-radius: 8px; text-decoration: none;">
                               Eliminar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: var(--text-secondary);">No hay productos registrados.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>