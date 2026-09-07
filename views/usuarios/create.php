<div class="form-card">
    <h2>Crear Usuario</h2>

    <?php if (!empty($error)): ?>
        <p class="alert alert-danger"><?= $error ?></p>
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
            <textarea name="direccion"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Usuario</button>
        <a href="/origins_games/usuario" class="btn btn-secondary">Cancelar</a>
    </form>
</div>