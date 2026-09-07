<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto;">
    <h2 style="font-size: 2rem; color: var(--yellow-accent); margin-bottom: 24px; text-align: center;">
        Gestión de Citas Técnicas (Panel Admin)
    </h2>

    <div style="background: var(--bg-card); border: 1px solid var(--border-card); border-radius: 20px; padding: 24px; overflow-x: auto;">
        <?php if (!empty($citas)): ?>
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-card); color: var(--yellow-accent);">
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Usuario / Email</th>
                        <th style="padding: 12px;">Equipo</th>
                        <th style="padding: 12px;">Fecha y Hora</th>
                        <th style="padding: 12px;">Observaciones</th>
                        <th style="padding: 12px;">Estado</th>
                        <th style="padding: 12px; text-align: center;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($citas as $c): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); color: var(--text-secondary);">
                            <td style="padding: 12px; font-weight: bold; color: var(--text-primary);">#<?= $c['id'] ?></td>
                            <td style="padding: 12px;">
                                <strong style="color: var(--text-primary);"><?= htmlspecialchars($c['usuario_nombre'] ?? 'Usuario ID: ' . $c['usuario_id']) ?></strong><br>
                                <small><?= htmlspecialchars($c['usuario_email'] ?? '') ?></small>
                            </td>
                            <td style="padding: 12px; color: var(--cyan-accent);"><?= htmlspecialchars($c['tipo_equipo']) ?></td>
                            <td style="padding: 12px;"><?= $c['fecha_cita'] ?><br><small><?= $c['hora_cita'] ?></small></td>
                            <td style="padding: 12px; max-width: 200px; font-size: 0.8rem;"><?= htmlspecialchars($c['observaciones'] ?? 'Sin notas') ?></td>
                            <td style="padding: 12px;">
                                <span style="padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; text-transform: capitalize;
                                    background: <?= $c['estado'] === 'confirmada' ? '#166534' : ($c['estado'] === 'completada' ? '#1e3a8a' : ($c['estado'] === 'cancelada' ? '#991b1b' : '#854d0e')) ?>;
                                    color: #fff;">
                                    <?= htmlspecialchars($c['estado']) ?>
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <form action="/origins_games/cita/cambiarEstado" method="POST" style="display: inline-flex; gap: 4px;">
                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                    <select name="estado" style="padding: 4px; font-size: 0.8rem; border-radius: 4px; background: #060d1e; color: #fff; border: 1px solid var(--border-card);">
                                        <option value="pendiente" <?= $c['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                        <option value="confirmada" <?= $c['estado'] === 'confirmada' ? 'selected' : '' ?>>Confirmar</option>
                                        <option value="completada" <?= $c['estado'] === 'completada' ? 'selected' : '' ?>>Completar</option>
                                        <option value="cancelada" <?= $c['estado'] === 'cancelada' ? 'selected' : '' ?>>Cancelar</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="padding: 4px 8px; font-size: 0.8rem;">Guardar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: var(--text-secondary); text-align: center;">No hay citas registradas en el sistema.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>