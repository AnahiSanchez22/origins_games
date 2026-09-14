<div class="container">
    <div class="header-actions">
        <h2>Panel de Usuarios</h2>
        <a href="/origins_games/usuario/create" class="btn btn-primary">+ Nuevo Usuario</a>
    </div>

    <div style="overflow-x: auto;">
        <table class="table">
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
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge <?= $u['rol_id'] == 1 ? 'badge-admin' : 'badge-client' ?>">
                                <?= htmlspecialchars($u['rol_nombre']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($u['telefono'] ?? 'N/A') ?></td>
                        <td style="white-space: nowrap;">
                            <!-- Añadimos la clase .btn aquí -->
                            <a href="/origins_games/usuario/edit/<?= $u['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <?php if ($u['id'] != $_SESSION['usuario']['id']): ?>
                                <a href="/origins_games/usuario/delete/<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro de eliminar este usuario?')">Eliminar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>                                                                                                               