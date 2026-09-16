<div class="container">

    <div class="form-card" style="max-width: 600px; margin: 40px auto;">

        <h2 style="
            color: #ffffff;
            text-shadow: 0 0 12px rgba(250, 204, 21, 0.6);
            margin-bottom: 24px;
        ">
            Crear Usuario
        </h2>

        <?php if (!empty($error)): ?>

            <p class="alert alert-danger" style="
                background: rgba(239, 68, 68, 0.15);
                border: 1px solid rgba(239, 68, 68, 0.5);
                padding: 10px 14px;
                border-radius: 10px;
                color: #f87171;
                margin-bottom: 20px;
            ">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>


        <form action="/origins_games/usuario/create" method="POST">

            <!-- NOMBRE -->
            <div class="form-group">

                <label for="nombre">
                    Nombre Completo *:
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    required
                    autocomplete="name"
                >

            </div>


            <!-- CORREO -->
            <div class="form-group">

                <label for="email">
                    Correo Electrónico *:
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    autocomplete="email"
                >

            </div>


            <!-- CONTRASEÑA -->
            <div class="form-group">

                <label for="password">
                    Contraseña *:
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                >

            </div>


            <!-- ROL -->
            <div class="form-group">

                <label for="rol_id">
                    Rol *:
                </label>

                <select
                    id="rol_id"
                    name="rol_id"
                    required
                >

                    <?php foreach ($roles as $r): ?>

                        <option value="<?= htmlspecialchars($r['id']) ?>">
                            <?= htmlspecialchars($r['nombre']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- TELÉFONO -->
            <div class="form-group">

                <label for="telefono">
                    Teléfono:
                </label>

                <input
                    type="text"
                    id="telefono"
                    name="telefono"
                    autocomplete="tel"
                >

            </div>


            <!-- DIRECCIÓN -->
            <div class="form-group">

                <label for="direccion">
                    Dirección:
                </label>

                <textarea
                    id="direccion"
                    name="direccion"
                    rows="3"
                ></textarea>

            </div>


            <!-- =================================================
                 BOTONES
                 ================================================= -->

            <div style="
                display: flex;
                align-items: center;
                gap: 14px;
                margin-top: 26px;
                width: 100%;
            ">

                <!-- GUARDAR -->
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
                    Guardar Usuario
                </button>


                <!-- CANCELAR -->
                <a
                    href="/origins_games/usuario"
                    class="btn"
                    style="
                        width: auto !important;
                        min-width: 120px;
                        min-height: 46px;

                        display: inline-flex !important;
                        align-items: center;
                        justify-content: center;

                        padding: 12px 22px;

                        background: rgba(239, 68, 68, 0.08);
                        color: #f87171 !important;

                        border: 1px solid rgba(239, 68, 68, 0.45);
                        border-radius: 20px;

                        font-size: 0.95rem;
                        font-weight: 700;

                        text-decoration: none !important;

                        box-shadow: 0 0 8px rgba(239, 68, 68, 0.08);

                        transition:
                            background 0.2s ease,
                            border-color 0.2s ease,
                            box-shadow 0.2s ease,
                            color 0.2s ease,
                            transform 0.2s ease;
                    "

                    onmouseover="
                        this.style.background='rgba(239, 68, 68, 0.18)';
                        this.style.borderColor='#ef4444';
                        this.style.color='#ffffff';
                        this.style.boxShadow='0 0 15px rgba(239, 68, 68, 0.25)';
                        this.style.transform='translateY(-2px)';
                    "

                    onmouseout="
                        this.style.background='rgba(239, 68, 68, 0.08)';
                        this.style.borderColor='rgba(239, 68, 68, 0.45)';
                        this.style.color='#f87171';
                        this.style.boxShadow='0 0 8px rgba(239, 68, 68, 0.08)';
                        this.style.transform='translateY(0)';
                    "
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>