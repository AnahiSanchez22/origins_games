<?php

require_once __DIR__ . '/../config/Database.php';

class Favorito
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Agregar un producto a favoritos.
     */
    public function agregar($usuario_id, $producto_id)
    {
        $sql = "INSERT INTO favoritos (usuario_id, producto_id)
                VALUES (:usuario_id, :producto_id)";

        $stmt = $this->db->prepare($sql);

        try {
            return $stmt->execute([
                ':usuario_id'  => $usuario_id,
                ':producto_id' => $producto_id
            ]);
        } catch (PDOException $e) {

            // Evita error si ya estaba guardado
            if ($e->getCode() === '23000') {
                return true;
            }

            return false;
        }
    }

    /**
     * Quitar un producto de favoritos.
     */
    public function eliminar($usuario_id, $producto_id)
    {
        $sql = "DELETE FROM favoritos
                WHERE usuario_id = :usuario_id
                AND producto_id = :producto_id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':usuario_id'  => $usuario_id,
            ':producto_id' => $producto_id
        ]);
    }

    /**
     * Comprobar si un producto es favorito del usuario.
     */
    public function esFavorito($usuario_id, $producto_id)
    {
        $sql = "SELECT id
                FROM favoritos
                WHERE usuario_id = :usuario_id
                AND producto_id = :producto_id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':usuario_id'  => $usuario_id,
            ':producto_id' => $producto_id
        ]);

        return $stmt->fetch() !== false;
    }

    /**
     * Obtener todos los favoritos de un usuario.
     */
    public function obtenerPorUsuario($usuario_id)
    {
        $sql = "SELECT
                    p.id,
                    p.nombre,
                    p.descripcion,
                    p.precio,
                    p.stock,
                    p.imagen,
                    p.categoria_id,
                    f.id AS favorito_id,
                    f.creado_en
                FROM favoritos f
                INNER JOIN productos p
                    ON p.id = f.producto_id
                WHERE f.usuario_id = :usuario_id
                ORDER BY f.creado_en DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':usuario_id' => $usuario_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Contar favoritos del usuario.
     */
    public function contarPorUsuario($usuario_id)
    {
        $sql = "SELECT COUNT(*)
                FROM favoritos
                WHERE usuario_id = :usuario_id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':usuario_id' => $usuario_id
        ]);

        return (int) $stmt->fetchColumn();
    }
}