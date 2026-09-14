<div class="container py-5" style="max-width: 600px;">
    <div class="card bg-dark text-white border-secondary shadow-lg p-4" style="border-radius: 15px;">
        <div class="text-center mb-4">
            <h2 class="text-warning fw-bold">Mi Perfil</h2>
            <p class="text-muted">Información de tu cuenta en Origins Games</p>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success text-center">¡Perfil actualizado con éxito!</div>
        <?php endif; ?>

        <div class="mb-3">
            <label class="text-muted small">Nombre completo:</label>
            <p class="fs-5 border-bottom border-secondary pb-2"><?php echo htmlspecialchars($usuario['nombre']); ?></p>
        </div>

        <div class="mb-3">
            <label class="text-muted small">Correo electrónico:</label>
            <p class="fs-5 border-bottom border-secondary pb-2"><?php echo htmlspecialchars($usuario['email']); ?></p>
        </div>

        <div class="mb-3">
            <label class="text-muted small">Teléfono:</label>
            <p class="fs-5 border-bottom border-secondary pb-2"><?php echo htmlspecialchars($usuario['telefono'] ?? 'No registrado'); ?></p>
        </div>

        <div class="mb-3">
            <label class="text-muted small">Dirección:</label>
            <p class="fs-5 border-bottom border-secondary pb-2"><?php echo htmlspecialchars($usuario['direccion'] ?? 'No registrada'); ?></p>
        </div>

        <div class="mb-4">
            <label class="text-muted small">Tipo de cuenta / Rol:</label>
            <p>
                <span class="badge bg-danger px-3 py-2 fs-6">
                    <?php echo htmlspecialchars($usuario['rol_nombre'] ?? 'Cliente'); ?>
                </span>
            </p>
        </div>

        <div class="d-flex justify-content-between">
            <a href="/origins_games/home" class="btn btn-outline-light px-4">Volver al Inicio</a>
            <a href="/origins_games/perfil/editar" class="btn btn-warning text-dark fw-bold px-4">Editar Perfil</a>
        </div>
    </div>
</div>