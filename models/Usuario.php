<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Buscar usuario por Email
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT u.*, r.nombre AS rol_nombre FROM usuarios u INNER JOIN roles r ON u.rol_id = r.id WHERE u.email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // Buscar usuario por ID
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT u.*, r.nombre AS rol_nombre FROM usuarios u INNER JOIN roles r ON u.rol_id = r.id WHERE u.id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Registrar nuevo cliente
    public function create($data) {
        $sql = "INSERT INTO usuarios (rol_id, nombre, email, password, telefono, direccion) 
                VALUES (:rol_id, :nombre, :email, :password, :telefono, :direccion)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':rol_id'    => $data['rol_id'] ?? 2, // 2 = Cliente por defecto
            ':nombre'    => $data['nombre'],
            ':email'     => $data['email'],
            ':password'  => password_hash($data['password'], PASSWORD_BCRYPT),
            ':telefono'  => $data['telefono'] ?? null,
            ':direccion' => $data['direccion'] ?? null
        ]);
    }

    // Obtener todos los usuarios (para Admin)
    public function getAll() {
        $stmt = $this->db->query("SELECT u.*, r.nombre AS rol_nombre FROM usuarios u INNER JOIN roles r ON u.rol_id = r.id ORDER BY u.id DESC");
        return $stmt->fetchAll();
    }

    // Actualizar usuario (Admin)
    public function update($id, $data) {
        if (!empty($data['password'])) {
            $sql = "UPDATE usuarios SET rol_id = :rol_id, nombre = :nombre, email = :email, password = :password, telefono = :telefono, direccion = :direccion WHERE id = :id";
            $params = [
                ':rol_id'    => $data['rol_id'],
                ':nombre'    => $data['nombre'],
                ':email'     => $data['email'],
                ':password'  => password_hash($data['password'], PASSWORD_BCRYPT),
                ':telefono'  => $data['telefono'],
                ':direccion' => $data['direccion'],
                ':id'        => $id
            ];
        } else {
            $sql = "UPDATE usuarios SET rol_id = :rol_id, nombre = :nombre, email = :email, telefono = :telefono, direccion = :direccion WHERE id = :id";
            $params = [
                ':rol_id'    => $data['rol_id'],
                ':nombre'    => $data['nombre'],
                ':email'     => $data['email'],
                ':telefono'  => $data['telefono'],
                ':direccion' => $data['direccion'],
                ':id'        => $id
            ];
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // Actualizar perfil del propio usuario (Sin cambiar rol)
    public function updateProfile($id, $data) {
        if (!empty($data['password'])) {
            $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, password = :password, telefono = :telefono, direccion = :direccion WHERE id = :id";
            $params = [
                ':nombre'    => $data['nombre'],
                ':email'     => $data['email'],
                ':password'  => password_hash($data['password'], PASSWORD_BCRYPT),
                ':telefono'  => $data['telefono'],
                ':direccion' => $data['direccion'],
                ':id'        => $id
            ];
        } else {
            $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, telefono = :telefono, direccion = :direccion WHERE id = :id";
            $params = [
                ':nombre'    => $data['nombre'],
                ':email'     => $data['email'],
                ':telefono'  => $data['telefono'],
                ':direccion' => $data['direccion'],
                ':id'        => $id
            ];
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // Eliminar usuario
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Obtener todos los roles
    public function getRoles() {
        $stmt = $this->db->query("SELECT * FROM roles");
        return $stmt->fetchAll();
    }
}