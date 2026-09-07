<?php
require_once "models/Cita.php";
require_once "config/AuthMiddleware.php";

class CitaController {
    private $citaModel;

    public function __construct() {
        AuthMiddleware::requireLogin();
        $this->citaModel = new Cita();
    }

    public function index() {
        $citas = $this->citaModel->getByUsuario($_SESSION['usuario']['id']);
        $servicios = $this->citaModel->getServicios();

        $pageTitle = "Mis Citas de Mantenimiento - Origins Games";
        require_once 'views/layouts/header.php';
        require_once 'views/citas/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function guardar() {
        $this->agendar();
    }

    public function agendar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio_id = intval($_POST['servicio_id'] ?? 0);
            $tipo_equipo = trim($_POST['tipo_equipo'] ?? '');
            $fecha_cita = trim($_POST['fecha_cita'] ?? '');
            $hora_cita = trim($_POST['hora_cita'] ?? '');
            $observaciones = trim($_POST['observaciones'] ?? '');

            if ($servicio_id > 0 && !empty($tipo_equipo) && !empty($fecha_cita) && !empty($hora_cita)) {
                $this->citaModel->create([
                    'usuario_id' => $_SESSION['usuario']['id'],
                    'servicio_id' => $servicio_id,
                    'tipo_equipo' => $tipo_equipo,
                    'fecha_cita' => $fecha_cita,
                    'hora_cita' => $hora_cita,
                    'observaciones' => $observaciones
                ]);
            }

            header("Location: /origins_games/cita");
            exit();
        }
    }

    // Cancelar cita desde la vista de Cliente
    public function cancelar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $citaId = intval($_POST['id'] ?? 0);
            $usuarioId = $_SESSION['usuario']['id'];

            if ($citaId > 0) {
                // Cambia el estado a cancelada solo si la cita le pertenece al usuario
                $this->citaModel->cancelarPorUsuario($citaId, $usuarioId);
            }
        }

        header("Location: /origins_games/cita");
        exit();
    }

    public function admin() {
        AuthMiddleware::requireAdmin();
        $citas = $this->citaModel->getAll();

        $pageTitle = "Gestión de Citas - Admin";
        require_once 'views/layouts/header.php';
        require_once 'views/citas/admin.php';
        require_once 'views/layouts/footer.php';
    }

    public function cambiarEstado($id = null) {
        AuthMiddleware::requireAdmin();
        
        $citaId = $_POST['id'] ?? $_GET['id'] ?? $id;
        $estado = $_POST['estado'] ?? $_GET['estado'] ?? null;

        if ($citaId && $estado) {
            if (in_array($estado, ['pendiente', 'confirmada', 'completada', 'cancelada'])) {
                $this->citaModel->updateEstado($citaId, $estado);
            }
        }

        header("Location: /origins_games/cita/admin");
        exit();
    }
}