<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    // Iniciar Sesión
    public function login() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = trim($_POST['correo'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($correo) || empty($password)) {
                $error = "Por favor, completa todos los campos.";
                require_once __DIR__ . '/../views/auth/login.php';
                return;
            }

            // Usar el método del modelo que ya trae el rol unificado con la tabla roles
            $usuario = $this->usuarioModel->findByEmail($correo);

            // Verificación de contraseña encriptada
            if ($usuario && password_verify($password, $usuario['password'])) {
                
                // Guardar la sesión completa incluyendo el rol de la base de datos
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'correo' => $usuario['email'],
                    'rol' => $usuario['rol_nombre'] ?? 'Cliente', // Toma el nombre del rol (Admin / Cliente)
                    'rol_id' => $usuario['rol_id']
                ];

                header('Location: /origins_games/home');
                exit();
            } else {
                $error = "El correo o la contraseña son incorrectos.";
                require_once __DIR__ . '/../views/auth/login.php';
                return;
            }
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Registrar Usuario
    public function register() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');

            if (empty($nombre) || empty($correo) || empty($password)) {
                $error = "Por favor, completa todos los campos obligatorios.";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }

            // Verificar si el correo ya existe
            if ($this->usuarioModel->findByEmail($correo)) {
                $error = "El correo ya está registrado.";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }

            // Usar el método create del modelo para guardar todos los campos incluyendo teléfono
            $resultado = $this->usuarioModel->create([
                'rol_id' => 2, // 2 = Cliente por defecto
                'nombre' => $nombre,
                'email' => $correo,
                'password' => $password,
                'telefono' => $telefono,
                'direccion' => $direccion
            ]);

            if ($resultado) {
                header('Location: /origins_games/auth/login?success=1');
                exit();
            } else {
                $error = "Ocurrió un error al registrar la cuenta.";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    // Cerrar Sesión
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: /origins_games/auth/login');
        exit();
    }
}