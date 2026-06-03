<?php
// ============================================================
// MODELO: Producto.php
// Acceso a datos de la tabla Producto
// ============================================================
require_once __DIR__ . '/database.php';

class Producto {
    private PDO $pdo;

    // Impuesto por categoría según reglas de negocio
    // Papeleria=7%, Drogueria=3%, Aseo=5%, Supermercado=0%
    public const IMPUESTOS = [
        'Papeleria'    => 7.00,
        'Drogueria'    => 3.00,
        'Aseo'         => 5.00,
        'Supermercado' => 0.00,
    ];

    public const STOCK_MINIMO = 5; // Alerta cuando cantidad < 5

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // ── OBTENER TODOS LOS PRODUCTOS CON SU CATEGORÍA ──────
    /* SQL: JOIN entre Producto y Categoria para mostrar el nombre de la categoría */
    public function getAll(): array {
        $stmt = $this->pdo->query(
            "SELECT p.id_producto, p.codigo, p.nombre, p.peso,
                    p.cantidad, p.tipo_empaque, p.precio_unitario,
                    p.impuesto, p.id_categoria, c.nombre AS categoria_nombre
             FROM Producto p
             INNER JOIN Categoria c ON p.id_categoria = c.id_categoria
             ORDER BY c.nombre, p.nombre"
        );
        return $stmt->fetchAll();
    }

    // ── PRODUCTOS CON STOCK BAJO (< 5 unidades) ───────────
    /* SQL: Filtra productos cuya cantidad es menor al stock mínimo */
    public function getStockBajo(): array {
        $stmt = $this->pdo->prepare(
            "SELECT p.id_producto, p.codigo, p.nombre, p.cantidad,
                    c.nombre AS categoria_nombre
             FROM Producto p
             INNER JOIN Categoria c ON p.id_categoria = c.id_categoria
             WHERE p.cantidad < :stock_min
             ORDER BY p.cantidad ASC"
        );
        $stmt->execute([':stock_min' => self::STOCK_MINIMO]);
        return $stmt->fetchAll();
    }

    // ── OBTENER PRODUCTO POR ID ───────────────────────────
    /* SQL: Busca un producto con join a categoría por su PK */
    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT p.*, c.nombre AS categoria_nombre
             FROM Producto p
             INNER JOIN Categoria c ON p.id_categoria = c.id_categoria
             WHERE p.id_producto = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // ── OBTENER PRODUCTO POR CÓDIGO ───────────────────────
    /* SQL: Busca producto por su código único */
    public function getByCodigo(string $codigo): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT p.*, c.nombre AS categoria_nombre
             FROM Producto p
             INNER JOIN Categoria c ON p.id_categoria = c.id_categoria
             WHERE p.codigo = :codigo"
        );
        $stmt->execute([':codigo' => $codigo]);
        return $stmt->fetch();
    }

    // ── PRODUCTOS POR CATEGORÍA ───────────────────────────
    /* SQL: Filtra productos de una categoría específica */
    public function getByCategoria(int $id_categoria): array {
        $stmt = $this->pdo->prepare(
            "SELECT p.id_producto, p.codigo, p.nombre, p.peso,
                    p.cantidad, p.tipo_empaque, p.precio_unitario, p.impuesto
             FROM Producto p
             WHERE p.id_categoria = :id_categoria
             ORDER BY p.nombre"
        );
        $stmt->execute([':id_categoria' => $id_categoria]);
        return $stmt->fetchAll();
    }

    // ── CREAR PRODUCTO ────────────────────────────────────
    /* SQL: Inserta un nuevo producto con todos sus atributos */
    public function create(array $data): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO Producto
                (codigo, nombre, peso, cantidad, tipo_empaque, precio_unitario, impuesto, id_categoria)
             VALUES
                (:codigo, :nombre, :peso, :cantidad, :tipo_empaque, :precio_unitario, :impuesto, :id_categoria)"
        );
        return $stmt->execute([
            ':codigo'          => $data['codigo'],
            ':nombre'          => $data['nombre'],
            ':peso'            => $data['peso'],
            ':cantidad'        => $data['cantidad'],
            ':tipo_empaque'    => $data['tipo_empaque'],
            ':precio_unitario' => $data['precio_unitario'],
            ':impuesto'        => $data['impuesto'],
            ':id_categoria'    => $data['id_categoria'],
        ]);
    }

    // ── ACTUALIZAR PRODUCTO ───────────────────────────────
    /* SQL: Actualiza todos los campos de un producto por su PK */
    public function update(int $id, array $data): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE Producto SET
                codigo = :codigo, nombre = :nombre, peso = :peso,
                cantidad = :cantidad, tipo_empaque = :tipo_empaque,
                precio_unitario = :precio_unitario, impuesto = :impuesto,
                id_categoria = :id_categoria
             WHERE id_producto = :id"
        );
        return $stmt->execute([
            ':codigo'          => $data['codigo'],
            ':nombre'          => $data['nombre'],
            ':peso'            => $data['peso'],
            ':cantidad'        => $data['cantidad'],
            ':tipo_empaque'    => $data['tipo_empaque'],
            ':precio_unitario' => $data['precio_unitario'],
            ':impuesto'        => $data['impuesto'],
            ':id_categoria'    => $data['id_categoria'],
            ':id'              => $id,
        ]);
    }

    // ── ACTUALIZAR STOCK (descontar cantidad comprada) ────
    /* SQL: Resta la cantidad vendida al stock del producto */
    public function actualizarStock(int $id_producto, int $cantidad_vendida): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE Producto
             SET cantidad = cantidad - :cantidad
             WHERE id_producto = :id AND cantidad >= :cantidad"
        );
        return $stmt->execute([
            ':cantidad' => $cantidad_vendida,
            ':id'       => $id_producto,
        ]);
    }

    // ── ELIMINAR PRODUCTO ─────────────────────────────────
    /* SQL: Elimina un producto por su PK */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare(
            "DELETE FROM Producto WHERE id_producto = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
}
?>