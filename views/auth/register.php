<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-card">
    <h2>Crear Cuenta</h2>

    <?php if (isset($error)): ?>
        <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5; padding: 12px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-size: 0.9rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/origins_games/auth/register" method="POST">
        <div class="form-group">
            <label>Nombre Completo *:</label>
            <input type="text" name="nombre" required placeholder="Tu nombre">
        </div>

        <div class="form-group">
            <label>Correo Electrónico *:</label>
            <input type="email" name="correo" required placeholder="tu@email.com">
        </div>
        
        <div class="form-group">
            <label>Contraseña *:</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>

    <div class="auth-footer-text">
        ¿Ya tienes cuenta? <a href="/origins_games/auth/login">Inicia sesión aquí</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>