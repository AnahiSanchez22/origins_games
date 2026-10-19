
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

            // Buscar el usuario por su correo electrónico.
            $usuario = $this->usuarioModel->findByEmail($correo);

            // Verificar la contraseña almacenada mediante hash.
            if ($usuario && password_verify($password, $usuario['password'])) {

                // Guardar los datos del usuario en la sesión.
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'correo' => $usuario['email'],
                    'rol' => $usuario['rol_nombre'] ?? 'Cliente',
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
            $password = $_POST['password'] ?? '';
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');

            // Validar los campos obligatorios.
            if (empty($nombre) || empty($correo) || $password === '') {
                $error = "Por favor, completa todos los campos obligatorios.";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }

            // Comprobar que el correo tenga un formato válido.
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $error = "Ingresa un correo electrónico válido.";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }

            // Validar la aceptación de la política en el servidor.
            // El formulario debe enviar privacy_accept con valor 1.
            if (($_POST['privacy_accept'] ?? '') !== '1') {
                $error = "Debes aceptar la política de privacidad para registrarte.";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }

            // Verificar si el correo ya está registrado.
            if ($this->usuarioModel->findByEmail($correo)) {
                $error = "El correo ya está registrado.";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }

            // Crear la cuenta con el rol de cliente.
            // El modelo Usuario se encarga del almacenamiento
            // y del hash de la contraseña.
            $resultado = $this->usuarioModel->create([
                'rol_id' => 2,
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
