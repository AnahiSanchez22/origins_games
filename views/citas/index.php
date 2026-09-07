<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<h2 style="font-size: 2rem; color: var(--yellow-accent); margin-bottom: 24px; text-align: center;">
    Servicio Técnico y Mantenimiento
</h2>

<div class="citas-layout" style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; max-width: 1100px; margin: 0 auto;">
    
    <!-- Formulario para Agendamiento -->
    <div class="form-card" style="margin: 0; max-width: 100%;">
        <h3 style="font-size: 1.4rem; margin-bottom: 20px; color: var(--text-primary); text-align: center;">
            Agendar Nueva Cita
        </h3>
        
        <form action="/origins_games/cita/guardar" method="POST">
            <div class="form-group">
                <label>Servicio Requerido *:</label>
                <select name="servicio_id" required>
                    <option value="">Selecciona un servicio</option>
                    <?php 
                    $nombresServicios = [
                        1 => 'Mantenimiento Preventivo Limpieza General',
                        2 => 'Cambio de Pasta Térmica y Almohadillas',
                        3 => 'Mantenimiento y Calibración de Controles',
                        4 => 'Diagnóstico General de Fallas',
                        5 => 'Reparación de Fuente de Poder / Encendido',
                        6 => 'Cambio o Actualización de Disco Duro / SSD',
                        7 => 'Reparación de Puerto HDMI / Conectores',
                        8 => 'Mantenimiento de Sistema de Refrigeración',
                        9 => 'Formateo e Instalación de Sistema Operativo',
                        10 => 'Limpieza Profunda Interna y Externa'
                    ];
                    foreach ($nombresServicios as $id => $nombre): 
                    ?>
                        <option value="<?= $id ?>"><?= $nombre ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Equipo (Ej. PS5, Xbox Series X, PC, Nintendo Switch) *:</label>
                <input type="text" name="tipo_equipo" required placeholder="Escribe tu dispositivo...">
            </div>

            <div class="form-group">
                <label>Fecha Requerida *:</label>
                <input type="date" name="fecha_cita" required min="<?= date('Y-m-d') ?>">
            </div>

            <div class="form-group">
                <label>Hora Requerida *:</label>
                <input type="time" name="hora_cita" required>
            </div>

            <div class="form-group">
                <label>Detalles / Fallas observadas:</label>
                <textarea name="observaciones" rows="3" placeholder="Describe el problema de tu equipo..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Agendar Cita</button>
        </form>
    </div>

    <!-- Historial de Citas del Usuario -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-card); border-radius: 20px; padding: 24px;">
        <h3 style="font-size: 1.4rem; color: var(--text-primary); margin-bottom: 20px;">
            Historial de Citas
        </h3>

        <?php if (!empty($citas)): ?>
            <div style="display: flex; flex-direction: column; gap: 14px; max-height: 500px; overflow-y: auto;">
                <?php foreach ($citas as $c): ?>
                    <div style="background: #060d1e; padding: 16px; border-radius: 12px; border: 1px solid var(--border-card);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="color: var(--yellow-accent); font-size: 1.05rem;">
                                <?= htmlspecialchars($nombresServicios[$c['servicio_id']] ?? 'Servicio Técnico') ?>
                            </strong>
                            <span style="font-size: 0.8rem; padding: 4px 8px; border-radius: 6px; background: #1e293b; color: var(--cyan-accent); text-transform: capitalize;">
                                <?= htmlspecialchars($c['estado'] ?? 'pendiente') ?>
                            </span>
                        </div>

                        <div style="font-size: 0.85rem; color: var(--text-secondary); display: flex; flex-direction: column; gap: 4px;">
                            <p style="margin: 0;"><strong style="color: var(--text-primary);">Equipo:</strong> <?= htmlspecialchars($c['tipo_equipo'] ?? 'N/A') ?></p>
                            <p style="margin: 0;"><strong style="color: var(--text-primary);">Fecha/Hora:</strong> <?= htmlspecialchars($c['fecha_cita'] ?? '') ?> a las <?= htmlspecialchars($c['hora_cita'] ?? '') ?></p>
                            <?php if (!empty($c['observaciones'])): ?>
                                <p style="margin: 0;"><strong style="color: var(--text-primary);">Notas:</strong> <?= htmlspecialchars($c['observaciones']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Botón de cancelación si no está completada ni ya cancelada -->
                        <?php if (in_array($c['estado'], ['pendiente', 'confirmada'])): ?>
                            <form action="/origins_games/cita/cancelar" method="POST" style="margin-top: 12px; text-align: right;" onsubmit="return confirm('¿Seguro que deseas cancelar esta cita?');">
                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                <button type="submit" style="background: #991b1b; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem;">
                                    Cancelar Cita
                                </button>
                            </form>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: var(--text-secondary);">No tienes citas agendadas por el momento.</p>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>