<?php

require_once "models/Usuario.php";
require_once "config/AuthMiddleware.php";


class UsuarioController
{
    private $usuarioModel;


    public function __construct()
    {
        // Solo Administradores pueden gestionar usuarios
        AuthMiddleware::requireAdmin();

        $this->usuarioModel = new Usuario();
    }


    /* =========================================================
       LISTAR USUARIOS
       ========================================================= */

    public function index()
    {
        $usuarios = $this->usuarioModel->getAll();

        $pageTitle = "Gestión de Usuarios - Admin";

        require_once "views/layouts/header.php";
        require_once "views/usuarios/index.php";
        require_once "views/layouts/footer.php";
    }


    /* =========================================================
       CREAR USUARIO
       ========================================================= */

    public function create()
    {
        $error = null;

        $roles = $this->usuarioModel->getRoles();


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $rol_id = intval($_POST['rol_id'] ?? 2);

            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');


            /* Validaciones */

            if (
                empty($nombre) ||
                empty($email) ||
                empty($password)
            ) {

                $error = "Nombre, email y contraseña son obligatorios.";

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $error = "El correo electrónico no es válido.";

            } elseif ($this->usuarioModel->findByEmail($email)) {

                $error = "El correo ya está registrado.";

            } else {

                $resultado = $this->usuarioModel->create([
                    'rol_id' => $rol_id,
                    'nombre' => $nombre,
                    'email' => $email,
                    'password' => $password,
                    'telefono' => $telefono,
                    'direccion' => $direccion
                ]);


                if ($resultado) {

                    header("Location: /origins_games/usuario");
                    exit();

                } else {

                    $error = "No fue posible crear el usuario.";

                }
            }
        }


        $pageTitle = "Crear Usuario - Admin";

        require_once "views/layouts/header.php";
        require_once "views/usuarios/create.php";
        require_once "views/layouts/footer.php";
    }


    /* =========================================================
       EDITAR USUARIO
       ========================================================= */

    public function edit($id = null)
    {
        /*
         * El ID llega mediante:
         *
         * /usuario/edit?id=6
         */

        $id = intval($id);


        if ($id <= 0) {

            header("Location: /origins_games/usuario");
            exit();

        }


        /* Buscar usuario */

        $usuario = $this->usuarioModel->findById($id);


        if (!$usuario) {

            header("Location: /origins_games/usuario");
            exit();

        }


        $roles = $this->usuarioModel->getRoles();

        $error = null;


        /* =====================================================
           PROCESAR FORMULARIO
           ===================================================== */

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');

            $password = trim($_POST['password'] ?? '');

            $rol_id = intval($_POST['rol_id'] ?? 2);

            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');


            /* Validaciones */

            if (empty($nombre)) {

                $error = "El nombre es obligatorio.";

            } elseif (empty($email)) {

                $error = "El correo electrónico es obligatorio.";

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $error = "El correo electrónico no es válido.";

            } else {

                /*
                 * Comprobar que el email no pertenezca
                 * a otro usuario.
                 */

                $existing = $this->usuarioModel->findByEmail($email);


                if (
                    $existing &&
                    $existing['id'] != $id
                ) {

                    $error = "El email ingresado ya pertenece a otro usuario.";

                } else {

                    $resultado = $this->usuarioModel->update(
                        $id,
                        [
                            'rol_id' => $rol_id,
                            'nombre' => $nombre,
                            'email' => $email,
                            'password' => $password,
                            'telefono' => $telefono,
                            'direccion' => $direccion
                        ]
                    );


                    if ($resultado) {

                        /*
                         * Si el administrador está editando
                         * su propio usuario, actualizamos también
                         * los datos básicos de la sesión.
                         */

                        if (
                            isset($_SESSION['usuario']['id']) &&
                            $_SESSION['usuario']['id'] == $id
                        ) {

                            $_SESSION['usuario']['nombre'] = $nombre;
                            $_SESSION['usuario']['email'] = $email;
                            $_SESSION['usuario']['rol_id'] = $rol_id;

                        }


                        header("Location: /origins_games/usuario");
                        exit();

                    } else {

                        $error = "No fue posible actualizar el usuario.";

                    }
                }
            }
        }


        /* =====================================================
           MOSTRAR FORMULARIO
           ===================================================== */

        $pageTitle = "Editar Usuario - Admin";

        require_once "views/layouts/header.php";
        require_once "views/usuarios/edit.php";
        require_once "views/layouts/footer.php";
    }


    /* =========================================================
       ELIMINAR USUARIO
       ========================================================= */

    public function delete($id = null)
    {
        $id = intval($id);


        /*
         * No permitir ID inválido.
         */

        if ($id <= 0) {

            header("Location: /origins_games/usuario");
            exit();

        }


        /*
         * No permitir que el administrador
         * se elimine a sí mismo.
         */

        if (
            isset($_SESSION['usuario']['id']) &&
            $id == $_SESSION['usuario']['id']
        ) {

            header("Location: /origins_games/usuario");
            exit();

        }


        /*
         * Comprobar que el usuario exista
         * antes de eliminarlo.
         */

        $usuario = $this->usuarioModel->findById($id);


        if ($usuario) {

            $this->usuarioModel->delete($id);

        }


        /*
         * Regresar al listado.
         */

        header("Location: /origins_games/usuario");
        exit();
    }
}