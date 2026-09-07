<?php
require_once __DIR__ . '/../config/database.php';

class Producto {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll($categoria_id = null) {
        if ($categoria_id) {
            $stmt = $this->db->prepare("SELECT p.*, c.nombre AS categoria_nombre FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE p.categoria_id = :cat ORDER BY p.id DESC");
            $stmt->execute([':cat' => $categoria_id]);
        } else {
            $stmt = $this->db->query("SELECT p.*, c.nombre AS categoria_nombre FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id ORDER BY p.id DESC");
        }
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.nombre AS categoria_nombre FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE p.id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, imagen) 
                VALUES (:categoria_id, :nombre, :descripcion, :precio, :stock, :imagen)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':categoria_id' => $data['categoria_id'],
            ':nombre'       => $data['nombre'],
            ':descripcion'  => $data['descripcion'],
            ':precio'       => $data['precio'],
            ':stock'        => $data['stock'],
            ':imagen'       => $data['imagen'] ?? 'default.jpg'
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE productos SET categoria_id = :categoria_id, nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock, imagen = :imagen WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':categoria_id' => $data['categoria_id'],
            ':nombre'       => $data['nombre'],
            ':descripcion'  => $data['descripcion'],
            ':precio'       => $data['precio'],
            ':stock'        => $data['stock'],
            ':imagen'       => $data['imagen'],
            ':id'           => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function updateStock($id, $cantidadComprada) {
        $stmt = $this->db->prepare("UPDATE productos SET stock = stock - :cant WHERE id = :id AND stock >= :cant");
        return $stmt->execute([':cant' => $cantidadComprada, ':id' => $id]);
    }
}