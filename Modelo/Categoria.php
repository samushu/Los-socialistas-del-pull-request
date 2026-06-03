<?php
// ============================================================
// MODELO: Categoria.php
// Acceso a datos de la tabla Categoria
// ============================================================
require_once __DIR__ . '/database.php';
 
class Categoria {
    private PDO $pdo;
 
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
 
    // ── OBTENER TODAS LAS CATEGORÍAS ──────────────────────
    /* SQL: Trae id y nombre de todas las categorías */
    public function getAll(): array {
        $stmt = $this->pdo->query(
            "SELECT id_categoria, nombre FROM Categoria ORDER BY nombre"
        );
        return $stmt->fetchAll();
    }
 
    // ── OBTENER CATEGORÍA POR ID ───────────────────────────
    /* SQL: Busca una categoría específica por su PK */
    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT id_categoria, nombre FROM Categoria WHERE id_categoria = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
 
    // ── CREAR CATEGORÍA ───────────────────────────────────
    /* SQL: Inserta una nueva categoría */
    public function create(string $nombre): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO Categoria (nombre) VALUES (:nombre)"
        );
        return $stmt->execute([':nombre' => $nombre]);
    }
 
    // ── ACTUALIZAR CATEGORÍA ──────────────────────────────
    /* SQL: Actualiza el nombre de una categoría por su PK */
    public function update(int $id, string $nombre): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE Categoria SET nombre = :nombre WHERE id_categoria = :id"
        );
        return $stmt->execute([':nombre' => $nombre, ':id' => $id]);
    }
 
    // ── ELIMINAR CATEGORÍA ────────────────────────────────
    /* SQL: Elimina una categoría (solo si no tiene productos) */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare(
            "DELETE FROM Categoria WHERE id_categoria = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
}
?>