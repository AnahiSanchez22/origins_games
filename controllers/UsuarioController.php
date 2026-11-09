
<?php

require_once "models/Usuario.php";
require_once "config/AuthMiddleware.php";
require_once "config/Flash.php";


class UsuarioController
{
    private $usuarioModel;


    public function __construct()
    {
        // Solo Administradores pueden gestionar usuarios
        AuthMiddleware::requireAdmin();

        $this->usuarioModel = new Usuario();
    }


    /*
     * =========================================================
     * VALIDAR QUE EL ROL EXISTA
     * =========================================================
     */

    private function rolValido($rolId, $roles)
    {
        if ($rolId <= 0) {
            return false;
        }

        foreach ($roles as $rol) {

            if ((int) $rol['id'] === $rolId) {
                return true;
            }

        }

        return false;
    }


    /*
     * =========================================================
     * VALIDAR CONTRASEÑA
     * =========================================================
     */

    private function passwordValido($password)
    {
        return preg_match(
            '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/',
            $password
        ) === 1;
    }


    /*
     * =========================================================
     * LISTAR USUARIOS
     * =========================================================
     */

    public function index()
    {
        $usuarios = $this->usuarioModel->getAll();

        $pageTitle = "Gestión de Usuarios - Admin";

        require_once "views/layouts/header.php";
        require_once "views/usuarios/index.php";
        require_once "views/layouts/footer.php";
    }


    /*
     * =========================================================
     * CREAR USUARIO
     * =========================================================
     */

    public function create()
    {
        $error = null;

        $roles = $this->usuarioModel->getRoles();


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $rolIdRecibido = filter_var(
                $_POST['rol_id'] ?? 2,
                FILTER_VALIDATE_INT
            );

            $rol_id = $rolIdRecibido !== false
                ? $rolIdRecibido
                : 0;

            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');


            /*
             * VALIDAR CAMPOS OBLIGATORIOS
             */

            if (
                $nombre === '' ||
                $email === '' ||
                $password === ''
            ) {

                $error = "Nombre, email y contraseña son obligatorios.";

            }


            /*
             * VALIDAR CORREO
             */

            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $error = "El correo electrónico no es válido.";

            }


            /*
             * VALIDAR CONTRASEÑA
             */

            elseif (!$this->passwordValido($password)) {

                $error =
                    "La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número.";

            }


            /*
             * VALIDAR ROL
             */

            elseif (!$this->rolValido($rol_id, $roles)) {

                $error = "El rol seleccionado no es válido.";

            }


            /*
             * COMPROBAR CORREO DUPLICADO
             */

            elseif ($this->usuarioModel->findByEmail($email)) {

                $error = "El correo ya está registrado.";

            }


            /*
             * GUARDAR USUARIO
             */

            else {

                $resultado = $this->usuarioModel->create([
                    'rol_id' => $rol_id,
                    'nombre' => $nombre,
                    'email' => $email,
                    'password' => $password,
                    'telefono' => $telefono,
                    'direccion' => $direccion
                ]);


                if ($resultado) {

                    Flash::set(
                        "Usuario creado correctamente.",
                        "success"
                    );

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


    /*
     * =========================================================
     * EDITAR USUARIO
     * =========================================================
     */

    public function edit($id = null)
    {
        /*
         * El ID llega mediante:
         *
         * /usuario/edit?id=6
         */

        $id = filter_var($id, FILTER_VALIDATE_INT);


        if ($id === false || $id === null || $id <= 0) {

            Flash::set(
                "Usuario no válido.",
                "error"
            );

            header("Location: /origins_games/usuario");
            exit();

        }


        /*
         * BUSCAR USUARIO
         */

        $usuario = $this->usuarioModel->findById($id);


        if (!$usuario) {

            Flash::set(
                "El usuario no existe.",
                "error"
            );

            header("Location: /origins_games/usuario");
            exit();

        }


        $roles = $this->usuarioModel->getRoles();

        $error = null;


        /*
         * =====================================================
         * PROCESAR FORMULARIO
         * =====================================================
         */

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');

            $password = trim($_POST['password'] ?? '');

            $rolIdRecibido = filter_var(
                $_POST['rol_id'] ?? 2,
                FILTER_VALIDATE_INT
            );

            $rol_id = $rolIdRecibido !== false
                ? $rolIdRecibido
                : 0;

            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');


            /*
             * VALIDAR NOMBRE
             */

            if ($nombre === '') {

                $error = "El nombre es obligatorio.";

            }


            /*
             * VALIDAR CORREO
             */

            elseif ($email === '') {

                $error = "El correo electrónico es obligatorio.";

            }

            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $error = "El correo electrónico no es válido.";

            }


            /*
             * VALIDAR ROL
             */

            elseif (!$this->rolValido($rol_id, $roles)) {

                $error = "El rol seleccionado no es válido.";

            }


            /*
             * VALIDAR CONTRASEÑA
             *
             * Si está vacía, se conserva la anterior.
             */

            elseif (
                $password !== '' &&
                !$this->passwordValido($password)
            ) {

                $error =
                    "La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número.";

            }


            /*
             * COMPROBAR CORREO DUPLICADO
             */

            else {

                $existing = $this->usuarioModel->findByEmail($email);


                if (
                    $existing &&
                    (int) $existing['id'] !== $id
                ) {

                    $error = "El email ingresado ya pertenece a otro usuario.";

                }
            }


            /*
             * GUARDAR CAMBIOS
             */

            if ($error === null) {

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
                     * su propio usuario, actualizar sesión.
                     */

                    if (
                        isset($_SESSION['usuario']['id']) &&
                        (int) $_SESSION['usuario']['id'] === $id
                    ) {

                        $_SESSION['usuario']['nombre'] = $nombre;
                        $_SESSION['usuario']['email'] = $email;
                        $_SESSION['usuario']['rol_id'] = $rol_id;

                    }


                    Flash::set(
                        "Usuario actualizado correctamente.",
                        "success"
                    );

                    header("Location: /origins_games/usuario");
                    exit();

                } else {

                    $error = "No fue posible actualizar el usuario.";

                }
            }
        }


        /*
         * =====================================================
         * MOSTRAR FORMULARIO
         * =====================================================
         */

        $pageTitle = "Editar Usuario - Admin";

        require_once "views/layouts/header.php";
        require_once "views/usuarios/editar-admin.php";
        require_once "views/layouts/footer.php";
    }


    /*
     * =========================================================
     * ELIMINAR USUARIO
     * =========================================================
     */

    public function delete($id = null)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);


        /*
         * NO PERMITIR ID INVÁLIDO
         */

        if ($id === false || $id === null || $id <= 0) {

            Flash::set(
                "Usuario no válido.",
                "error"
            );

            header("Location: /origins_games/usuario");
            exit();

        }


        /*
         * NO PERMITIR AUTOELIMINACIÓN
         */

        if (
            isset($_SESSION['usuario']['id']) &&
            $id == $_SESSION['usuario']['id']
        ) {

            Flash::set(
                "No puedes eliminar tu propio usuario administrador.",
                "error"
            );

            header("Location: /origins_games/usuario");
            exit();

        }


        /*
         * COMPROBAR QUE EL USUARIO EXISTA
         */

        $usuario = $this->usuarioModel->findById($id);


        if ($usuario) {

            $resultado = $this->usuarioModel->delete($id);

            if ($resultado) {

                Flash::set(
                    "Usuario eliminado correctamente.",
                    "success"
                );

            } else {

                Flash::set(
                    "No fue posible eliminar el usuario.",
                    "error"
                );

            }

        } else {

            Flash::set(
                "El usuario no existe.",
                "error"
            );

        }


        /*
         * REGRESAR AL LISTADO
         */

        header("Location: /origins_games/usuario");
        exit();
    }
}
