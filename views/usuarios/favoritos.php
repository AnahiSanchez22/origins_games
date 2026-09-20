<?php
?>

<style>

    .favoritos-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .favoritos-title {
        text-align: center;
        color: var(--yellow-accent);
        margin-bottom: 10px;
    }

    .favoritos-subtitle {
        text-align: center;
        color: var(--text-secondary);
        font-size: .9rem;
        margin-bottom: 30px;
    }

    .favoritos-grid {
        display: grid;
        grid-template-columns: repeat(
            auto-fill,
            minmax(250px, 1fr)
        );
        gap: 20px;
    }

    .favorito-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: .2s ease;
    }

    .favorito-card:hover {
        transform: translateY(-3px);
        border-color: var(--cyan-accent);
    }

    .favorito-imagen {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .favorito-nombre {
        color: var(--text-primary);
        font-size: 1.1rem;
        margin-bottom: 8px;
    }

    .favorito-descripcion {
        color: var(--text-secondary);
        font-size: .85rem;
        margin-bottom: 15px;
    }

    .favorito-precio {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--cyan-accent);
        margin-bottom: 10px;
    }

    .favorito-stock {
        margin-bottom: 15px;
        font-size: .8rem;
        font-weight: 700;
    }

    .favorito-stock-disponible {
        color: #34d399;
    }

    .favorito-stock-bajo {
        color: #fbbf24;
    }

    .favorito-stock-agotado {
        color: #f87171;
    }

    .favorito-acciones {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .favorito-carrito {
        width: 100%;
    }

    .favorito-eliminar {
        width: 100%;
        padding: 11px 15px;
        border-radius: 20px;
        border: 1px solid rgba(248, 113, 113, .45);
        background: rgba(248, 113, 113, .08);
        color: #f87171;
        font-weight: 700;
        cursor: pointer;
        transition: .2s ease;
    }

    .favorito-eliminar:hover {
        background: rgba(248, 113, 113, .18);
        border-color: #f87171;
    }

    .favoritos-vacio {
        text-align: center;
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 16px;
        padding: 40px 20px;
    }

    .favoritos-vacio h3 {
        color: var(--text-primary);
        margin-bottom: 10px;
    }

    .favoritos-vacio p {
        color: var(--text-secondary);
        margin-bottom: 20px;
    }

    @media (max-width: 600px) {

        .favoritos-grid {
            grid-template-columns: 1fr;
        }

    }

</style>


<div class="favoritos-page">

    <h2 class="favoritos-title">
        Mis Favoritos
    </h2>

    <p class="favoritos-subtitle">
        Productos que has guardado para consultar después.
    </p>


    <?php if (!empty($favoritos)): ?>

        <div class="favoritos-grid">

            <?php foreach ($favoritos as $favorito): ?>

                <?php

                $stock = (int) ($favorito['stock'] ?? 0);

                if ($stock <= 0) {

                    $stockClass =
                        'favorito-stock-agotado';

                    $stockTexto =
                        'Agotado';

                } elseif ($stock <= 3) {

                    $stockClass =
                        'favorito-stock-bajo';

                    $stockTexto =
                        'Últimas ' .
                        $stock .
                        ' unidades';

                } else {

                    $stockClass =
                        'favorito-stock-disponible';

                    $stockTexto =
                        'Disponible · ' .
                        $stock .
                        ' unidades';
                }

                $imagen = !empty($favorito['imagen'])
                    ? $favorito['imagen']
                    : 'default.jpg';

                $nombre = $favorito['nombre'] ?? 'Producto';

                ?>

                <div class="favorito-card">

                    <div>

                        <img
                            src="/origins_games/public/uploads/<?= htmlspecialchars($imagen) ?>"
                            onerror="
                                this.onerror=null;
                                this.src='/origins_games/public/uploads/default.jpg';
                            "
                            alt="<?= htmlspecialchars($nombre) ?>"
                            class="favorito-imagen"
                        >


                        <h3 class="favorito-nombre">
                            <?= htmlspecialchars($nombre) ?>
                        </h3>


                        <p class="favorito-descripcion">
                            <?= htmlspecialchars(
                                $favorito['descripcion'] ?? ''
                            ) ?>
                        </p>

                    </div>


                    <div>

                        <div class="favorito-precio">
                            $
                            <?= number_format(
                                $favorito['precio'] ?? 0,
                                2
                            ) ?>
                        </div>


                        <div
                            class="favorito-stock <?= $stockClass ?>"
                        >
                            <?= $stockTexto ?>
                        </div>


                        <div class="favorito-acciones">

                            <?php if ($stock > 0): ?>

                                <form
                                    action="/origins_games/carrito/agregar"
                                    method="POST"
                                    style="margin:0;"
                                >

                                    <input
                                        type="hidden"
                                        name="producto_id"
                                        value="<?= (int) $favorito['id'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-primary favorito-carrito"
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


                            <form
                                action="/origins_games/favoritos/eliminar"
                                method="POST"
                                style="margin:0;"
                            >

                                <input
                                    type="hidden"
                                    name="producto_id"
                                    value="<?= (int) $favorito['id'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="favorito-eliminar"
                                >
                                    Quitar de favoritos
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="favoritos-vacio">

            <h3>
                Aún no tienes favoritos
            </h3>

            <p>
                Guarda los productos que te interesen
                para encontrarlos fácilmente después.
            </p>

            <a
                href="/origins_games/producto"
                class="btn btn-primary"
            >
                Explorar catálogo
            </a>

        </div>

    <?php endif; ?>

</div>