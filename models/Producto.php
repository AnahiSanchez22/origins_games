
<?php

require_once __DIR__ . '/../config/database.php';

class Producto
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // =====================================================
    // CONSULTAR TODOS LOS PRODUCTOS
    // =====================================================

    public function getAll($categoria_id = null)
    {
        if ($categoria_id !== null && $categoria_id !== '') {

            $categoria_id = $this->validarId($categoria_id);

            if ($categoria_id === false) {
                return [];
            }

            $stmt = $this->db->prepare(
                "SELECT p.*, c.nombre AS categoria_nombre
                 FROM productos p
                 INNER JOIN categorias c ON p.categoria_id = c.id
                 WHERE p.categoria_id = :cat
                 ORDER BY p.id DESC"
            );

            $stmt->execute([
                ':cat' => $categoria_id
            ]);

        } else {

            $stmt = $this->db->query(
                "SELECT p.*, c.nombre AS categoria_nombre
                 FROM productos p
                 INNER JOIN categorias c ON p.categoria_id = c.id
                 ORDER BY p.id DESC"
            );
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =====================================================
    // BUSCAR PRODUCTO POR ID
    // =====================================================

    public function findById($id)
    {
        $id = $this->validarId($id);

        if ($id === false) {
            return false;
        }

        $stmt = $this->db->prepare(
            "SELECT p.*, c.nombre AS categoria_nombre
             FROM productos p
             INNER JOIN categorias c ON p.categoria_id = c.id
             WHERE p.id = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =====================================================
    // CREAR PRODUCTO
    // =====================================================

    public function create($data)
    {
        if (!$this->validarDatos($data)) {
            return false;
        }

        $sql = "INSERT INTO productos
                (categoria_id, nombre, descripcion, precio, stock, imagen)
                VALUES
                (:categoria_id, :nombre, :descripcion, :precio, :stock, :imagen)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':categoria_id' => (int) $data['categoria_id'],
            ':nombre'       => trim($data['nombre']),
            ':descripcion'  => trim($data['descripcion'] ?? ''),
            ':precio'       => $data['precio'],
            ':stock'        => (int) $data['stock'],
            ':imagen'       => $data['imagen'] ?? 'default.jpg'
        ]);
    }

    // =====================================================
    // ACTUALIZAR PRODUCTO
    // =====================================================

    public function update($id, $data)
    {
        $id = $this->validarId($id);

        if ($id === false) {
            return false;
        }

        // Comprobar que el producto exista.
        $productoActual = $this->findById($id);

        if (!$productoActual) {
            return false;
        }

        if (!$this->validarDatos($data)) {
            return false;
        }

        // Si no se envía una imagen nueva, conservar la anterior.
        $imagen = $data['imagen'] ?? $productoActual['imagen'];

        if (!$this->validarImagen($imagen)) {
            return false;
        }

        $sql = "UPDATE productos
                SET categoria_id = :categoria_id,
                    nombre = :nombre,
                    descripcion = :descripcion,
                    precio = :precio,
                    stock = :stock,
                    imagen = :imagen
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':categoria_id' => (int) $data['categoria_id'],
            ':nombre'       => trim($data['nombre']),
            ':descripcion'  => trim($data['descripcion'] ?? ''),
            ':precio'       => $data['precio'],
            ':stock'        => (int) $data['stock'],
            ':imagen'       => $imagen,
            ':id'           => $id
        ]);
    }

    // =====================================================
    // ELIMINAR PRODUCTO
    // =====================================================

    public function delete($id)
    {
        $id = $this->validarId($id);

        if ($id === false) {
            return false;
        }

        $stmt = $this->db->prepare(
            "DELETE FROM productos WHERE id = :id"
        );

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    // =====================================================
    // ACTUALIZAR INVENTARIO
    // =====================================================

    public function updateStock($id, $cantidadComprada)
    {
        $id = $this->validarId($id);

        $cantidadComprada = $this->validarCantidad(
            $cantidadComprada
        );

        if (
            $id === false
            || $cantidadComprada === false
            || $cantidadComprada <= 0
        ) {
            return false;
        }

        $sql = "UPDATE productos
                SET stock = stock - :cant
                WHERE id = :id
                AND stock >= :cant";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':cant' => $cantidadComprada,
            ':id'   => $id
        ]);

        // Solo devuelve true si realmente se actualizó una fila.
        return $stmt->rowCount() > 0;
    }

    // =====================================================
    // VALIDAR DATOS DEL PRODUCTO
    // =====================================================

    private function validarDatos($data)
    {
        if (!is_array($data)) {
            return false;
        }

        // Nombre obligatorio.
        if (
            !isset($data['nombre'])
            || !is_string($data['nombre'])
            || trim($data['nombre']) === ''
        ) {
            return false;
        }

        // Categoría obligatoria y existente.
        $categoriaId = $this->validarId(
            $data['categoria_id'] ?? null
        );

        if (
            $categoriaId === false
            || !$this->categoriaExiste($categoriaId)
        ) {
            return false;
        }

        // Precio numérico, positivo y con máximo dos decimales.
        if (
            !isset($data['precio'])
            || !is_scalar($data['precio'])
        ) {
            return false;
        }

        $precio = trim((string) $data['precio']);

        if (
            !preg_match('/^\d+(?:\.\d{1,2})?$/', $precio)
            || (float) $precio <= 0
        ) {
            return false;
        }

        // Stock: debe ser un entero igual o mayor que cero.
        $stock = $this->validarCantidad(
            $data['stock'] ?? null
        );

        if ($stock === false) {
            return false;
        }

        // Descripción opcional, pero debe ser texto.
        if (
            isset($data['descripcion'])
            && !is_string($data['descripcion'])
        ) {
            return false;
        }

        // Imagen opcional.
        if (
            isset($data['imagen'])
            && !$this->validarImagen($data['imagen'])
        ) {
            return false;
        }

        return true;
    }

    // =====================================================
    // COMPROBAR QUE LA CATEGORÍA EXISTA
    // =====================================================

    private function categoriaExiste($id)
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM categorias WHERE id = :id LIMIT 1"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        return (bool) $stmt->fetchColumn();
    }

    // =====================================================
    // VALIDAR IDENTIFICADORES
    // =====================================================

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

    // =====================================================
    // VALIDAR CANTIDADES DE INVENTARIO
    // =====================================================

    private function validarCantidad($cantidad)
    {
        if (
            !is_scalar($cantidad)
            || filter_var(
                $cantidad,
                FILTER_VALIDATE_INT
            ) === false
        ) {
            return false;
        }

        $cantidad = (int) $cantidad;

        return $cantidad >= 0 ? $cantidad : false;
    }

    // =====================================================
    // VALIDAR NOMBRE DE IMAGEN
    // =====================================================

    private function validarImagen($imagen)
    {
        if (
            !is_string($imagen)
            || trim($imagen) === ''
            || basename($imagen) !== $imagen
            || preg_match('/[\x00-\x1F\x7F]/', $imagen)
        ) {
            return false;
        }

        return true;
    }
}
