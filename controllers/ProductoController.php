<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/AuthMiddleware.php';
require_once __DIR__ . '/../config/Flash.php';
require_once __DIR__ . '/../models/Favorito.php';

class ProductoController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }


    // =========================================================
    // CATÁLOGO PÚBLICO
    // =========================================================

    public function index()
    {
        // Categoría seleccionada
        $cat = $_GET['categoria'] ?? 'todos';

        // Búsqueda por nombre
        $busqueda = trim($_GET['busqueda'] ?? '');

        // Ordenamiento
        $orden = $_GET['orden'] ?? 'recientes';


        /*
         * Validamos la categoría
         */

        $categoriasValidas = [
            'todos',
            'accesorios',
            'consolas',
            'juegos'
        ];

        if (!in_array($cat, $categoriasValidas, true)) {
            $cat = 'todos';
        }


        /*
         * Convertimos la categoría en su ID
         */

        $catId = null;

        if ($cat === 'accesorios') {
            $catId = 1;
        } elseif ($cat === 'consolas') {
            $catId = 2;
        } elseif ($cat === 'juegos') {
            $catId = 3;
        }


        /*
         * Ordenamientos permitidos
         */

        $ordenesPermitidos = [
            'recientes' => 'id DESC',
            'precio_asc' => 'precio ASC',
            'precio_desc' => 'precio DESC',
            'nombre_asc' => 'nombre ASC',
            'nombre_desc' => 'nombre DESC'
        ];

        if (!isset($ordenesPermitidos[$orden])) {
            $orden = 'recientes';
        }

        $orderBy = $ordenesPermitidos[$orden];


        /*
         * Consulta
         */

        $sql = "SELECT *
                FROM productos
                WHERE 1 = 1";

        $parametros = [];


        // Filtro por categoría
        if ($catId !== null) {
            $sql .= " AND categoria_id = ?";
            $parametros[] = $catId;
        }


        // Búsqueda por nombre
        if ($busqueda !== '') {
            $sql .= " AND nombre LIKE ?";
            $parametros[] = '%' . $busqueda . '%';
        }


        // Ordenamiento
        $sql .= " ORDER BY " . $orderBy;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);

        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);


        /*
         * =====================================================
         * FAVORITOS DEL USUARIO
         * =====================================================
         */

        $favoritosIds = [];

        if (isset($_SESSION['usuario']['id'])) {

            $usuario_id = (int) $_SESSION['usuario']['id'];

            $favoritoModel = new Favorito();

            $favoritos = $favoritoModel->obtenerPorUsuario(
                $usuario_id
            );

            foreach ($favoritos as $favorito) {

                if (isset($favorito['id'])) {

                    $favoritosIds[] =
                        (int) $favorito['id'];
                }
            }
        }


        require_once __DIR__ . '/../views/productos/index.php';
    }


    // =========================================================
    // INVENTARIO DEL ADMINISTRADOR
    // =========================================================

    public function admin()
    {
        AuthMiddleware::requireAdmin();

        $stmt = $this->db->query(
            "SELECT *
             FROM productos
             ORDER BY id DESC"
        );

        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/productos/admin.php';
    }


    // =========================================================
    // CREAR / ACTUALIZAR PRODUCTO
    // =========================================================

    public function guardar()
    {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            Flash::set(
                "Solicitud no válida.",
                "error"
            );

            header(
                'Location: /origins_games/producto/admin'
            );

            exit();
        }


        try {

            $id = !empty($_POST['id'])
                ? (int) $_POST['id']
                : null;

            $nombre = trim(
                $_POST['nombre'] ?? ''
            );

            $categoria_id = !empty($_POST['categoria_id'])
                ? (int) $_POST['categoria_id']
                : null;

            $precio = isset($_POST['precio'])
                ? (float) $_POST['precio']
                : 0;

            $stock = isset($_POST['stock'])
                ? (int) $_POST['stock']
                : 0;

            $descripcion = trim(
                $_POST['descripcion'] ?? ''
            );


            // =================================================
            // VALIDACIONES
            // =================================================

            if ($nombre === '') {

                Flash::set(
                    "El nombre del producto es obligatorio.",
                    "error"
                );

                header(
                    'Location: /origins_games/producto/admin'
                );

                exit();
            }


            if ($precio < 0) {

                Flash::set(
                    "El precio no puede ser negativo.",
                    "error"
                );

                header(
                    'Location: /origins_games/producto/admin'
                );

                exit();
            }


            if ($stock < 0) {
                $stock = 0;
            }


            // =================================================
            // IMAGEN ACTUAL
            // =================================================

            $imagenActual = 'default.jpg';

            if ($id) {

                $stmtImg = $this->db->prepare(
                    "SELECT imagen
                     FROM productos
                     WHERE id = ?"
                );

                $stmtImg->execute([$id]);

                $productoActual =
                    $stmtImg->fetch(PDO::FETCH_ASSOC);


                if (
                    $productoActual &&
                    !empty($productoActual['imagen'])
                ) {

                    $imagenActual =
                        $productoActual['imagen'];
                }
            }


            // =================================================
            // NUEVA IMAGEN
            // =================================================

            if (
                isset($_FILES['imagen']) &&
                $_FILES['imagen']['error'] === UPLOAD_ERR_OK
            ) {

                $ext = strtolower(
                    pathinfo(
                        $_FILES['imagen']['name'],
                        PATHINFO_EXTENSION
                    )
                );


                $extPermitidas = [
                    'jpg',
                    'jpeg',
                    'png',
                    'gif',
                    'webp',
                    'avif'
                ];


                if (in_array($ext, $extPermitidas, true)) {

                    $nombreImg =
                        time() .
                        '_' .
                        uniqid() .
                        '.' .
                        $ext;


                    $dirSubida =
                        __DIR__ .
                        '/../public/uploads/';


                    if (!file_exists($dirSubida)) {

                        mkdir(
                            $dirSubida,
                            0777,
                            true
                        );
                    }


                    if (
                        move_uploaded_file(
                            $_FILES['imagen']['tmp_name'],
                            $dirSubida . $nombreImg
                        )
                    ) {

                        $imagenActual = $nombreImg;
                    }

                } else {

                    Flash::set(
                        "El formato de imagen no es válido.",
                        "error"
                    );

                    header(
                        'Location: /origins_games/producto/admin'
                    );

                    exit();
                }
            }


            // =================================================
            // ACTUALIZAR PRODUCTO
            // =================================================

            if ($id) {

                $stmt = $this->db->prepare(
                    "UPDATE productos
                     SET
                        nombre = ?,
                        categoria_id = ?,
                        precio = ?,
                        stock = ?,
                        descripcion = ?,
                        imagen = ?
                     WHERE id = ?"
                );


                $stmt->execute([
                    $nombre,
                    $categoria_id,
                    $precio,
                    $stock,
                    $descripcion,
                    $imagenActual,
                    $id
                ]);


                Flash::set(
                    "Producto actualizado correctamente.",
                    "success"
                );

            }


            // =================================================
            // CREAR PRODUCTO
            // =================================================

            else {

                $stmt = $this->db->prepare(
                    "INSERT INTO productos
                    (
                        nombre,
                        categoria_id,
                        precio,
                        stock,
                        descripcion,
                        imagen
                    )
                    VALUES (?, ?, ?, ?, ?, ?)"
                );


                $stmt->execute([
                    $nombre,
                    $categoria_id,
                    $precio,
                    $stock,
                    $descripcion,
                    $imagenActual
                ]);


                Flash::set(
                    "Producto creado correctamente.",
                    "success"
                );
            }


            // =================================================
            // REGRESAR AL INVENTARIO
            // =================================================

            header(
                'Location: /origins_games/producto/admin'
            );

            exit();


        } catch (PDOException $e) {

            Flash::set(
                "No fue posible guardar el producto.",
                "error"
            );

            header(
                'Location: /origins_games/producto/admin'
            );

            exit();
        }
    }


    // =========================================================
    // ELIMINAR PRODUCTO
    // =========================================================

    public function eliminar()
    {
        AuthMiddleware::requireAdmin();


        $id = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;


        if ($id <= 0) {

            Flash::set(
                "Producto no válido.",
                "error"
            );

            header(
                'Location: /origins_games/producto/admin'
            );

            exit();
        }


        try {

            $stmt = $this->db->prepare(
                "DELETE FROM productos
                 WHERE id = ?"
            );

            $stmt->execute([$id]);


            if ($stmt->rowCount() > 0) {

                Flash::set(
                    "Producto eliminado correctamente.",
                    "success"
                );

            } else {

                Flash::set(
                    "El producto no existe.",
                    "error"
                );
            }


        } catch (PDOException $e) {

            Flash::set(
                "No fue posible eliminar el producto.",
                "error"
            );
        }


        header(
            'Location: /origins_games/producto/admin'
        );

        exit();
    }
}