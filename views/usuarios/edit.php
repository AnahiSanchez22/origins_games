<div class="form-card">
    <h2>Editar Usuario (#<?= $usuario['id'] ?>)</h2>

    <?php if (!empty($error)): ?>
        <p class="alert alert-danger"><?= $error ?></p>
    <?php endif; ?>

    <form action="/origins_games/usuario/edit/<?= $usuario['id'] ?>" method="POST">
        <div class="form-group">
            <label>Nombre Completo *:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>

        <div class="form-group">
            <label>Correo Electrónico *:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
        </div>

        <div class="form-group">
            <label>Contraseña (dejar en blanco para mantener la actual):</label>
            <input type="password" name="password">
        </div>

        <div class="form-group">
            <label>Rol *:</label>
            <select name="rol_id" required>
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= $r['id'] == $usuario['rol_id'] ? 'selected' : '' ?>>
                        <?= $r['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Dirección:</label>
            <textarea name="direccion"><?= htmlspecialchars($usuario['direccion'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="/origins_games/usuario" class="btn btn-secondary">Cancelar</a>
    </form>
</div>