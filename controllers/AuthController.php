<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AuthController {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=origins_games;charset=utf8", "root", "");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // Iniciar Sesión
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = trim($_POST['correo'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($correo) || empty($password)) {
                $error = "Por favor, completa todos los campos.";
                require_once __DIR__ . '/../views/auth/login.php';
                return;
            }

            // Buscar por columna 'email' en la BD
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$correo]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificación de contraseña encriptada
            if ($usuario && password_verify($password, $usuario['password'])) {
                
                // Guardar la sesión con compatibilidad para AuthMiddleware (rol y rol_id)
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'] ?? $usuario['id_usuario'] ?? null,
                    'nombre' => $usuario['nombre'],
                    'correo' => $usuario['email'] ?? $usuario['correo'],
                    'rol' => $usuario['rol'] ?? 'cliente',
                    'rol_id' => $usuario['rol_id'] ?? ($usuario['rol'] === 'admin' ? 1 : 2)
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($nombre) || empty($correo) || empty($password)) {
                $error = "Por favor, completa todos los campos.";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Verificar si el correo ya existe en la columna 'email'
            $check = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
            $check->execute([$correo]);

            if ($check->fetch()) {
                $error = "El correo ya está registrado.";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }

            // Inserción sin errores de columna faltante
            $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$nombre, $correo, $passwordHash])) {
                header('Location: /origins_games/auth/login');
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