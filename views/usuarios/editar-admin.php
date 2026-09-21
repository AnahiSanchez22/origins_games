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
                margin-bottom: 28px;
            "
        >

            <h2
                style="
                    color: var(--yellow-accent);
                    margin-bottom: 8px;
                "
            >
                Editar Usuario
            </h2>

            <p
                style="
                    color: var(--text-secondary);
                    margin: 0;
                "
            >
                Modifica la información del usuario seleccionado
            </p>

        </div>


        <!-- ERROR -->

        <?php if (!empty($error)): ?>

            <div
                style="
                    background: rgba(239, 68, 68, .10);
                    border: 1px solid rgba(239, 68, 68, .40);
                    color: #f87171;
                    padding: 12px 15px;
                    border-radius: 10px;
                    margin-bottom: 22px;
                "
            >
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>


        <form
            action="/origins_games/usuario/edit?id=<?= urlencode($usuario['id']) ?>"
            method="POST"
        >

            <!-- NOMBRE -->

            <div class="form-group">

                <label for="nombre">
                    Nombre completo *
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?= htmlspecialchars(
                        $usuario['nombre'] ?? ''
                    ) ?>"
                    required
                >

            </div>


            <!-- EMAIL -->

            <div class="form-group">

                <label for="email">
                    Correo electrónico *
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars(
                        $usuario['email'] ?? ''
                    ) ?>"
                    required
                >

            </div>


            <!-- ROL -->

            <div class="form-group">

                <label for="rol_id">
                    Rol *
                </label>

                <select
                    id="rol_id"
                    name="rol_id"
                    required
                >

                    <?php foreach ($roles as $rol): ?>

                        <option
                            value="<?= (int) $rol['id'] ?>"
                            <?= (
                                (int) $usuario['rol_id'] ===
                                (int) $rol['id']
                            ) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($rol['nombre']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- TELÉFONO -->

            <div class="form-group">

                <label for="telefono">
                    Teléfono
                </label>

                <input
                    type="text"
                    id="telefono"
                    name="telefono"
                    value="<?= htmlspecialchars(
                        $usuario['telefono'] ?? ''
                    ) ?>"
                    placeholder="Ingresa el teléfono"
                >

            </div>


            <!-- DIRECCIÓN -->

            <div class="form-group">

                <label for="direccion">
                    Dirección
                </label>

                <textarea
                    id="direccion"
                    name="direccion"
                    rows="3"
                    placeholder="Ingresa la dirección"
                ><?= htmlspecialchars(
                    $usuario['direccion'] ?? ''
                ) ?></textarea>

            </div>


            <!-- CONTRASEÑA -->

            <div class="form-group">

                <label for="password">
                    Nueva contraseña
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="Déjala vacía para conservar la actual"
                >

                <small
                    style="
                        display: block;
                        margin-top: 7px;
                        color: var(--text-secondary);
                        font-size: .78rem;
                    "
                >
                    Solo completa este campo si deseas cambiar la contraseña.
                </small>

            </div>


            <!-- BOTONES -->

            <div
                style="
                    display: flex;
                    gap: 12px;
                    margin-top: 26px;
                    width: 100%;
                "
            >

                <button
                    type="submit"
                    class="btn btn-primary"
                    style="
                        width: auto !important;
                        flex: 1;
                        min-height: 46px;
                        margin: 0;
                    "
                >
                    Guardar Cambios
                </button>


                <a
                    href="/origins_games/usuario"
                    class="btn"
                    style="
                        width: auto !important;
                        min-width: 130px;
                        min-height: 46px;

                        display: inline-flex !important;
                        align-items: center;
                        justify-content: center;

                        padding: 12px 20px;

                        background: rgba(239, 68, 68, .08);

                        color: #f87171 !important;

                        border: 1px solid rgba(239, 68, 68, .45);

                        border-radius: 20px;

                        font-size: .95rem;
                        font-weight: 700;

                        text-decoration: none !important;

                        transition: all .2s ease;
                    "

                    onmouseover="
                        this.style.background='rgba(239, 68, 68, .18)';
                        this.style.borderColor='#ef4444';
                        this.style.color='#ffffff';
                        this.style.transform='translateY(-2px)';
                    "

                    onmouseout="
                        this.style.background='rgba(239, 68, 68, .08)';
                        this.style.borderColor='rgba(239, 68, 68, .45)';
                        this.style.color='#f87171';
                        this.style.transform='translateY(0)';
                    "
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>