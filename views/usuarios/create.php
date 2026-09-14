<div class="container">
    <div class="form-card" style="max-width: 600px; margin: 0 auto;">
        <h2 style="color: var(--gold-star); text-shadow: 0 0 12px rgba(255, 234, 0, 0.6); margin-bottom: 20px;">Crear Usuario</h2>

        <?php if (!empty($error)): ?>
            <p class="alert alert-danger" style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; padding: 10px; border-radius: 10px; color: #f87171; margin-bottom: 20px;"><?= $error ?></p>
        <?php endif; ?>

        <form action="/origins_games/usuario/create" method="POST">
            <div class="form-group">
                <label>Nombre Completo *:</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Correo Electrónico *:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Contraseña *:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Rol *:</label>
                <select name="rol_id" required>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= $r['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="telefono">
            </div>

            <div class="form-group">
                <label>Dirección:</label>
                <textarea name="direccion" rows="3"></textarea>
            </div>

            <!-- Contenedor ordenado para los botones de acción -->
            <div style="display: flex; gap: 12px; align-items: center; margin-top: 25px;">
                <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                <a href="/origins_games/usuario" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>