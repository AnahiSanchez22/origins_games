<?php
require_once __DIR__ . '/../config/database.php';

class Categoria {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categorias ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($nombre, $descripcion) {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (:nombre, :descripcion)");
        return $stmt->execute([':nombre' => $nombre, ':descripcion' => $descripcion]);
    }
}