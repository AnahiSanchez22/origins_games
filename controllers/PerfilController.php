<?php
require_once "models/Usuario.php";

class PerfilController {
    private $usuarioModel;

    public function __construct() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /origins_games/auth/login");
            exit();
        }
        $this->usuarioModel = new Usuario();
    }

    // Ver perfil del usuario actual
    public function index() {
        $id = $_SESSION['usuario']['id'];
        $usuario = $this->usuarioModel->findById($id);
        $pageTitle = "Mi Perfil - Origins Games";
        
        require_once "views/layouts/header.php";
        require_once "views/usuario/perfil.php";
        require_once "views/layouts/footer.php";
    }

    // Editar perfil del usuario actual
    public function editar() {
        $id = $_SESSION['usuario']['id'];
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');

            $existing = $this->usuarioModel->findByEmail($email);
            if ($existing && $existing['id'] != $id) {
                $error = "El correo electrónico ya está en uso por otro usuario.";
            } else {
                $this->usuarioModel->updateProfile($id, [
                    'nombre' => $nombre,
                    'email' => $email,
                    'password' => $password,
                    'telefono' => $telefono,
                    'direccion' => $direccion
                ]);
                
                $_SESSION['usuario']['nombre'] = $nombre;
                $_SESSION['usuario']['email'] = $email;

                $success = "Perfil actualizado correctamente.";
            }
        }

        $usuario = $this->usuarioModel->findById($id);
        $pageTitle = "Editar Perfil - Origins Games";

        require_once "views/layouts/header.php";
        require_once "views/usuario/editar.php";
        require_once "views/layouts/footer.php";
    }
}