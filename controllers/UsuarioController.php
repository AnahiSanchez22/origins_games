<?php
require_once "models/Usuario.php";
require_once "config/AuthMiddleware.php";

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        // Solo el Administrador puede gestionar usuarios
        AuthMiddleware::requireAdmin();
        $this->usuarioModel = new Usuario();
    }

    // Listar todos los usuarios
    public function index() {
        $usuarios = $this->usuarioModel->getAll();
        $pageTitle = "Gestión de Usuarios - Admin";
        require_once "views/layouts/header.php";
        require_once "views/usuarios/index.php";
        require_once "views/layouts/footer.php";
    }

    // Crear nuevo usuario (Admin)
    public function create() {
        $error = null;
        $roles = $this->usuarioModel->getRoles();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $rol_id = intval($_POST['rol_id'] ?? 2);
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');

            if (empty($nombre) || empty($email) || empty($password)) {
                $error = "Nombre, email y contraseña son obligatorios.";
            } elseif ($this->usuarioModel->findByEmail($email)) {
                $error = "El correo ya está registrado.";
            } else {
                $this->usuarioModel->create([
                    'rol_id' => $rol_id,
                    'nombre' => $nombre,
                    'email' => $email,
                    'password' => $password,
                    'telefono' => $telefono,
                    'direccion' => $direccion
                ]);
                header("Location: /origins_games/usuario");
                exit();
            }
        }

        $pageTitle = "Crear Usuario - Admin";
        require_once "views/layouts/header.php";
        require_once "views/usuarios/create.php";
        require_once "views/layouts/footer.php";
    }

    // Editar usuario
    public function edit($id = null) {
        if (!$id) {
            header("Location: /origins_games/usuario");
            exit();
        }

        $usuario = $this->usuarioModel->findById($id);
        if (!$usuario) {
            header("Location: /origins_games/usuario");
            exit();
        }

        $roles = $this->usuarioModel->getRoles();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $rol_id = intval($_POST['rol_id'] ?? 2);
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');

            $existing = $this->usuarioModel->findByEmail($email);
            if ($existing && $existing['id'] != $id) {
                $error = "El email ingresado ya pertenece a otro usuario.";
            } else {
                $this->usuarioModel->update($id, [
                    'rol_id' => $rol_id,
                    'nombre' => $nombre,
                    'email' => $email,
                    'password' => $password,
                    'telefono' => $telefono,
                    'direccion' => $direccion
                ]);
                header("Location: /origins_games/usuario");
                exit();
            }
        }

        $pageTitle = "Editar Usuario - Admin";
        require_once "views/layouts/header.php";
        require_once "views/usuarios/edit.php";
        require_once "views/layouts/footer.php";
    }

    // Eliminar usuario
    public function delete($id = null) {
        if ($id && $id != $_SESSION['usuario']['id']) { // No auto-eliminación
            $this->usuarioModel->delete($id);
        }
        header("Location: /origins_games/usuario");
        exit();
    }
}