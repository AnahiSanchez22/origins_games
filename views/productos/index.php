<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>

    .stock-info {
        margin-bottom: 12px;
        font-size: .8rem;
        font-weight: 700;
    }

    .stock-disponible {
        color: #34d399;
    }

    .stock-bajo {
        color: #fbbf24;
    }

    .stock-agotado {
        color: #f87171;
    }

    /* BOTÓN DE FAVORITOS */

    .favorite-form {
        margin: 0 0 10px;
    }

    .favorite-button {
        width: 46px;
        height: 46px;

        padding: 0;

        border-radius: 50%;

        font-size: 1.7rem;
        line-height: 1;

        font-family: Arial, Helvetica, sans-serif;

        cursor: pointer;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        transition:
            transform .2s ease,
            background .2s ease,
            border-color .2s ease,
            box-shadow .2s ease,
            color .2s ease;
    }

    .favorite-button-add {
        border: 1px solid var(--yellow-accent);

        background: transparent;

        color: var(--yellow-accent);
    }

    .favorite-button-add:hover {
        background: rgba(250, 204, 21, .10);

        color: var(--yellow-hover);

        border-color: var(--yellow-hover);

        box-shadow:
            0 0 14px rgba(250, 204, 21, .25);

        transform: scale(1.08);
    }

    .favorite-button-remove {
        border: 1px solid #f43f5e;

        background: rgba(244, 63, 94, .10);

        color: #f43f5e;
    }

    .favorite-button-remove:hover {
        background: rgba(244, 63, 94, .18);

        color: #fb7185;

        border-color: #fb7185;

        box-shadow:
            0 0 14px rgba(244, 63, 94, .25);

        transform: scale(1.08);
    }

    /* FILTROS DEL CATÁLOGO */

    .catalog-filters {
        max-width: 850px;
        margin: 0 auto 25px;
    }

    .catalog-search {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
    }

    .catalog-search input {
        width: 100%;
        max-width: 560px;
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        color: var(--text-primary);
        border-radius: 10px;
        padding: 12px 15px;
        font-size: .9rem;
        outline: none;
    }

    .catalog-search input::placeholder {
        color: var(--text-secondary);
    }

    .catalog-search input:focus {
        border-color: var(--cyan-accent);
    }

    .catalog-search button {
        border: 1px solid var(--cyan-accent);
        background: transparent;
        color: var(--cyan-accent);
        border-radius: 10px;
        padding: 11px 18px;
        font-size: .9rem;
        font-weight: 700;
        cursor: pointer;
        transition: .2s ease;
        white-space: nowrap;
    }

    .catalog-search button:hover {
        background: var(--cyan-accent);
        color: #06111c;
    }

    .catalog-order-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-bottom: 22px;
    }

    .catalog-order-label {
        color: var(--text-secondary);
        font-size: .85rem;
    }

    .catalog-order {
        background: transparent;
        border: 1px solid var(--border-card);
        color: var(--text-primary);
        border-radius: 9px;
        padding: 9px 12px;
        font-size: .85rem;
        outline: none;
        cursor: pointer;
    }

    .catalog-order:focus {
        border-color: var(--yellow-accent);
    }

    .catalog-order option {
        background: var(--bg-card);
        color: var(--text-primary);
    }

    .catalog-results {
        text-align: center;
        color: var(--text-secondary);
        font-size: .85rem;
        margin-bottom: 22px;
    }

    /* CATEGORÍAS */

    .catalog-categories {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    @media (max-width: 600px) {

        .catalog-search {
            flex-direction: column;
            align-items: stretch;
        }

        .catalog-search input {
            max-width: none;
        }

        .catalog-search button {
            width: 100%;
        }

        .catalog-order-wrapper {
            flex-direction: column;
        }

    }

</style>


<h2
    class="section-title"
    style="
        text-align: center;
        color: var(--yellow-accent);
        margin-bottom: 20px;
    "
>
    Catálogo
</h2>


<!-- BUSCADOR -->

<div class="catalog-filters">

    <form
        action="/origins_games/producto"
        method="GET"
        class="catalog-search"
    >

        <input
            type="hidden"
            name="categoria"
            value="<?= htmlspecialchars($cat ?? 'todos') ?>"
        >

        <input
            type="hidden"
            name="orden"
            value="<?= htmlspecialchars($orden ?? 'recientes') ?>"
        >

        <input
            type="text"
            name="busqueda"
            value="<?= htmlspecialchars($busqueda ?? '') ?>"
            placeholder="Buscar producto..."
        >

        <button type="submit">
            Buscar
        </button>

    </form>


    <!-- ORDENAMIENTO -->

    <form
        action="/origins_games/producto"
        method="GET"
        class="catalog-order-wrapper"
    >

        <input
            type="hidden"
            name="categoria"
            value="<?= htmlspecialchars($cat ?? 'todos') ?>"
        >

        <input
            type="hidden"
            name="busqueda"
            value="<?= htmlspecialchars($busqueda ?? '') ?>"
        >

        <label
            for="orden"
            class="catalog-order-label"
        >
            Ordenar por:
        </label>

        <select
            name="orden"
            id="orden"
            class="catalog-order"
            onchange="this.form.submit()"
        >

            <option
                value="recientes"
                <?= ($orden ?? 'recientes') === 'recientes'
                    ? 'selected'
                    : '' ?>
            >
                Más recientes
            </option>

            <option
                value="precio_asc"
                <?= ($orden ?? '') === 'precio_asc'
                    ? 'selected'
                    : '' ?>
            >
                Precio: menor a mayor
            </option>

            <option
                value="precio_desc"
                <?= ($orden ?? '') === 'precio_desc'
                    ? 'selected'
                    : '' ?>
            >
                Precio: mayor a menor
            </option>

        </select>

    </form>


    <?php if (!empty($busqueda)): ?>

        <div class="catalog-results">

            Resultados para:
            <strong>
                <?= htmlspecialchars($busqueda) ?>
            </strong>

        </div>

    <?php endif; ?>

</div>


<!-- FILTRO POR CATEGORÍA -->

<div class="catalog-categories">

    <a
        href="/origins_games/producto?categoria=todos&busqueda=<?= urlencode($busqueda ?? '') ?>&orden=<?= urlencode($orden ?? 'recientes') ?>"
        class="<?= ($cat ?? 'todos') === 'todos'
            ? 'btn-nav-solid'
            : 'btn-nav-outline' ?>"
    >
        Todos
    </a>


    <a
        href="/origins_games/producto?categoria=accesorios&busqueda=<?= urlencode($busqueda ?? '') ?>&orden=<?= urlencode($orden ?? 'recientes') ?>"
        class="<?= ($cat ?? '') === 'accesorios'
            ? 'btn-nav-solid'
            : 'btn-nav-outline' ?>"
    >
        Accesorios
    </a>


    <a
        href="/origins_games/producto?categoria=consolas&busqueda=<?= urlencode($busqueda ?? '') ?>&orden=<?= urlencode($orden ?? 'recientes') ?>"
        class="<?= ($cat ?? '') === 'consolas'
            ? 'btn-nav-solid'
            : 'btn-nav-outline' ?>"
    >
        Consolas
    </a>


    <a
        href="/origins_games/producto?categoria=juegos&busqueda=<?= urlencode($busqueda ?? '') ?>&orden=<?= urlencode($orden ?? 'recientes') ?>"
        class="<?= ($cat ?? '') === 'juegos'
            ? 'btn-nav-solid'
            : 'btn-nav-outline' ?>"
    >
        Juegos
    </a>

</div>


<!-- PRODUCTOS -->

<div
    class="grid-products"
    style="
        display:grid;
        grid-template-columns:
            repeat(auto-fill, minmax(250px, 1fr));
        gap:20px;
    "
>

    <?php if (!empty($productos)): ?>

        <?php foreach ($productos as $p): ?>

            <?php

            $stock = (int)($p['stock'] ?? 0);

            if ($stock <= 0) {

                $stockClass = 'stock-agotado';

                $stockTexto = 'Agotado';

            } elseif ($stock <= 3) {

                $stockClass = 'stock-bajo';

                $stockTexto =
                    'Últimas ' .
                    $stock .
                    ' unidades';

            } else {

                $stockClass = 'stock-disponible';

                $stockTexto =
                    'Disponible · ' .
                    $stock .
                    ' unidades';
            }

            /*
             * Comprobamos si el producto está entre los favoritos
             * del usuario actualmente conectado.
             */
            $productoId = (int)($p['id'] ?? 0);

            $esFavorito = isset($favoritosIds)
                && in_array($productoId, $favoritosIds, true);

            ?>


            <div
                class="card-item"
                style="
                    background:var(--bg-card);
                    border:1px solid var(--border-card);
                    border-radius:16px;
                    padding:20px;
                    text-align:center;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                "
            >

                <div>

                    <img
                        src="/origins_games/public/uploads/<?= htmlspecialchars(
                            $p['imagen'] ?? 'default.jpg'
                        ) ?>"
                        onerror="
                            this.onerror=null;
                            this.src='/origins_games/public/uploads/default.jpg';
                        "
                        alt="<?= htmlspecialchars(
                            $p['nombre'] ?? ''
                        ) ?>"
                        style="
                            width:100%;
                            height:160px;
                            object-fit:cover;
                            border-radius:12px;
                            margin-bottom:12px;
                        "
                    >


                    <h3
                        style="
                            color:var(--text-primary);
                            font-size:1.1rem;
                            margin-bottom:8px;
                        "
                    >
                        <?= htmlspecialchars(
                            $p['nombre'] ?? ''
                        ) ?>
                    </h3>


                    <p
                        style="
                            color:var(--text-secondary);
                            font-size:.85rem;
                            margin-bottom:15px;
                        "
                    >
                        <?= htmlspecialchars(
                            $p['descripcion'] ?? ''
                        ) ?>
                    </p>

                </div>


                <div>

                    <div
                        class="card-price"
                        style="
                            font-size:1.2rem;
                            font-weight:bold;
                            color:var(--cyan-accent);
                            margin-bottom:8px;
                        "
                    >
                        $
                        <?= number_format(
                            $p['precio'] ?? 0,
                            2
                        ) ?>
                    </div>


                    <!-- STOCK -->

                    <div
                        class="stock-info <?= $stockClass ?>"
                    >
                        <?= $stockTexto ?>
                    </div>


                    <!-- FAVORITOS -->

                    <?php if (isset($_SESSION['usuario']['id'])): ?>

                        <form
                            action="/origins_games/favoritos/<?= $esFavorito ? 'eliminar' : 'agregar' ?>"
                            method="POST"
                            class="favorite-form"
                        >

                            <input
                                type="hidden"
                                name="producto_id"
                                value="<?= $productoId ?>"
                            >

                            <button
                                type="submit"
                                class="favorite-button <?= $esFavorito
                                    ? 'favorite-button-remove'
                                    : 'favorite-button-add' ?>"
                                title="<?= $esFavorito
                                    ? 'Quitar de favoritos'
                                    : 'Agregar a favoritos' ?>"
                                aria-label="<?= $esFavorito
                                    ? 'Quitar de favoritos'
                                    : 'Agregar a favoritos' ?>"
                            >
                                <?= $esFavorito ? '♥' : '♡' ?>
                            </button>

                        </form>

                    <?php endif; ?>


                    <!-- CARRITO -->

                    <?php if ($stock > 0): ?>

                        <form
                            action="/origins_games/carrito/agregar"
                            method="POST"
                            style="margin:0;"
                        >

                            <input
                                type="hidden"
                                name="producto_id"
                                value="<?= $productoId ?>"
                            >


                            <button
                                type="submit"
                                class="btn btn-primary"
                                style="width:100%;"
                            >
                                Añadir al carrito
                            </button>

                        </form>

                    <?php else: ?>

                        <button
                            type="button"
                            disabled
                            style="
                                width:100%;
                                padding:12px;
                                border-radius:20px;
                                border:1px solid rgba(248,113,113,.4);
                                background:rgba(248,113,113,.08);
                                color:#f87171;
                                font-weight:700;
                                cursor:not-allowed;
                            "
                        >
                            Producto agotado
                        </button>

                    <?php endif; ?>

                </div>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p
            style="
                text-align:center;
                grid-column:1/-1;
                color:var(--text-secondary);
            "
        >
            No se encontraron resultados de su busqueda.
        </p>

    <?php endif; ?>

</div>


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>