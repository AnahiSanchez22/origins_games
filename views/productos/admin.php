<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php

$editarProd = null;

if (isset($_GET['edit'])) {

    $idEditar = (int) $_GET['edit'];

    $stmtEdit = $this->db->prepare(
        "SELECT *
         FROM productos
         WHERE id = ?"
    );

    $stmtEdit->execute([$idEditar]);

    $editarProd = $stmtEdit->fetch(PDO::FETCH_ASSOC);
}

?>


<style>

    .inventario-title {
        color: var(--yellow-accent);
        font-size: 2rem;
        margin-bottom: 8px;
    }

    .inventario-subtitle {
        color: var(--text-secondary);
        margin-bottom: 25px;
        font-size: .9rem;
    }

    .inventario-layout {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 24px;
        align-items: start;
    }

    .inventario-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,.35);
    }

    .inventario-card h3 {
        color: var(--cyan-accent);
        margin-bottom: 20px;
    }

    .stock-input {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stock-input input {
        flex: 1;
    }

    .stock-help {
        color: var(--text-secondary);
        font-size: .72rem;
        margin-top: 6px;
    }

    .productos-lista {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .producto-admin-item {
        background: #060d1e;
        border: 1px solid var(--border-card);
        border-radius: 14px;
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .producto-admin-info {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
    }

    .producto-admin-info img {
        width: 58px;
        height: 58px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid var(--border-card);
        flex-shrink: 0;
    }

    .producto-admin-name {
        color: #fff;
        font-weight: 700;
        font-size: .9rem;
    }

    .producto-admin-price {
        color: var(--cyan-accent);
        font-size: .78rem;
        margin-top: 4px;
    }

    .stock-badge {
        display: inline-block;
        min-width: 55px;
        text-align: center;
        padding: 6px 10px;
        border-radius: 10px;
        font-size: .72rem;
        font-weight: 800;
        margin-top: 5px;
    }

    .stock-ok {
        color: #34d399;
        border: 1px solid rgba(52,211,153,.4);
        background: rgba(52,211,153,.08);
    }

    .stock-low {
        color: #fbbf24;
        border: 1px solid rgba(251,191,36,.4);
        background: rgba(251,191,36,.08);
    }

    .stock-zero {
        color: #f87171;
        border: 1px solid rgba(248,113,113,.4);
        background: rgba(248,113,113,.08);
    }

    .producto-actions {
        display: flex;
        gap: 7px;
        flex-shrink: 0;
    }

    .producto-actions a {
        padding: 7px 11px;
        border-radius: 9px;
        font-size: .72rem;
        font-weight: 700;
    }

    .btn-editar-producto {
        color: var(--yellow-accent) !important;
        border: 1px solid var(--yellow-accent);
    }

    .btn-eliminar-producto {
        color: #f87171 !important;
        border: 1px solid #f87171;
    }

    @media (max-width: 900px) {

        .inventario-layout {
            grid-template-columns: 1fr;
        }

    }

    @media (max-width: 600px) {

        .producto-admin-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .producto-actions {
            width: 100%;
        }

    }

</style>


<h2 class="inventario-title">
    Gestión de Inventario
</h2>

<p class="inventario-subtitle">
    Administra productos, precios y existencias disponibles.
</p>


<div class="inventario-layout">


    <!-- FORMULARIO -->

    <div class="inventario-card">

        <h3>

            <?= $editarProd
                ? 'Editar Producto'
                : 'Agregar Producto' ?>

        </h3>


        <form
            action="/origins_games/producto/guardar"
            method="POST"
            enctype="multipart/form-data"
        >

            <?php if ($editarProd): ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $editarProd['id'] ?>"
                >

            <?php endif; ?>


            <div class="form-group">

                <label>
                    Nombre del Producto *
                </label>

                <input
                    type="text"
                    name="nombre"
                    required
                    value="<?= htmlspecialchars(
                        $editarProd['nombre'] ?? ''
                    ) ?>"
                    placeholder="Ej. Control DualSense"
                >

            </div>


            <div class="form-group">

                <label>
                    Categoría
                </label>

                <select name="categoria_id">

                    <option
                        value="1"
                        <?= ($editarProd['categoria_id'] ?? '') == 1
                            ? 'selected'
                            : '' ?>
                    >
                        Accesorios
                    </option>

                    <option
                        value="2"
                        <?= ($editarProd['categoria_id'] ?? '') == 2
                            ? 'selected'
                            : '' ?>
                    >
                        Consolas
                    </option>

                    <option
                        value="3"
                        <?= ($editarProd['categoria_id'] ?? '') == 3
                            ? 'selected'
                            : '' ?>
                    >
                        Juegos
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Precio ($) *
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="precio"
                    required
                    value="<?= htmlspecialchars(
                        $editarProd['precio'] ?? ''
                    ) ?>"
                    placeholder="59900"
                >

            </div>


            <!-- STOCK -->

            <div class="form-group">

                <label>
                    Stock disponible *
                </label>

                <div class="stock-input">

                    <input
                        type="number"
                        name="stock"
                        min="0"
                        step="1"
                        required
                        value="<?= htmlspecialchars(
                            $editarProd['stock'] ?? '0'
                        ) ?>"
                        placeholder="0"
                    >

                </div>

                <div class="stock-help">
                    Cantidad de unidades disponibles para vender.
                </div>

            </div>


            <!-- IMAGEN -->

            <div class="form-group">

                <label>
                    Imagen del Producto
                </label>


                <?php if (
                    $editarProd &&
                    !empty($editarProd['imagen'])
                ): ?>

                    <div style="margin-bottom:10px;">

                        <img
                            src="/origins_games/public/uploads/<?= htmlspecialchars($editarProd['imagen']) ?>"
                            alt="Vista previa"
                            style="
                                width:70px;
                                height:70px;
                                object-fit:cover;
                                border-radius:10px;
                                border:1px solid var(--border-card);
                            "
                        >

                    </div>

                <?php endif; ?>


                <input
                    type="file"
                    name="imagen"
                    accept="image/*"
                >

            </div>


            <!-- DESCRIPCIÓN -->

            <div class="form-group">

                <label>
                    Descripción
                </label>

                <textarea
                    name="descripcion"
                    rows="3"
                    placeholder="Detalles del producto..."
                ><?= htmlspecialchars(
                    $editarProd['descripcion'] ?? ''
                ) ?></textarea>

            </div>


            <button
                type="submit"
                class="btn btn-primary"
                style="width:100%;"
            >

                <?= $editarProd
                    ? 'Actualizar Producto'
                    : 'Guardar Producto' ?>

            </button>


            <?php if ($editarProd): ?>

                <a
                    href="/origins_games/producto/admin"
                    style="
                        display:block;
                        text-align:center;
                        margin-top:12px;
                        color:var(--text-secondary);
                    "
                >
                    Cancelar edición
                </a>

            <?php endif; ?>

        </form>

    </div>


    <!-- LISTA -->

    <div class="inventario-card">

        <h3 style="color:var(--text-primary);">

            Productos registrados

        </h3>


        <?php if (!empty($productos)): ?>

            <div class="productos-lista">

                <?php foreach ($productos as $p): ?>

                    <?php

                    $stock = (int) $p['stock'];

                    if ($stock === 0) {
                        $stockClass = 'stock-zero';
                        $stockTexto = 'Sin stock';
                    } elseif ($stock <= 3) {
                        $stockClass = 'stock-low';
                        $stockTexto = 'Stock bajo';
                    } else {
                        $stockClass = 'stock-ok';
                        $stockTexto = 'Disponible';
                    }

                    ?>


                    <div class="producto-admin-item">


                        <div class="producto-admin-info">

                            <img
                                src="/origins_games/public/uploads/<?= htmlspecialchars(
                                    $p['imagen'] ?? 'default.jpg'
                                ) ?>"
                                alt="Producto"
                                onerror="this.onerror=null; this.src='/origins_games/public/uploads/default.jpg';"
                            >


                            <div>

                                <div class="producto-admin-name">

                                    <?= htmlspecialchars(
                                        $p['nombre']
                                    ) ?>

                                </div>


                                <div class="producto-admin-price">

                                    $
                                    <?= number_format(
                                        (float) $p['precio'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>

                                </div>


                                <span class="stock-badge <?= $stockClass ?>">

                                    <?= $stockTexto ?>
                                    ·
                                    <?= $stock ?>

                                </span>

                            </div>

                        </div>


                        <div class="producto-actions">

                            <a
                                href="/origins_games/producto/admin?edit=<?= (int) $p['id'] ?>"
                                class="btn-editar-producto"
                            >
                                Editar
                            </a>


                            <a
                                href="/origins_games/producto/eliminar?id=<?= (int) $p['id'] ?>"
                                class="btn-eliminar-producto"
                                onclick="return confirm('¿Seguro que deseas eliminar este producto?')"
                            >
                                Eliminar
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <p style="color:var(--text-secondary);">
                No hay productos registrados.
            </p>

        <?php endif; ?>

    </div>

</div>


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>