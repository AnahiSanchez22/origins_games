<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ProductoController {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=origins_games;charset=utf8", "root", "");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // Listar productos en la tienda
    public function index() {
        $cat = $_GET['categoria'] ?? 'todos';

        if ($cat === 'todos') {
            $stmt = $this->db->query("SELECT * FROM productos ORDER BY id DESC");
        } else {
            $stmt = $this->db->prepare("SELECT * FROM productos WHERE categoria_id = ? ORDER BY id DESC");
            $catId = ($cat === 'accesorios') ? 1 : (($cat === 'consolas') ? 2 : 3);
            $stmt->execute([$catId]);
        }
        
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/productos/index.php';
    }

    // Listar y gestionar productos en el panel admin
    public function admin() {
        $stmt = $this->db->query("SELECT * FROM productos ORDER BY id DESC");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/productos/admin.php';
    }

    // Crear o Actualizar Producto
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? null;
                $nombre = trim($_POST['nombre'] ?? '');
                $categoria_id = !empty($_POST['categoria_id']) ? intval($_POST['categoria_id']) : NULL;
                $precio = floatval($_POST['precio'] ?? 0);
                $descripcion = trim($_POST['descripcion'] ?? '');

                // Obtener la imagen previa si es edición
                $imagenActual = 'default.jpg';
                if ($id) {
                    $stmtImg = $this->db->prepare("SELECT imagen FROM productos WHERE id = ?");
                    $stmtImg->execute([$id]);
                    $prodActual = $stmtImg->fetch(PDO::FETCH_ASSOC);
                    if ($prodActual && !empty($prodActual['imagen'])) {
                        $imagenActual = $prodActual['imagen'];
                    }
                }

                // Subir nueva imagen si se seleccionó una
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $nombreImg = time() . '_' . uniqid() . '.' . $ext;
                    $dirSubida = __DIR__ . '/../public/uploads/';

                    if (!file_exists($dirSubida)) {
                        mkdir($dirSubida, 0777, true);
                    }

                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $dirSubida . $nombreImg)) {
                        $imagenActual = $nombreImg;
                    }
                }

                // Ejecutar UPDATE o INSERT
                if ($id) {
                    $stmt = $this->db->prepare("UPDATE productos SET nombre = ?, categoria_id = ?, precio = ?, descripcion = ?, imagen = ? WHERE id = ?");
                    $stmt->execute([$nombre, $categoria_id, $precio, $descripcion, $imagenActual, $id]);
                } else {
                    $stmt = $this->db->prepare("INSERT INTO productos (nombre, categoria_id, precio, descripcion, imagen) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$nombre, $categoria_id, $precio, $descripcion, $imagenActual]);
                }

                header('Location: /origins_games/producto/admin');
                exit();

            } catch (PDOException $e) {
                die("Error al guardar en la base de datos: " . $e->getMessage());
            }
        }
    }

    // Eliminar producto
    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->execute([$id]);
        }
        header('Location: /origins_games/producto/admin');
        exit();
    }
}