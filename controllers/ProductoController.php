<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/AuthMiddleware.php';
require_once __DIR__ . '/../models/Favorito.php';

class ProductoController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // Catálogo público
    public function index()
    {
        // Categoría seleccionada
        $cat = $_GET['categoria'] ?? 'todos';

        // Búsqueda por nombre
        $busqueda = trim($_GET['busqueda'] ?? '');

        // Ordenamiento
        $orden = $_GET['orden'] ?? 'recientes';

        /*
         * Validamos la categoría para evitar valores
         * diferentes a los que maneja actualmente
         * el proyecto.
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
         * Convertimos la categoría en su ID actual.
         *
         * accesorios = 1
         * consolas   = 2
         * juegos     = 3
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
         * Ordenamientos permitidos.
         * Solo usamos valores definidos aquí
         * para evitar introducir SQL directamente
         * desde $_GET.
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
         * Construimos la consulta utilizando
         * parámetros preparados.
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
         *
         * Si el usuario está conectado, obtenemos los IDs
         * de los productos que tiene guardados.
         *
         * Si no está conectado, simplemente dejamos el arreglo
         * vacío para que el catálogo siga siendo público.
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


    // Inventario del administrador
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


    // Crear / actualizar producto
    public function guardar()
    {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /origins_games/producto/admin');
            exit();
        }

        try {

            $id = !empty($_POST['id'])
                ? (int) $_POST['id']
                : null;

            $nombre = trim($_POST['nombre'] ?? '');

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


            // Validaciones
            if ($nombre === '') {
                die('El nombre del producto es obligatorio.');
            }

            if ($precio < 0) {
                die('El precio no puede ser negativo.');
            }

            if ($stock < 0) {
                $stock = 0;
            }


            // Imagen actual
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


            // Nueva imagen
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
                }
            }


            // Actualizar
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

            }

            // Crear
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
            }


            header(
                'Location: /origins_games/producto/admin'
            );

            exit();

        } catch (PDOException $e) {

            die(
                "Error al guardar en la base de datos: " .
                htmlspecialchars($e->getMessage())
            );
        }
    }


    // Eliminar producto
    public function eliminar()
    {
        AuthMiddleware::requireAdmin();

        $id = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;

        if ($id > 0) {

            $stmt = $this->db->prepare(
                "DELETE FROM productos
                 WHERE id = ?"
            );

            $stmt->execute([$id]);
        }

        header(
            'Location: /origins_games/producto/admin'
        );

        exit();
    }
}