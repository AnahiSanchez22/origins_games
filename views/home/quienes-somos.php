
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    .quienes-container {
        max-width: 1050px;
        margin: 0 auto;
        padding: 35px 20px 60px;
        color: var(--text-primary);
    }

    /* ENCABEZADO */

    .quienes-header {
        padding: 30px 15px 40px;
        text-align: center;
    }

    .quienes-header h1 {
        margin: 0 0 14px;
        color: var(--yellow-accent);
        font-size: clamp(2rem, 5vw, 2.8rem);
        font-weight: 800;
    }

    .quienes-header p {
        max-width: 720px;
        margin: 0 auto;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    /* SECCIONES */

    .quienes-section {
        margin-top: 28px;
        padding: 28px;
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 16px;
    }

    .quienes-section h2 {
        margin: 0 0 16px;
        color: var(--cyan-accent);
        font-size: 1.4rem;
        font-weight: 800;
    }

    .quienes-section p {
        margin: 0 0 12px;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .quienes-section p:last-child {
        margin-bottom: 0;
    }

    /* TARJETAS DE INTEGRANTES */

    .quienes-team {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-top: 24px;
    }

    .quienes-member {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 175px;
        padding: 25px 18px;
        overflow: hidden;
        text-align: center;
        background: rgba(3, 7, 18, 0.4);
        border: 1px solid var(--border-card);
        border-radius: 14px;
        transition:
            border-color 0.2s ease,
            transform 0.2s ease;
    }

    .quienes-member::before {
        content: "";
        position: absolute;
        top: 0;
        left: 25%;
        width: 50%;
        height: 2px;
        background: linear-gradient(
            90deg,
            transparent,
            #facc15,
            transparent
        );
        opacity: 0.8;
    }

    .quienes-member:hover {
        transform: translateY(-3px);
        border-color: rgba(250, 204, 21, 0.45);
    }

    /* FIGURA DE PERSONA */

    .quienes-member-icon {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin: 0 auto 17px;
        border-radius: 50%;
        background: rgba(250, 204, 21, 0.06);
        border: 1px solid rgba(250, 204, 21, 0.35);
    }

    /* CABEZA */

    .quienes-member-icon .persona-cabeza {
        position: absolute;
        top: 13px;
        left: 50%;
        width: 15px;
        height: 15px;
        border: 2px solid #facc15;
        border-radius: 50%;
        transform: translateX(-50%);
    }

    /* CUERPO Y HOMBROS */

    .quienes-member-icon .persona-cuerpo {
        position: absolute;
        bottom: 12px;
        left: 50%;
        width: 30px;
        height: 19px;
        border: 2px solid #facc15;
        border-bottom: 0;
        border-radius: 16px 16px 7px 7px;
        transform: translateX(-50%);
    }

    /* NOMBRE */

    .quienes-member h3 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.05rem;
        font-weight: 700;
    }

    /* DETALLE BAJO EL NOMBRE */

    .quienes-member-line {
        width: 30px;
        height: 2px;
        margin-top: 12px;
        border-radius: 5px;
        background: #facc15;
    }

    /* TECNOLOGÍAS */

    .quienes-tech {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .quienes-tech span {
        padding: 8px 13px;
        border: 1px solid var(--border-card);
        border-radius: 8px;
        background: rgba(3, 7, 18, 0.5);
        color: var(--text-secondary);
        font-size: 0.88rem;
    }

    /* BOTONES */

    .quienes-actions {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 32px;
    }

    .quienes-back {
        display: inline-block;
        padding: 12px 22px;
        border: 1px solid var(--yellow-accent);
        border-radius: 25px;
        color: var(--yellow-accent);
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
    }

    .quienes-back:hover {
        background: var(--yellow-accent);
        color: var(--bg-dark);
    }

    .quienes-catalogo {
        display: inline-block;
        width: auto;
        padding: 12px 22px;
        text-decoration: none;
    }

    /* ADAPTACIÓN A PANTALLAS PEQUEÑAS */

    @media (max-width: 700px) {
        .quienes-team {
            grid-template-columns: 1fr;
        }

        .quienes-section {
            padding: 22px 18px;
        }

        .quienes-header {
            padding: 25px 5px 35px;
        }

        .quienes-member {
            min-height: 155px;
        }
    }
</style>


<main class="quienes-container">

    <!-- ENCABEZADO -->

    <header class="quienes-header">

        <h1>¿Quiénes somos?</h1>

        <p>
            Somos el equipo desarrollador de Origins Games,
            un proyecto académico que consiste en una plataforma
            web para consultar productos gamer, gestionar compras
            y pedidos, controlar inventario y solicitar soporte técnico.
        </p>

    </header>


    <!-- SOBRE EL PROYECTO -->

    <section class="quienes-section">

        <h2>Sobre el proyecto</h2>

        <p>
            Origins Games reúne funciones de una tienda virtual
            orientada al mundo gamer. El sistema permite consultar
            el catálogo de productos y utilizar el carrito de compras
            para gestionar pedidos.
        </p>

        <p>
            También cuenta con funciones administrativas para gestionar
            productos, usuarios, inventario y pedidos, además de
            herramientas relacionadas con el soporte técnico y las citas.
        </p>

    </section>


    <!-- OBJETIVO -->

    <section class="quienes-section">

        <h2>Objetivo</h2>

        <p>
            Desarrollar una plataforma web que organice la consulta
            y gestión de productos gamer, las compras, el inventario
            y las solicitudes de soporte técnico mediante una estructura
            de software organizada y una base de datos.
        </p>

    </section>


    <!-- INTEGRANTES -->

    <section class="quienes-section">

        <h2>Integrantes del proyecto</h2>

        <p>
            El equipo está conformado por las siguientes integrantes:
        </p>

        <div class="quienes-team">

            <article class="quienes-member">

                <div class="quienes-member-icon" aria-hidden="true">
                    <span class="persona-cabeza"></span>
                    <span class="persona-cuerpo"></span>
                </div>

                <h3>Alice Téllez</h3>

                <div class="quienes-member-line" aria-hidden="true"></div>

            </article>


            <article class="quienes-member">

                <div class="quienes-member-icon" aria-hidden="true">
                    <span class="persona-cabeza"></span>
                    <span class="persona-cuerpo"></span>
                </div>

                <h3>Hanna Ballen</h3>

                <div class="quienes-member-line" aria-hidden="true"></div>

            </article>


            <article class="quienes-member">

                <div class="quienes-member-icon" aria-hidden="true">
                    <span class="persona-cabeza"></span>
                    <span class="persona-cuerpo"></span>
                </div>

                <h3>Anahi Sanchez</h3>

                <div class="quienes-member-line" aria-hidden="true"></div>

            </article>

        </div>

    </section>


    <!-- TECNOLOGÍAS Y HERRAMIENTAS -->

    <section class="quienes-section">

        <h2>Tecnologías y herramientas</h2>

        <p>
            Tecnologías y herramientas utilizadas en el desarrollo
            del proyecto:
        </p>

        <div class="quienes-tech">

            <span>PHP</span>
            <span>MySQL</span>
            <span>HTML</span>
            <span>CSS</span>
            <span>XAMPP</span>

        </div>

    </section>


    <!-- NAVEGACIÓN -->

    <div class="quienes-actions">

        <a href="/origins_games/home" class="quienes-back">
            ← Volver al inicio
        </a>

        <a href="/origins_games/producto"
           class="btn btn-primary quienes-catalogo">
            Ver catálogo
        </a>

    </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
