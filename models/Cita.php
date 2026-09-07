<?php
class Cita {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=origins_games;charset=utf8", "root", "");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getByUsuario($usuario_id) {
        $sql = "SELECT * FROM citas WHERE usuario_id = ? ORDER BY fecha_cita DESC, hora_cita DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT c.*, u.nombre AS usuario_nombre, u.email AS usuario_email 
                FROM citas c 
                LEFT JOIN usuarios u ON c.usuario_id = u.id 
                ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO citas (usuario_id, servicio_id, tipo_equipo, fecha_cita, hora_cita, observaciones, estado) 
                VALUES (?, ?, ?, ?, ?, ?, 'pendiente')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['usuario_id'],
            $data['servicio_id'],
            $data['tipo_equipo'],
            $data['fecha_cita'],
            $data['hora_cita'],
            $data['observaciones']
        ]);
    }

    public function getServicios() {
        return [
            ['id' => 1, 'nombre' => 'Mantenimiento Preventivo Limpieza General'],
            ['id' => 2, 'nombre' => 'Cambio de Pasta Térmica y Almohadillas'],
            ['id' => 3, 'nombre' => 'Mantenimiento y Calibración de Controles'],
            ['id' => 4, 'nombre' => 'Diagnóstico General de Fallas'],
            ['id' => 5, 'nombre' => 'Reparación de Fuente de Poder / Encendido'],
            ['id' => 6, 'nombre' => 'Cambio o Actualización de Disco Duro / SSD'],
            ['id' => 7, 'nombre' => 'Reparación de Puerto HDMI / Conectores'],
            ['id' => 8, 'nombre' => 'Mantenimiento de Sistema de Refrigeración'],
            ['id' => 9, 'nombre' => 'Formateo e Instalación de Sistema Operativo'],
            ['id' => 10, 'nombre' => 'Limpieza Profunda Interna y Externa']
        ];
    }

    public function updateEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE citas SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }

    public function cancelarPorUsuario($id, $usuario_id) {
        $stmt = $this->db->prepare("UPDATE citas SET estado = 'cancelada' WHERE id = ? AND usuario_id = ? AND estado != 'completada'");
        return $stmt->execute([$id, $usuario_id]);
    }
}