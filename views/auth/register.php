
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    /* Enlace para consultar la política */
    .privacy-link {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin: 8px 0 16px;
        padding: 10px 14px;
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 9px;
        background: rgba(6, 182, 212, 0.07);
        color: var(--cyan-accent, #06b6d4);
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
    }

    .privacy-link::before {
        content: "ⓘ";
        font-size: 1.05rem;
    }

    .privacy-link:hover {
        background: rgba(6, 182, 212, 0.14);
        border-color: var(--cyan-accent, #06b6d4);
    }

    /* Casilla de aceptación */
    .privacy-acceptance {
        margin: 18px 0 22px;
        padding: 14px;
        border: 1px solid var(--border-card, #1e293b);
        border-radius: 10px;
        background: rgba(11, 19, 41, 0.65);
        color: var(--text-secondary, #94a3b8);
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .privacy-acceptance label {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        cursor: pointer;
    }

    .privacy-acceptance input[type="checkbox"] {
        margin-top: 5px;
        accent-color: var(--yellow-accent, #facc15);
        flex-shrink: 0;
        width: 16px;
        height: 16px;
    }

    /* Control CSS de la ventana emergente */
    #privacy-modal-toggle {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .privacy-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        padding: 24px;
        align-items: center;
        justify-content: center;
        background: rgba(1, 4, 15, 0.88);
    }

    #privacy-modal-toggle:checked ~ .privacy-modal {
        display: flex;
    }

    .privacy-modal-content {
        position: relative;
        width: 100%;
        max-width: 650px;
        max-height: 82vh;
        overflow-y: auto;
        padding: 30px 32px;
        background: var(--bg-card, #0b1329);
        color: var(--text-primary, #ffffff);
        border: 1px solid #263650;
        border-top: 3px solid var(--yellow-accent, #facc15);
        border-radius: 16px;
        box-shadow: 0 24px 70px rgba(0, 0, 0, 0.55);
    }

    .privacy-modal-content h2 {
        margin: 0 42px 8px 0;
        color: var(--yellow-accent, #facc15);
        font-size: 1.55rem;
        line-height: 1.3;
    }

    .privacy-modal-content > p {
        margin-bottom: 24px;
        color: var(--text-secondary, #94a3b8);
        font-size: 0.9rem;
    }

    .privacy-modal-content h3 {
        margin: 24px 0 8px;
        padding-left: 11px;
        border-left: 3px solid var(--cyan-accent, #06b6d4);
        color: var(--text-primary, #ffffff);
        font-size: 1rem;
        line-height: 1.5;
    }

    .privacy-modal-content p,
    .privacy-modal-content li {
        color: #cbd5e1;
        font-size: 0.91rem;
        line-height: 1.75;
    }

    .privacy-modal-content p {
        margin-top: 0;
        margin-bottom: 14px;
    }

    .privacy-modal-content ul {
        padding-left: 22px;
    }

    /* Botón de cierre superior */
    .privacy-modal-close {
        position: absolute;
        top: 20px;
        right: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: 1px solid #334155;
        border-radius: 9px;
        background: #111c32;
        color: #cbd5e1;
        font-size: 1.5rem;
        line-height: 1;
        cursor: pointer;
    }

    .privacy-modal-close:hover {
        color: var(--yellow-accent, #facc15);
        border-color: var(--yellow-accent, #facc15);
    }

    /* Pie de la ventana */
    .privacy-modal-footer {
        position: sticky;
        bottom: -30px;
        display: flex;
        justify-content: flex-end;
        margin: 28px -32px -30px;
        padding: 16px 32px;
        border-top: 1px solid #263650;
        background: var(--bg-card, #0b1329);
    }

    .privacy-modal-footer label {
        display: inline-block;
        padding: 10px 18px;
        border-radius: 9px;
        background: var(--yellow-accent, #facc15);
        color: #030712;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
    }

    .privacy-modal-footer label:hover {
        background: var(--yellow-hover, #eab308);
    }

    @media (max-width: 600px) {
        .privacy-modal {
            padding: 12px;
        }

        .privacy-modal-content {
            max-height: 90vh;
            padding: 24px 20px;
        }

        .privacy-modal-content h2 {
            font-size: 1.3rem;
        }

        .privacy-modal-footer {
            bottom: -24px;
            margin: 24px -20px -24px;
            padding: 14px 20px;
        }
    }
</style>

<div class="form-card">
    <h2>Crear Cuenta</h2>

    <?php if (isset($error) && $error !== null): ?>
        <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5; padding: 12px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-size: 0.9rem;">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form action="/origins_games/auth/register" method="POST">

        <div class="form-group">
            <label for="nombre">Nombre Completo *:</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                required
                maxlength="150"
                placeholder="Tu nombre"
                value="<?= htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label for="correo">Correo Electrónico *:</label>
            <input
                type="email"
                id="correo"
                name="correo"
                required
                maxlength="255"
                placeholder="tu@email.com"
                value="<?= htmlspecialchars($_POST['correo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input
                type="text"
                id="telefono"
                name="telefono"
                placeholder="Ej: 3000000000"
                value="<?= htmlspecialchars($_POST['telefono'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label for="password">Contraseña *:</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                placeholder="••••••••"
            >
        </div>

        <!-- Interruptor CSS para mostrar la política -->
        <input type="checkbox" id="privacy-modal-toggle">

        <label for="privacy-modal-toggle" class="privacy-link">
            Consultar política de privacidad
        </label>

        <!-- Ventana emergente -->
        <div class="privacy-modal">
            <div
                class="privacy-modal-content"
                role="dialog"
                aria-labelledby="privacy-title"
                aria-describedby="privacy-description"
            >
                <label
                    for="privacy-modal-toggle"
                    class="privacy-modal-close"
                    aria-label="Cerrar política"
                    title="Cerrar"
                >&times;</label>

                <h2 id="privacy-title">Política de privacidad</h2>
                <p><strong>Origins Games — Proyecto académico</strong></p>

                <div id="privacy-description">

                    <h3>1. Finalidad del proyecto</h3>
                    <p>
                        Origins Games es una aplicación web desarrollada
                        con fines académicos para simular el funcionamiento
                        de una tienda virtual de videojuegos y productos
                        relacionados. Sus funcionalidades incluyen el
                        registro de usuarios, la consulta de productos,
                        el carrito de compras y la gestión de pedidos.
                    </p>

                    <h3>2. Información tratada</h3>
                    <p>
                        Para el funcionamiento de la página se pueden
                        tratar datos como el nombre, el correo electrónico,
                        el teléfono y las credenciales de acceso que el
                        usuario ingrese durante el registro. También se
                        puede almacenar información sobre productos,
                        carritos y pedidos.
                    </p>

                    <h3>3. Finalidad del tratamiento</h3>
                    <p>
                        La información se utiliza para administrar las
                        cuentas, permitir el acceso a la plataforma,
                        gestionar el catálogo y el inventario, y mantener
                        los registros de los pedidos realizados dentro
                        del sistema.
                    </p>

                    <h3>4. Uso académico y datos ficticios</h3>
                    <p>
                        El proyecto tiene fines educativos y de demostración.
                        Durante las pruebas y exposiciones se utilizan
                        datos ficticios. No se deben ingresar datos reales
                        de terceros sin la autorización y las condiciones
                        legales que correspondan.
                    </p>

                    <h3>5. Almacenamiento y seguridad</h3>
                    <p>
                        Los datos ingresados se almacenan en la base
                        de datos de la página para permitir el uso de
                        sus funcionalidades. Las contraseñas se
                        protegen mediante mecanismos seguros de hash
                        y no se almacenan en texto plano.
                    </p>
                    <p>
                        Se deben aplicar medidas razonables para proteger
                        la información y limitar el acceso a las funciones
                        administrativas a los usuarios autorizados.
                    </p>

                    <h3>6. Acceso y permisos</h3>
                    <p>
                        El acceso a las funcionalidades depende del rol
                        asignado a cada cuenta. Los clientes acceden a las
                        funciones destinadas a usuarios y los
                        administradores a las funciones de gestión que
                        les correspondan.
                    </p>

                    <h3>7. Protección de datos personales</h3>
                    <p>
                        Durante las actividades académicas se utiliza
                        únicamente información ficticia y se evitan
                        los datos sensibles o la información real que
                        no sea necesaria para las pruebas.
                    </p>
                    <p>
                        Si Origins Games se utiliza con personas reales,
                        deberá informarse de forma precisa quién es
                        responsable del tratamiento, qué datos se
                        recopilan, para qué se utilizan y cuáles son
                        los mecanismos disponibles para ejercer los
                        derechos correspondientes.
                    </p>

                    <h3>8. Derechos de los titulares</h3>
                    <p>
                        Cuando se traten datos personales reales, sus
                        titulares podrán ejercer los derechos que les
                        reconozca la normativa aplicable, incluidos los
                        relacionados con la consulta, actualización,
                        rectificación y, cuando proceda, supresión de
                        sus datos y revocación de la autorización.
                    </p>

                    <h3>9. Alcance de esta política</h3>
                    <p>
                        Esta política describe el tratamiento de
                        información previsto para el contexto académico
                        del proyecto. Deberá actualizarse si se incorporan
                        nuevas funcionalidades, servicios externos o
                        tratamientos adicionales de información.
                    </p>

                    <h3>10. Aceptación</h3>
                    <p>
                        Antes de crear una cuenta, el usuario debe
                        consultar esta política y marcar la casilla
                        de aceptación. La aceptación es obligatoria
                        para completar el registro.
                    </p>

                </div>

                <div class="privacy-modal-footer">
                    <label for="privacy-modal-toggle">
                        Cerrar y volver al registro
                    </label>
                </div>
            </div>
        </div>

        <div class="privacy-acceptance">
            <label for="privacy_accept">
                <input
                    type="checkbox"
                    id="privacy_accept"
                    name="privacy_accept"
                    value="1"
                    <?= isset($_POST['privacy_accept']) && $_POST['privacy_accept'] === '1' ? 'checked' : '' ?>
                >
                <span>
                    He leído y acepto la política de privacidad y el
                    tratamiento de datos descrito.
                </span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary">
            Registrarse
        </button>
    </form>

    <div class="auth-footer-text">
        ¿Ya tienes cuenta?
        <a href="/origins_games/auth/login">Inicia sesión aquí</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
