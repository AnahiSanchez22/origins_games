
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div style="text-align: center; padding: 30px 0 50px;">

    <h1
        style="
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 12px;
            color: var(--text-primary);
        "
    >
        Tu Universo Gamer en un Solo Lugar
    </h1>

    <p
        style="
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto 28px;
        "
    >
        Explora los componentes de última generación,
        los periféricos más precisos y el soporte técnico
        especializado que tu equipo necesita.
    </p>

    <div
        style="
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        "
    >

        <a
            href="/origins_games/producto"
            class="btn btn-primary"
            style="width: auto;"
        >
            Ver Catalogo
        </a>

        <a
            href="/origins_games/cita"
            class="btn-nav-outline"
            style="
                padding: 12px 24px;
                font-size: 0.95rem !important;
                text-decoration: none;
            "
        >
            Agendar Cita
        </a>

    </div>

</div>


<!-- =========================================
     PRODUCTOS DESTACADOS
     ========================================= -->

<h2 class="section-title">
    Productos Destacados
</h2>


<div
    class="grid-products"
    style="
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    "
>

    <?php if (!empty($productosDestacados)): ?>

        <?php foreach ($productosDestacados as $p): ?>

            <div
                class="card-item"
                style="
                    background: var(--bg-card);
                    border: 1px solid var(--border-card);
                    border-radius: 16px;
                    padding: 20px;
                    text-align: center;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                "
            >

                <div>

                    <img
                        src="/origins_games/public/uploads/<?= htmlspecialchars($p['imagen'] ?? 'default.jpg') ?>"
                        onerror="this.onerror=null; this.src='https://via.placeholder.com/200?text=Origins+Games';"
                        alt="<?= htmlspecialchars($p['nombre'] ?? '') ?>"
                        style="
                            width: 100%;
                            height: 150px;
                            object-fit: cover;
                            border-radius: 12px;
                            margin-bottom: 12px;
                        "
                    >

                    <h3
                        style="
                            color: var(--text-primary);
                            font-size: 1.1rem;
                            margin-bottom: 8px;
                        "
                    >
                        <?= htmlspecialchars($p['nombre'] ?? '') ?>
                    </h3>

                    <p
                        style="
                            color: var(--text-secondary);
                            font-size: 0.85rem;
                            margin-bottom: 15px;
                            line-height: 1.4;
                        "
                    >
                        <?= htmlspecialchars(
                            $p['descripcion']
                            ?? 'Alto rendimiento para tus partidas competitivas.'
                        ) ?>
                    </p>

                </div>


                <!-- PRECIO Y STOCK -->

                <div>

                    <div
                        class="card-price"
                        style="
                            font-size: 1.2rem;
                            font-weight: bold;
                            color: var(--cyan-accent);
                            margin-bottom: 12px;
                        "
                    >
                        $<?= number_format(
                            (float)($p['precio'] ?? 0),
                            2
                        ) ?>
                    </div>


                    <!-- PRODUCTO DISPONIBLE -->

                    <?php if ((int)($p['stock'] ?? 0) > 0): ?>

                        <div
                            style="
                                color: #34d399;
                                font-size: 0.82rem;
                                font-weight: bold;
                                margin-bottom: 10px;
                            "
                        >
                            ✓ Disponible
                            (<?= (int)$p['stock'] ?> en stock)
                        </div>


                        <form
                            action="/origins_games/carrito/agregar"
                            method="POST"
                            style="margin: 0;"
                        >

                            <input
                                type="hidden"
                                name="producto_id"
                                value="<?= (int)$p['id'] ?>"
                            >

                            <button
                                type="submit"
                                class="btn btn-primary"
                                style="width: 100%;"
                            >
                                Añadir al Carrito
                            </button>

                        </form>


                    <!-- PRODUCTO AGOTADO -->

                    <?php else: ?>

                        <div
                            style="
                                color: #f87171;
                                font-size: 0.82rem;
                                font-weight: bold;
                                margin-bottom: 10px;
                            "
                        >
                            Producto agotado
                        </div>

                        <button
                            type="button"
                            disabled
                            style="
                                width: 100%;
                                padding: 12px 20px;
                                border-radius: 25px;
                                border: 1px solid rgba(248, 113, 113, .5);
                                background: rgba(248, 113, 113, .08);
                                color: #f87171;
                                font-weight: 700;
                                cursor: not-allowed;
                            "
                        >
                            Agotado
                        </button>

                    <?php endif; ?>

                </div>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p
            style="
                text-align: center;
                grid-column: 1 / -1;
                color: var(--text-secondary);
            "
        >
            No hay productos destacados disponibles
            por el momento.
        </p>

    <?php endif; ?>

</div>


<!-- =========================================
     SOPORTE TÉCNICO Y CITAS
     ========================================= -->

<h2
    class="section-title"
    style="margin-top: 60px;"
>
    Soporte Tecnico y Citas
</h2>


<div
    class="grid-products"
    style="
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    "
>

    <!-- MANTENIMIENTO -->

    <div
        class="card-item"
        style="
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        "
    >

        <div>

            <h3
                style="
                    color: var(--text-primary);
                    font-size: 1.15rem;
                    margin-bottom: 10px;
                "
            >
                Mantenimiento Preventivo
            </h3>

            <p
                style="
                    color: var(--text-secondary);
                    font-size: 0.85rem;
                    line-height: 1.5;
                    margin-bottom: 15px;
                "
            >
                Limpieza interna profunda, cambio de pasta
                térmica de alta gama y optimización de sistema
                para evitar sobrecalentamientos.
            </p>

        </div>

        <div>

            <a
                href="/origins_games/cita"
                class="btn btn-primary"
                style="
                    display: block;
                    text-align: center;
                    text-decoration: none;
                "
            >
                Agendar Cita
            </a>

        </div>

    </div>


    <!-- REPARACIÓN -->

    <div
        class="card-item"
        style="
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        "
    >

        <div>

            <h3
                style="
                    color: var(--text-primary);
                    font-size: 1.15rem;
                    margin-bottom: 10px;
                "
            >
                Reparación de Hardware
            </h3>

            <p
                style="
                    color: var(--text-secondary);
                    font-size: 0.85rem;
                    line-height: 1.5;
                    margin-bottom: 15px;
                "
            >
                Diagnóstico avanzado y sustitución de
                componentes dañados en PCs de escritorio,
                laptops y consolas de videojuegos.
            </p>

        </div>

        <div>

            <a
                href="/origins_games/cita"
                class="btn btn-primary"
                style="
                    display: block;
                    text-align: center;
                    text-decoration: none;
                "
            >
                Agendar Cita
            </a>

        </div>

    </div>


    <!-- SOFTWARE -->

    <div
        class="card-item"
        style="
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        "
    >

        <div>

            <h3
                style="
                    color: var(--text-primary);
                    font-size: 1.15rem;
                    margin-bottom: 10px;
                "
            >
                Optimización y Software
            </h3>

            <p
                style="
                    color: var(--text-secondary);
                    font-size: 0.85rem;
                    line-height: 1.5;
                    margin-bottom: 15px;
                "
            >
                Formateo seguro, instalación limpia de
                controladores, eliminación de cuellos de
                botella y mejora general de FPS en juegos.
            </p>

        </div>

        <div>

            <a
                href="/origins_games/cita"
                class="btn btn-primary"
                style="
                    display: block;
                    text-align: center;
                    text-decoration: none;
                "
            >
                Agendar Cita
            </a>

        </div>

    </div>

</div>


<!-- =========================================
     NUEVOS LANZAMIENTOS
     ========================================= -->

<h2
    class="section-title"
    style="margin-top: 60px;"
>
    Nuevos Lanzamientos de Juegos
</h2>


<div
    class="grid-products"
    style="
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    "
>

    <!-- NOTICIA 1 -->

    <div
        class="card-item"
        style="
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        "
    >

        <div>

            <span
                style="
                    font-size: 0.75rem;
                    color: var(--yellow-accent);
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                "
            >
                Mundo Abierto / Acción
            </span>

            <h3
                style="
                    color: var(--text-primary);
                    font-size: 1.15rem;
                    margin: 8px 0 10px;
                "
            >
                Duración y tamaño colosal del mapa de GTA 6
            </h3>

            <p
                style="
                    color: var(--text-secondary);
                    font-size: 0.85rem;
                    line-height: 1.5;
                    margin-bottom: 15px;
                "
            >
                Analizamos por qué las dimensiones del mapa
                y las expectativas de duración apuntan a que
                estaremos jugando sin parar durante meses.
            </p>

        </div>

        <div>

            <a
                href="https://www.vidaextra.com/accion/duracion-tamano-colosal-mapa-gta-6-mayor-prueba-que-vamos-a-estar-jugando-parar-durante-meses"
                target="_blank"
                rel="noopener noreferrer"
                style="
                    color: var(--cyan-accent);
                    font-size: 0.9rem;
                    font-weight: bold;
                    text-decoration: none;
                "
            >
                Leer detalles del juego →
            </a>

        </div>

    </div>


    <!-- NOTICIA 2 -->

    <div
        class="card-item"
        style="
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        "
    >

        <div>

            <span
                style="
                    font-size: 0.75rem;
                    color: var(--yellow-accent);
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                "
            >
                Actualización / Nintendo
            </span>

            <h3
                style="
                    color: var(--text-primary);
                    font-size: 1.15rem;
                    margin: 8px 0 10px;
                "
            >
                Kirby Air Riders: Notas de actualización y parche
            </h3>

            <p
                style="
                    color: var(--text-secondary);
                    font-size: 0.85rem;
                    line-height: 1.5;
                    margin-bottom: 15px;
                "
            >
                Descubre los cambios, correcciones de errores
                y mejoras de rendimiento implementadas para
                las consolas de nueva generación de Nintendo.
            </p>

        </div>

        <div>

            <a
                href="https://www.gamerfocus.co/juegos/kirby-air-riders-notas-actualizacion-parche-nintendo-switch-2/"
                target="_blank"
                rel="noopener noreferrer"
                style="
                    color: var(--cyan-accent);
                    font-size: 0.9rem;
                    font-weight: bold;
                    text-decoration: none;
                "
            >
                Leer detalles del juego →
            </a>

        </div>

    </div>


    <!-- NOTICIA 3 -->

    <div
        class="card-item"
        style="
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        "
    >

        <div>

            <span
                style="
                    font-size: 0.75rem;
                    color: var(--yellow-accent);
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                "
            >
                Retro / Coleccionismo
            </span>

            <h3
                style="
                    color: var(--text-primary);
                    font-size: 1.15rem;
                    margin: 8px 0 10px;
                "
            >
                Atari anuncia Barbie Rewind Deluxe Edition
            </h3>

            <p
                style="
                    color: var(--text-secondary);
                    font-size: 0.85rem;
                    line-height: 1.5;
                    margin-bottom: 15px;
                "
            >
                Una carta de amor a más de 65 años de historia
                que combina la nostalgia clásica con una edición
                de lujo imperdible para coleccionistas.
            </p>

        </div>

        <div>

            <a
                href="https://meridiem-games.com/new/atari-anuncia-barbietm-rewind-deluxe-edition-una-carta-de-amor-a-mas-de-65-anos-de-historia"
                target="_blank"
                rel="noopener noreferrer"
                style="
                    color: var(--cyan-accent);
                    font-size: 0.9rem;
                    font-weight: bold;
                    text-decoration: none;
                "
            >
                Leer detalles del juego →
            </a>

        </div>

    </div>

</div>


<!-- =========================================
     QUIÉNES SOMOS
     Al final de la página principal
     ========================================= -->

<style>
    .about-origins {
        margin-top: 60px;
        padding: 34px;
        border: 1px solid var(--border-card);
        border-radius: 18px;
        background: linear-gradient(
            135deg,
            rgba(6, 182, 212, .07),
            var(--bg-card) 55%,
            rgba(250, 204, 21, .04)
        );
    }

    .about-eyebrow {
        margin-bottom: 10px;
        color: var(--cyan-accent);
        font-size: .76rem;
        font-weight: 800;
        letter-spacing: 1.8px;
        text-transform: uppercase;
    }

    .about-heading {
        margin: 0 0 14px;
        color: var(--text-primary);
        font-size: clamp(1.7rem, 4vw, 2.35rem);
        font-weight: 800;
    }

    .about-heading span {
        color: var(--yellow-accent);
    }

    .about-intro {
        max-width: 760px;
        margin: 0;
        color: var(--text-secondary);
        font-size: .95rem;
        line-height: 1.8;
    }

    .about-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border-card);
    }

    .about-bottom p {
        margin: 0;
        color: var(--text-secondary);
        font-size: .88rem;
        line-height: 1.7;
    }

    .about-bottom strong {
        color: var(--text-primary);
    }

    .about-cta {
        display: inline-block;
        flex-shrink: 0;
        padding: 11px 18px;
        border: 1px solid var(--yellow-accent);
        border-radius: 9px;
        color: var(--yellow-accent);
        font-size: .88rem;
        font-weight: 700;
        text-decoration: none;
        transition: background .2s, color .2s;
    }

    .about-cta:hover {
        background: var(--yellow-accent);
        color: var(--bg-dark);
    }

    @media (max-width: 650px) {
        .about-origins {
            padding: 24px 20px;
        }

        .about-bottom {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<section class="about-origins"
         id="quienes-somos"
         aria-labelledby="about-origins-title">

    <p class="about-eyebrow">Detrás de Origins Games</p>

    <h2 class="about-heading" id="about-origins-title">
        Todo gran juego tiene un <span>origen.</span>
    </h2>

    <p class="about-intro">
        Origins Games es un proyecto académico que integra el
        mundo gamer con una tienda virtual. La plataforma permite
        consultar productos y cuenta con funciones para gestionar
        usuarios, inventario, carritos y pedidos.
    </p>

    <div class="about-bottom">
        <p>
            <strong>Un proyecto creado desde la idea hasta el código.</strong>
            <br>
            Conoce más sobre el proyecto y sus integrantes.
        </p>

        <a href="/origins_games/quienes-somos"
           class="about-cta">
            Conócenos →
        </a>
    </div>

</section>


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
