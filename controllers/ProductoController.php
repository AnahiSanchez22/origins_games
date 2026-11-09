
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/AuthMiddleware.php';
require_once __DIR__ . '/../config/Flash.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Favorito.php';

class ProductoController
{
    private $db;
    private $productoModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->productoModel = new Producto();
    }

    // =========================================================
    // CATÁLOGO PÚBLICO
    // =========================================================

    public function index()
    {
        $cat = $_GET['categoria'] ?? 'todos';
        $busqueda = trim($_GET['busqueda'] ?? '');
        $orden = $_GET['orden'] ?? 'recientes';

        // Categorías permitidas.
        $categoriasValidas = [
            'todos',
            'accesorios',
            'consolas',
            'juegos'
        ];

        if (!in_array($cat, $categoriasValidas, true)) {
            $cat = 'todos';
        }

        // Identificadores de las categorías.
        $catId = null;

        if ($cat === 'accesorios') {
            $catId = 1;
        } elseif ($cat === 'consolas') {
            $catId = 2;
        } elseif ($cat === 'juegos') {
            $catId = 3;
        }

        // Ordenamientos permitidos.
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

        // Construcción de la consulta.
        $sql = "SELECT *
                FROM productos
                WHERE 1 = 1";

        $parametros = [];

        if ($catId !== null) {
            $sql .= " AND categoria_id = ?";
            $parametros[] = $catId;
        }

        if ($busqueda !== '') {
            // Limitar la longitud de la búsqueda.
            $busqueda = mb_substr($busqueda, 0, 100);

            $sql .= " AND nombre LIKE ?";
            $parametros[] = '%' . $busqueda . '%';
        }

        $sql .= " ORDER BY " . $orderBy;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);

        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // =====================================================
        // FAVORITOS DEL USUARIO
        // =====================================================

        $favoritosIds = [];

        if (isset($_SESSION['usuario']['id'])) {

            $usuario_id = (int) $_SESSION['usuario']['id'];

            $favoritoModel = new Favorito();

            $favoritos = $favoritoModel->obtenerPorUsuario(
                $usuario_id
            );

            foreach ($favoritos as $favorito) {

                if (isset($favorito['id'])) {
                    $favoritosIds[] = (int) $favorito['id'];
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

        // Solo se permiten solicitudes POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $this->mensaje(
                'Solicitud no válida.',
                'error'
            );
        }

        try {

            // =================================================
            // RECIBIR DATOS
            // =================================================

            $id = null;

            if (isset($_POST['id']) && $_POST['id'] !== '') {

                $id = $this->validarId($_POST['id']);

                if ($id === false) {
                    $this->mensaje(
                        'El identificador del producto no es válido.',
                        'error'
                    );
                }
            }

            $nombre = $_POST['nombre'] ?? '';

            $categoria_id = $_POST['categoria_id'] ?? '';

            $precio = $_POST['precio'] ?? '';

            $stock = $_POST['stock'] ?? '';

            $descripcion = $_POST['descripcion'] ?? '';

            // =================================================
            // VALIDAR NOMBRE
            // =================================================

            if (
                !is_string($nombre)
                || trim($nombre) === ''
            ) {
                $this->mensaje(
                    'El nombre del producto es obligatorio.',
                    'error'
                );
            }

            $nombre = trim($nombre);

            if (mb_strlen($nombre) > 150) {
                $this->mensaje(
                    'El nombre no puede superar los 150 caracteres.',
                    'error'
                );
            }

            // =================================================
            // VALIDAR CATEGORÍA
            // =================================================

            $categoria_id = $this->validarId($categoria_id);

            if ($categoria_id === false) {
                $this->mensaje(
                    'Debes seleccionar una categoría válida.',
                    'error'
                );
            }

            // =================================================
            // VALIDAR PRECIO
            // =================================================

            // Se aceptan números enteros o con máximo
            // dos posiciones decimales.
            if (
                !is_scalar($precio)
                || !preg_match(
                    '/^\d+(?:\.\d{1,2})?$/',
                    trim((string) $precio)
                )
                || (float) $precio <= 0
            ) {
                $this->mensaje(
                    'Ingresa un precio válido mayor que cero.',
                    'error'
                );
            }

            $precio = trim((string) $precio);

            // Los valores se almacenan numéricamente en COP.
            // No se agrega el símbolo $ a la base de datos.

            // =================================================
            // VALIDAR STOCK
            // =================================================

            if (
                !is_scalar($stock)
                || filter_var(
                    $stock,
                    FILTER_VALIDATE_INT
                ) === false
                || (int) $stock < 0
            ) {
                $this->mensaje(
                    'El inventario debe ser un número entero igual o mayor que cero.',
                    'error'
                );
            }

            $stock = (int) $stock;

            // =================================================
            // VALIDAR DESCRIPCIÓN
            // =================================================

            if (!is_string($descripcion)) {
                $this->mensaje(
                    'La descripción no es válida.',
                    'error'
                );
            }

            $descripcion = trim($descripcion);

            if (mb_strlen($descripcion) > 2000) {
                $this->mensaje(
                    'La descripción no puede superar los 2000 caracteres.',
                    'error'
                );
            }

            // =================================================
            // COMPROBAR SI EL PRODUCTO EXISTE
            // =================================================

            $productoActual = null;

            if ($id !== null) {

                $productoActual = $this->productoModel->findById($id);

                if (!$productoActual) {
                    $this->mensaje(
                        'El producto que intentas actualizar no existe.',
                        'error'
                    );
                }
            }

            // =================================================
            // CONSERVAR IMAGEN ACTUAL
            // =================================================

            $imagenActual = $productoActual['imagen']
                ?? 'default.jpg';

            // =================================================
            // VALIDAR Y SUBIR NUEVA IMAGEN
            // =================================================

            $imagenSubida = null;

            if (
                isset($_FILES['imagen'])
                && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE
            ) {

                $archivo = $_FILES['imagen'];

                // Comprobar errores de carga.
                if ($archivo['error'] !== UPLOAD_ERR_OK) {
                    $this->mensaje(
                        'Ocurrió un problema al cargar la imagen.',
                        'error'
                    );
                }

                // Máximo 5 MB.
                $tamanoMaximo = 5 * 1024 * 1024;

                if (
                    !isset($archivo['size'])
                    || $archivo['size'] <= 0
                    || $archivo['size'] > $tamanoMaximo
                ) {
                    $this->mensaje(
                        'La imagen no puede superar los 5 MB.',
                        'error'
                    );
                }

                // Comprobar que sea una imagen real.
                $informacionImagen = @getimagesize(
                    $archivo['tmp_name']
                );

                if ($informacionImagen === false) {
                    $this->mensaje(
                        'El archivo seleccionado no es una imagen válida.',
                        'error'
                    );
                }

                // Tipos MIME y extensiones permitidos.
                $tiposPermitidos = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/gif'  => 'gif',
                    'image/webp' => 'webp',
                    'image/avif' => 'avif'
                ];

                $finfo = new finfo(FILEINFO_MIME_TYPE);

                $tipoMime = $finfo->file(
                    $archivo['tmp_name']
                );

                if (!isset($tiposPermitidos[$tipoMime])) {
                    $this->mensaje(
                        'Solo se permiten imágenes JPG, PNG, GIF, WEBP o AVIF.',
                        'error'
                    );
                }

                // Crear un nombre aleatorio.
                $extension = $tiposPermitidos[$tipoMime];

                $nombreImg = bin2hex(random_bytes(16))
                    . '.'
                    . $extension;

                // Directorio de almacenamiento.
                $dirSubida = __DIR__
                    . '/../public/uploads/';

                if (
                    !is_dir($dirSubida)
                    && !mkdir($dirSubida, 0755, true)
                    && !is_dir($dirSubida)
                ) {
                    $this->mensaje(
                        'No se pudo preparar la carpeta de imágenes.',
                        'error'
                    );
                }

                $rutaDestino = $dirSubida . $nombreImg;

                if (
                    !move_uploaded_file(
                        $archivo['tmp_name'],
                        $rutaDestino
                    )
                ) {
                    $this->mensaje(
                        'No se pudo guardar la imagen.',
                        'error'
                    );
                }

                $imagenActual = $nombreImg;
                $imagenSubida = $rutaDestino;
            }

            // =================================================
            // PREPARAR INFORMACIÓN DEL PRODUCTO
            // =================================================

            $datosProducto = [
                'nombre'       => $nombre,
                'categoria_id' => $categoria_id,
                'precio'       => $precio,
                'stock'        => $stock,
                'descripcion'  => $descripcion,
                'imagen'       => $imagenActual
            ];

            // =================================================
            // ACTUALIZAR PRODUCTO
            // =================================================

            if ($id !== null) {

                $resultado = $this->productoModel->update(
                    $id,
                    $datosProducto
                );

                if (!$resultado) {

                    // Eliminar la imagen nueva si no se guardó
                    // correctamente la información del producto.
                    if (
                        $imagenSubida !== null
                        && file_exists($imagenSubida)
                    ) {
                        unlink($imagenSubida);
                    }

                    $this->mensaje(
                        'No fue posible actualizar el producto. Revisa los datos.',
                        'error'
                    );
                }

                $this->mensaje(
                    'Producto actualizado correctamente.',
                    'success'
                );
            }

            // =================================================
            // CREAR PRODUCTO
            // =================================================

            else {

                $resultado = $this->productoModel->create(
                    $datosProducto
                );

                if (!$resultado) {

                    if (
                        $imagenSubida !== null
                        && file_exists($imagenSubida)
                    ) {
                        unlink($imagenSubida);
                    }

                    $this->mensaje(
                        'No fue posible registrar el producto. Revisa los datos.',
                        'error'
                    );
                }

                $this->mensaje(
                    'Producto creado correctamente.',
                    'success'
                );
            }

        } catch (PDOException $e) {

            // El detalle técnico se conserva en el registro del servidor.
            error_log(
                'Error al guardar producto: ' . $e->getMessage()
            );

            if (
                isset($imagenSubida)
                && $imagenSubida !== null
                && file_exists($imagenSubida)
            ) {
                unlink($imagenSubida);
            }

            $this->mensaje(
                'No fue posible guardar el producto. Verifica la información.',
                'error'
            );

        } catch (Throwable $e) {

            error_log(
                'Error inesperado en productos: ' . $e->getMessage()
            );

            if (
                isset($imagenSubida)
                && $imagenSubida !== null
                && file_exists($imagenSubida)
            ) {
                unlink($imagenSubida);
            }

            $this->mensaje(
                'Ocurrió un error al procesar el producto.',
                'error'
            );
        }
    }

    // =========================================================
    // ELIMINAR PRODUCTO
    // =========================================================

   
public function eliminar()
{
    AuthMiddleware::requireAdmin();

    // Impedir eliminaciones mediante GET u otros métodos.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->mensaje(
            'Método de solicitud no permitido.',
            'error'
        );
    }

    // Validar el ID recibido mediante POST.
    $id = $this->validarId(
        $_POST['id'] ?? null
    );

    if ($id === false) {
        $this->mensaje(
            'El producto seleccionado no es válido.',
            'error'
        );
    }

    try {

        // Comprobar que el producto exista.
        $producto = $this->productoModel->findById($id);

        if (!$producto) {
            $this->mensaje(
                'El producto no existe.',
                'error'
            );
        }

        // Intentar eliminar el producto.
        $resultado = $this->productoModel->delete($id);

        if ($resultado) {

            $this->mensaje(
                'Producto eliminado correctamente.',
                'success'
            );

        } else {

            $this->mensaje(
                'No fue posible eliminar el producto.',
                'error'
            );
        }

    } catch (PDOException $e) {

        error_log(
            'Error al eliminar producto: ' . $e->getMessage()
        );

        $this->mensaje(
            'No se pudo eliminar el producto. Puede estar relacionado con pedidos registrados.',
            'error'
        );
    }
}


    // =========================================================
    // VALIDAR IDENTIFICADORES
    // =========================================================

    private function validarId($id)
    {
        if (
            !is_scalar($id)
            || filter_var(
                $id,
                FILTER_VALIDATE_INT
            ) === false
        ) {
            return false;
        }

        $id = (int) $id;

        return $id > 0 ? $id : false;
    }

    // =========================================================
    // MOSTRAR MENSAJES Y REGRESAR AL INVENTARIO
    // =========================================================

    private function mensaje($texto, $tipo)
    {
        Flash::set($texto, $tipo);

        header(
            'Location: /origins_games/producto/admin'
        );

        exit();
    }
}
