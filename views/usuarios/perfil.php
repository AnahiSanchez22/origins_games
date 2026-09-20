<div
    style="
        max-width: 650px;
        margin: 40px auto;
        padding: 0 20px;
    "
>

    <div
        style="
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,.25);
        "
    >

        <!-- TÍTULO -->

        <div
            style="
                text-align: center;
                margin-bottom: 30px;
            "
        >

            <h2
                style="
                    color: var(--yellow-accent);
                    margin-bottom: 8px;
                "
            >
                Mi Perfil
            </h2>

            <p
                style="
                    color: var(--text-secondary);
                    margin: 0;
                "
            >
                Información de tu cuenta en Origins Games
            </p>

        </div>


        <!-- MENSAJE DE ÉXITO -->

        <?php if (isset($_GET['success'])): ?>

            <div
                style="
                    background: rgba(52, 211, 153, .10);
                    border: 1px solid rgba(52, 211, 153, .35);
                    color: #34d399;
                    padding: 12px 15px;
                    border-radius: 10px;
                    margin-bottom: 22px;
                    text-align: center;
                "
            >
                Perfil actualizado correctamente.
            </div>

        <?php endif; ?>


        <!-- NOMBRE -->

        <div
            style="
                margin-bottom: 22px;
                padding-bottom: 16px;
                border-bottom: 1px solid var(--border-card);
            "
        >

            <div
                style="
                    color: var(--text-secondary);
                    font-size: .8rem;
                    margin-bottom: 6px;
                "
            >
                Nombre completo
            </div>

            <div
                style="
                    color: var(--text-primary);
                    font-size: 1rem;
                "
            >
                <?= htmlspecialchars(
                    $usuario['nombre'] ?? ''
                ) ?>
            </div>

        </div>


        <!-- EMAIL -->

        <div
            style="
                margin-bottom: 22px;
                padding-bottom: 16px;
                border-bottom: 1px solid var(--border-card);
            "
        >

            <div
                style="
                    color: var(--text-secondary);
                    font-size: .8rem;
                    margin-bottom: 6px;
                "
            >
                Correo electrónico
            </div>

            <div
                style="
                    color: var(--text-primary);
                    font-size: 1rem;
                "
            >
                <?= htmlspecialchars(
                    $usuario['email'] ?? ''
                ) ?>
            </div>

        </div>


        <!-- TELÉFONO -->

        <div
            style="
                margin-bottom: 22px;
                padding-bottom: 16px;
                border-bottom: 1px solid var(--border-card);
            "
        >

            <div
                style="
                    color: var(--text-secondary);
                    font-size: .8rem;
                    margin-bottom: 6px;
                "
            >
                Teléfono
            </div>

            <div
                style="
                    color: var(--text-primary);
                    font-size: 1rem;
                "
            >
                <?= !empty($usuario['telefono'])
                    ? htmlspecialchars($usuario['telefono'])
                    : 'No registrado'
                ?>
            </div>

        </div>


        <!-- DIRECCIÓN -->

        <div
            style="
                margin-bottom: 30px;
                padding-bottom: 16px;
                border-bottom: 1px solid var(--border-card);
            "
        >

            <div
                style="
                    color: var(--text-secondary);
                    font-size: .8rem;
                    margin-bottom: 6px;
                "
            >
                Dirección
            </div>

            <div
                style="
                    color: var(--text-primary);
                    font-size: 1rem;
                "
            >
                <?= !empty($usuario['direccion'])
                    ? nl2br(htmlspecialchars($usuario['direccion']))
                    : 'No registrada'
                ?>
            </div>

        </div>


        <!-- TIPO DE CUENTA -->

        <div
            style="
                margin-bottom: 30px;
            "
        >

            <div
                style="
                    color: var(--text-secondary);
                    font-size: .8rem;
                    margin-bottom: 8px;
                "
            >
                Tipo de cuenta
            </div>

            <span
                style="
                    display: inline-block;
                    padding: 7px 14px;
                    border-radius: 20px;
                    background: rgba(250, 204, 21, .10);
                    border: 1px solid rgba(250, 204, 21, .35);
                    color: var(--yellow-accent);
                    font-size: .85rem;
                    font-weight: 700;
                "
            >
                <?= htmlspecialchars(
                    $usuario['rol_nombre'] ?? 'Cliente'
                ) ?>
            </span>

        </div>


        <!-- BOTONES -->

        <div
            style="
                display: flex;
                gap: 12px;
                justify-content: space-between;
                flex-wrap: wrap;
            "
        >

            <a
                href="/origins_games/home"
                class="btn"
                style="
                    width: auto !important;
                    min-width: 150px;
                    text-align: center;
                    text-decoration: none;
                "
            >
                Volver al Inicio
            </a>


            <a
                href="/origins_games/perfil/editar"
                class="btn btn-primary"
                style="
                    width: auto !important;
                    min-width: 150px;
                    text-align: center;
                    text-decoration: none;
                "
            >
                Editar Perfil
            </a>

        </div>

    </div>

</div>