<?php
// ============================================================
// MODELO: Proveedor.php
// Acceso a datos de Proveedor y Producto_Proveedor
// ============================================================
require_once __DIR__ . '/database.php';

class Proveedor {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // ── OBTENER TODOS LOS PROVEEDORES ─────────────────────
    /* SQL: Lista completa de proveedores ordenados por nombre */
    public function getAll(): array {
        $stmt = $this->pdo->query(
            "SELECT id_proveedor, nombre, telefono, ciudad
             FROM Proveedor
             ORDER BY nombre"
        );
        return $stmt->fetchAll();
    }

    // ── OBTENER PROVEEDOR POR ID ──────────────────────────
    /* SQL: Busca un proveedor por su PK */
    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT id_proveedor, nombre, telefono, ciudad
             FROM Proveedor
             WHERE id_proveedor = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // ── CREAR PROVEEDOR ───────────────────────────────────
    /* SQL: Inserta un nuevo proveedor */
    public function create(array $data): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO Proveedor (nombre, telefono, ciudad)
             VALUES (:nombre, :telefono, :ciudad)"
        );
        return $stmt->execute([
            ':nombre'   => $data['nombre'],
            ':telefono' => $data['telefono'],
            ':ciudad'   => $data['ciudad'],
        ]);
    }

    // ── ACTUALIZAR PROVEEDOR ──────────────────────────────
    /* SQL: Actualiza los datos de un proveedor por su PK */
    public function update(int $id, array $data): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE Proveedor
             SET nombre = :nombre, telefono = :telefono, ciudad = :ciudad
             WHERE id_proveedor = :id"
        );
        return $stmt->execute([
            ':nombre'   => $data['nombre'],
            ':telefono' => $data['telefono'],
            ':ciudad'   => $data['ciudad'],
            ':id'       => $id,
        ]);
    }

    // ── ELIMINAR PROVEEDOR ────────────────────────────────
    /* SQL: Elimina un proveedor por su PK */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare(
            "DELETE FROM Proveedor WHERE id_proveedor = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    // ── PROVEEDORES DE UN PRODUCTO ────────────────────────
    /* SQL: JOIN triple para ver qué proveedores surten un producto */
    public function getByProducto(int $id_producto): array {
        $stmt = $this->pdo->prepare(
            "SELECT pr.id_proveedor, pr.nombre, pr.telefono, pr.ciudad,
                    pp.precio_compra
             FROM Proveedor pr
             INNER JOIN Producto_Proveedor pp ON pr.id_proveedor = pp.id_proveedor
             WHERE pp.id_producto = :id_producto
             ORDER BY pr.ciudad"
        );
        $stmt->execute([':id_producto' => $id_producto]);
        return $stmt->fetchAll();
    }

    // ── PRODUCTOS VINCULADOS A UN PROVEEDOR ───────────────
    /* SQL: Dado un proveedor, trae todos los productos que suministra */
    public function getProductosByProveedor(int $id_proveedor): array {
        $stmt = $this->pdo->prepare(
            "SELECT p.id_producto, p.codigo, p.nombre, p.precio_unitario,
                    c.nombre AS categoria_nombre,
                    pp.precio_compra
             FROM Producto_Proveedor pp
             INNER JOIN Producto p ON pp.id_producto = p.id_producto
             INNER JOIN Categoria c ON p.id_categoria = c.id_categoria
             WHERE pp.id_proveedor = :id_proveedor
             ORDER BY c.nombre, p.nombre"
        );
        $stmt->execute([':id_proveedor' => $id_proveedor]);
        return $stmt->fetchAll();
    }

    // ── ASOCIAR PROVEEDOR CON PRODUCTO ────────────────────
    /* SQL: Inserta relación muchos-a-muchos entre Producto y Proveedor */
    public function asociarProducto(int $id_producto, int $id_proveedor, float $precio_compra): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO Producto_Proveedor (id_producto, id_proveedor, precio_compra)
             VALUES (:id_producto, :id_proveedor, :precio_compra)
             ON DUPLICATE KEY UPDATE precio_compra = :precio_compra"
        );
        return $stmt->execute([
            ':id_producto'  => $id_producto,
            ':id_proveedor' => $id_proveedor,
            ':precio_compra'=> $precio_compra,
        ]);
    }

    // ── DESASOCIAR PROVEEDOR DE PRODUCTO ──────────────────
    /* SQL: Elimina la relación entre un producto y un proveedor */
    public function desasociarProducto(int $id_producto, int $id_proveedor): bool {
        $stmt = $this->pdo->prepare(
            "DELETE FROM Producto_Proveedor
             WHERE id_producto = :id_producto AND id_proveedor = :id_proveedor"
        );
        return $stmt->execute([
            ':id_producto'  => $id_producto,
            ':id_proveedor' => $id_proveedor,
        ]);
    }

    // ── PAGOS REALIZADOS A UN PROVEEDOR ───────────────────
    /* SQL: Suma total de pagos registrados por proveedor */
    public function getPagos(int $id_proveedor): array {
        $stmt = $this->pdo->prepare(
            "SELECT pp.id_pago, pp.fecha, pp.monto
             FROM Pago_Proveedor pp
             WHERE pp.id_proveedor = :id_proveedor
             ORDER BY pp.fecha DESC"
        );
        $stmt->execute([':id_proveedor' => $id_proveedor]);
        return $stmt->fetchAll();
    }
}
?>