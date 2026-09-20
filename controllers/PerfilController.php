<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Usuario.php';

class PerfilController
{
    private $usuarioModel;

    public function __construct()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: /origins_games/auth/login");
            exit();
        }

        $this->usuarioModel = new Usuario();
    }


    /*
     * =========================================================
     * VER PERFIL
     * =========================================================
     */

    public function index()
    {
        $id = (int) $_SESSION['usuario']['id'];

        $usuario = $this->usuarioModel->findById($id);

        if (!$usuario) {
            header("Location: /origins_games/home");
            exit();
        }

        $pageTitle = "Mi Perfil - Origins Games";

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/usuarios/perfil.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }


    /*
     * =========================================================
     * EDITAR PERFIL
     * =========================================================
     */

    public function editar()
    {
        $id = (int) $_SESSION['usuario']['id'];

        $error = null;
        $success = null;

        $usuario = $this->usuarioModel->findById($id);

        if (!$usuario) {
            header("Location: /origins_games/home");
            exit();
        }


        /*
         * =====================================================
         * PROCESAR FORMULARIO
         * =====================================================
         */

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');


            /*
             * Validar nombre
             */

            if ($nombre === '') {

                $error = "El nombre es obligatorio.";

            }


            /*
             * Validar correo
             */

            elseif ($email === '') {

                $error = "El correo electrónico es obligatorio.";

            }

            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $error = "El correo electrónico no es válido.";

            }


            /*
             * Comprobar correo duplicado
             */

            else {

                $existing = $this->usuarioModel->findByEmail($email);

                if (
                    $existing &&
                    (int) $existing['id'] !== $id
                ) {

                    $error =
                        "El correo electrónico ya está en uso por otro usuario.";

                }

            }


            /*
             * Guardar cambios
             */

            if ($error === null) {

                $resultado = $this->usuarioModel->updateProfile(
                    $id,
                    [
                        'nombre' => $nombre,
                        'email' => $email,
                        'password' => $password,
                        'telefono' => $telefono,
                        'direccion' => $direccion
                    ]
                );


                if ($resultado) {

                    /*
                     * Actualizar datos básicos de la sesión
                     */

                    $_SESSION['usuario']['nombre'] = $nombre;
                    $_SESSION['usuario']['email'] = $email;


                    /*
                     * Volver a consultar el usuario
                     */

                    $usuario = $this->usuarioModel->findById($id);


                    $success =
                        "Perfil actualizado correctamente.";

                } else {

                    $error =
                        "No fue posible actualizar el perfil.";

                }
            }
        }


        /*
         * =====================================================
         * MOSTRAR VISTA
         * =====================================================
         */

        $pageTitle = "Editar Perfil - Origins Games";

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/usuarios/editar.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}