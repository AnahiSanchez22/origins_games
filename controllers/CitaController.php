<?php

require_once "models/Cita.php";
require_once "config/AuthMiddleware.php";

class CitaController
{
    private $citaModel;

    public function __construct()
    {
        AuthMiddleware::requireLogin();
        $this->citaModel = new Cita();
    }

    public function index()
    {
        $citas = $this->citaModel->getByUsuario($_SESSION['usuario']['id']);
        $servicios = $this->citaModel->getServicios();

        $pageTitle = "Mis Citas de Mantenimiento - Origins Games";

        require_once 'views/layouts/header.php';
        require_once 'views/citas/index.php';
        require_once 'views/layouts/footer.php';
    }

    public function guardar()
    {
        $this->agendar();
    }

    public function agendar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /origins_games/cita");
            exit();
        }

        $servicioId = filter_var(
            $_POST['servicio_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        $tipoEquipo = trim($_POST['tipo_equipo'] ?? '');
        $fechaCita = trim($_POST['fecha_cita'] ?? '');
        $horaCita = trim($_POST['hora_cita'] ?? '');
        $observaciones = trim($_POST['observaciones'] ?? '');

        // Validar que el servicio exista.
        $servicios = $this->citaModel->getServicios();

        $idsServicios = array_column($servicios, 'id');

        if (
            $servicioId === false ||
            !in_array($servicioId, $idsServicios, true)
        ) {
            header("Location: /origins_games/cita");
            exit();
        }

        // Validar que el equipo no esté vacío.
        if ($tipoEquipo === '') {
            header("Location: /origins_games/cita");
            exit();
        }

        // Validar formato de fecha.
        $fecha = DateTime::createFromFormat('!Y-m-d', $fechaCita);
        $erroresFecha = DateTime::getLastErrors();

        if (
            !$fecha ||
            $fecha->format('Y-m-d') !== $fechaCita ||
            ($erroresFecha !== false &&
                ($erroresFecha['warning_count'] > 0 ||
                 $erroresFecha['error_count'] > 0))
        ) {
            header("Location: /origins_games/cita");
            exit();
        }

        // No permitir fechas anteriores al día actual.
        if ($fechaCita < date('Y-m-d')) {
            header("Location: /origins_games/cita");
            exit();
        }

        // Validar formato de hora.
        $hora = DateTime::createFromFormat('!H:i', $horaCita);
        $erroresHora = DateTime::getLastErrors();

        if (
            !$hora ||
            $hora->format('H:i') !== $horaCita ||
            ($erroresHora !== false &&
                ($erroresHora['warning_count'] > 0 ||
                 $erroresHora['error_count'] > 0))
        ) {
            header("Location: /origins_games/cita");
            exit();
        }

        // Si la cita es hoy, no permitir horas anteriores o iguales a la actual.
        if (
            $fechaCita === date('Y-m-d') &&
            $horaCita <= date('H:i')
        ) {
            header("Location: /origins_games/cita");
            exit();
        }

        // Comprobar que el horario no esté ocupado.
        if ($this->citaModel->horarioOcupado($fechaCita, $horaCita)) {
            header("Location: /origins_games/cita");
            exit();
        }

        // Registrar la cita.
        $this->citaModel->create([
            'usuario_id' => $_SESSION['usuario']['id'],
            'servicio_id' => $servicioId,
            'tipo_equipo' => $tipoEquipo,
            'fecha_cita' => $fechaCita,
            'hora_cita' => $horaCita,
            'observaciones' => $observaciones
        ]);

        header("Location: /origins_games/cita");
        exit();
    }

    public function cancelar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $citaId = filter_var(
                $_POST['id'] ?? null,
                FILTER_VALIDATE_INT
            );

            $usuarioId = $_SESSION['usuario']['id'];

            if ($citaId !== false && $citaId > 0) {
                $this->citaModel->cancelarPorUsuario(
                    $citaId,
                    $usuarioId
                );
            }
        }

        header("Location: /origins_games/cita");
        exit();
    }

    public function admin()
    {
        AuthMiddleware::requireAdmin();

        $citas = $this->citaModel->getAll();

        $pageTitle = "Gestión de Citas - Admin";

        require_once 'views/layouts/header.php';
        require_once 'views/citas/admin.php';
        require_once 'views/layouts/footer.php';
    }

    public function cambiarEstado($id = null)
    {
        AuthMiddleware::requireAdmin();

        // Los estados solo pueden modificarse mediante POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /origins_games/cita/admin");
            exit();
        }

        $citaId = filter_var(
            $_POST['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        $estado = $_POST['estado'] ?? null;

        $estadosPermitidos = [
            'pendiente',
            'confirmada',
            'completada',
            'cancelada'
        ];

        if (
            $citaId !== false &&
            $citaId > 0 &&
            in_array($estado, $estadosPermitidos, true)
        ) {
            $this->citaModel->updateEstado($citaId, $estado);
        }

        header("Location: /origins_games/cita/admin");
        exit();
    }
}