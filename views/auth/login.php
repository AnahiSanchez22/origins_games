<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-card">
    <h2>Iniciar Sesión</h2>

    <?php if (isset($error)): ?>
        <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5; padding: 12px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-size: 0.9rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/origins_games/auth/login" method="POST">
        <div class="form-group">
            <label>Correo Electrónico:</label>
            <input type="email" name="correo" required placeholder="tu@email.com">
        </div>
        
        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-primary">Ingresar</button>
    </form>

    <div class="auth-footer-text">
        ¿No tienes cuenta? <a href="/origins_games/auth/register">Regístrate aquí</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>