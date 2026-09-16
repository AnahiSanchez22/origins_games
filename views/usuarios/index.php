<style>
    /* =========================================================
       GESTIÓN DE USUARIOS
       ========================================================= */

    .usuarios-container {
        max-width: 1200px;
        margin: 35px auto;
        padding: 0 20px;
    }

    .usuarios-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .usuarios-header h2 {
        margin: 0;
        color: #ffffff;
        font-size: 1.8rem;
        font-weight: 800;
        text-shadow: 0 0 10px rgba(6, 182, 212, 0.25);
    }

    /* =========================================================
       NUEVO USUARIO
       ========================================================= */

    .btn-nuevo-usuario {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;

        padding: 10px 22px;

        background: #facc15 !important;
        color: #000000 !important;

        border: none;
        border-radius: 20px;

        font-family: inherit;
        font-size: 0.9rem;
        font-weight: 700;

        text-decoration: none !important;
        white-space: nowrap;

        box-shadow: 0 0 12px rgba(250, 204, 21, 0.35);

        transition: all 0.2s ease;
    }

    .btn-nuevo-usuario:hover {
        background: #eab308 !important;
        color: #000000 !important;

        transform: translateY(-2px);

        box-shadow:
            0 0 18px rgba(250, 204, 21, 0.55);
    }

    /* =========================================================
       TABLA
       ========================================================= */

    .usuarios-table-card {
        background: rgba(11, 19, 41, 0.72);

        border: 1px solid #1e293b;
        border-radius: 18px;

        overflow: hidden;

        box-shadow:
            0 12px 35px rgba(0, 0, 0, 0.45);
    }

    .usuarios-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .usuarios-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .usuarios-table th {
        background: rgba(6, 182, 212, 0.08);

        color: #06b6d4;

        padding: 15px 14px;

        font-size: 0.82rem;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 0.04em;

        text-align: left;

        border-bottom: 1px solid rgba(6, 182, 212, 0.25);
    }

    .usuarios-table td {
        padding: 16px 14px;

        color: #ffffff;

        font-size: 0.9rem;

        border-bottom: 1px solid rgba(255, 255, 255, 0.06);

        vertical-align: middle;
    }

    .usuarios-table tbody tr {
        transition: background 0.2s ease;
    }

    .usuarios-table tbody tr:hover {
        background: rgba(6, 182, 212, 0.045);
    }

    .usuarios-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Columnas */

    .usuarios-table th:nth-child(1),
    .usuarios-table td:nth-child(1) {
        width: 7%;
    }

    .usuarios-table th:nth-child(2),
    .usuarios-table td:nth-child(2) {
        width: 19%;
    }

    .usuarios-table th:nth-child(3),
    .usuarios-table td:nth-child(3) {
        width: 27%;
    }

    .usuarios-table th:nth-child(4),
    .usuarios-table td:nth-child(4) {
        width: 15%;
    }

    .usuarios-table th:nth-child(5),
    .usuarios-table td:nth-child(5) {
        width: 14%;
    }

    .usuarios-table th:nth-child(6),
    .usuarios-table td:nth-child(6) {
        width: 18%;
    }

    .usuario-email {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* =========================================================
       ROLES
       ========================================================= */

    .usuario-rol {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 6px 12px;

        border-radius: 12px;

        font-size: 0.78rem;
        font-weight: 700;

        white-space: nowrap;
    }

    .usuario-rol.admin {
        color: #f43f5e;

        border: 1px solid rgba(244, 63, 94, 0.45);

        background: rgba(244, 63, 94, 0.08);
    }

    .usuario-rol.cliente {
        color: #06b6d4;

        border: 1px solid rgba(6, 182, 212, 0.4);

        background: rgba(6, 182, 212, 0.08);
    }

    /* =========================================================
       ACCIONES
       ========================================================= */

    .usuario-actions {
        display: flex;
        align-items: center;
        gap: 7px;

        white-space: nowrap;
    }

    .usuario-action {
        display: inline-flex !important;

        align-items: center;
        justify-content: center;

        min-width: 65px;

        padding: 7px 11px;

        border-radius: 12px;

        font-family: inherit;
        font-size: 0.78rem;
        font-weight: 700;

        text-decoration: none !important;

        transition: all 0.2s ease;
    }

    /* EDITAR */

    .usuario-editar {
        color: #06b6d4 !important;

        border: 1px solid rgba(6, 182, 212, 0.4);

        background: rgba(6, 182, 212, 0.06);
    }

    .usuario-editar:hover {
        color: #ffffff !important;

        background: rgba(6, 182, 212, 0.16);

        border-color: #06b6d4;

        box-shadow:
            0 0 10px rgba(6, 182, 212, 0.25);

        transform: translateY(-1px);
    }

    /* ELIMINAR */

    .usuario-eliminar {
        color: #f43f5e !important;

        border: 1px solid rgba(244, 63, 94, 0.4);

        background: rgba(244, 63, 94, 0.06);
    }

    .usuario-eliminar:hover {
        color: #ffffff !important;

        background: rgba(244, 63, 94, 0.16);

        border-color: #f43f5e;

        box-shadow:
            0 0 10px rgba(244, 63, 94, 0.25);

        transform: translateY(-1px);
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 900px) {

        .usuarios-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .btn-nuevo-usuario {
            width: 100%;
        }

        .usuarios-table {
            min-width: 900px;
        }
    }

    @media (max-width: 600px) {

        .usuarios-container {
            margin: 20px auto;
            padding: 0 12px;
        }

        .usuarios-header h2 {
            font-size: 1.5rem;
        }
    }
</style>


<div class="usuarios-container">

    <!-- =====================================================
         ENCABEZADO
         ===================================================== -->

    <div class="usuarios-header">

        <h2>
            Panel de Usuarios
        </h2>

        <a
            href="/origins_games/usuario/create"
            class="btn-nuevo-usuario"
        >
            + Nuevo Usuario
        </a>

    </div>


    <!-- =====================================================
         TABLA
         ===================================================== -->

    <div class="usuarios-table-card">

        <div class="usuarios-table-wrapper">

            <table class="usuarios-table">

                <thead>
                    <tr>

                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>

                    </tr>
                </thead>


                <tbody>

                    <?php foreach ($usuarios as $u): ?>

                        <tr>

                            <!-- ID -->
                            <td>
                                <?= htmlspecialchars($u['id']) ?>
                            </td>


                            <!-- NOMBRE -->
                            <td>
                                <?= htmlspecialchars($u['nombre']) ?>
                            </td>


                            <!-- EMAIL -->
                            <td class="usuario-email">
                                <?= htmlspecialchars($u['email']) ?>
                            </td>


                            <!-- ROL -->
                            <td>

                                <span class="usuario-rol <?= $u['rol_id'] == 1 ? 'admin' : 'cliente' ?>">

                                    <?= htmlspecialchars($u['rol_nombre']) ?>

                                </span>

                            </td>


                            <!-- TELÉFONO -->
                            <td>

                                <?= htmlspecialchars(
                                    !empty($u['telefono'])
                                        ? $u['telefono']
                                        : 'N/A'
                                ) ?>

                            </td>


                            <!-- ACCIONES -->
                            <td>

                                <div class="usuario-actions">

                                    <!-- EDITAR -->
                                    <a
                                        href="/origins_games/usuario/edit?id=<?= urlencode($u['id']) ?>"
                                        class="usuario-action usuario-editar"
                                    >
                                        Editar
                                    </a>


                                    <!-- ELIMINAR -->
                                    <?php if ($u['id'] != $_SESSION['usuario']['id']): ?>

                                        <a
                                            href="/origins_games/usuario/delete?id=<?= urlencode($u['id']) ?>"
                                            class="usuario-action usuario-eliminar"
                                            onclick="return confirm('¿Seguro que deseas eliminar a <?= htmlspecialchars($u['nombre'], ENT_QUOTES) ?>?');"
                                        >
                                            Eliminar
                                        </a>

                                    <?php endif; ?>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>