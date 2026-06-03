<?php
// ============================================================
// MODELO: Compra.php
// Gestiona compras de clientes y sus detalles
// ============================================================
require_once __DIR__ . '/database.php';

class Compra {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // ── LISTAR TODAS LAS COMPRAS ──────────────────────────
    /* SQL: JOIN con Cliente para mostrar nombre del comprador */
    public function getAll(): array {
        $stmt = $this->pdo->query(
            "SELECT co.id_compra, co.fecha,
                    cl.cedula, cl.nombre, cl.apellido,
                    COUNT(dc.id_detalle) AS num_productos,
                    SUM(dc.cantidad * dc.precio_unitario * (1 + dc.impuesto / 100)) AS total
             FROM Compra co
             INNER JOIN Cliente cl ON co.cedula_cliente = cl.cedula
             LEFT JOIN Detalle_Compra dc ON co.id_compra = dc.id_compra
             GROUP BY co.id_compra, co.fecha, cl.cedula, cl.nombre, cl.apellido
             ORDER BY co.fecha DESC"
        );
        return $stmt->fetchAll();
    }

    // ── OBTENER COMPRA CON DETALLE COMPLETO ───────────────
    /* SQL: JOIN a 4 tablas para mostrar el resumen completo de una compra */
    public function getDetalleCompleto(int $id_compra): array {
        $stmt = $this->pdo->prepare(
            "SELECT co.id_compra, co.fecha,
                    cl.cedula, cl.nombre AS cliente_nombre, cl.apellido AS cliente_apellido,
                    cl.telefono, cl.correo,
                    dc.id_detalle, dc.cantidad, dc.precio_unitario, dc.impuesto,
                    p.nombre AS producto_nombre, p.codigo AS producto_codigo,
                    c.nombre AS categoria_nombre,
                    (dc.cantidad * dc.precio_unitario) AS subtotal,
                    (dc.cantidad * dc.precio_unitario * dc.impuesto / 100) AS valor_impuesto,
                    (dc.cantidad * dc.precio_unitario * (1 + dc.impuesto / 100)) AS total_linea
             FROM Compra co
             INNER JOIN Cliente cl ON co.cedula_cliente = cl.cedula
             INNER JOIN Detalle_Compra dc ON co.id_compra = dc.id_compra
             INNER JOIN Producto p ON dc.id_producto = p.id_producto
             INNER JOIN Categoria c ON p.id_categoria = c.id_categoria
             WHERE co.id_compra = :id_compra
             ORDER BY c.nombre, p.nombre"
        );
        $stmt->execute([':id_compra' => $id_compra]);
        return $stmt->fetchAll();
    }

    // ── COMPRAS DE UN CLIENTE ─────────────────────────────
    /* SQL: Filtra compras por cédula del cliente */
    public function getByCliente(string $cedula): array {
        $stmt = $this->pdo->prepare(
            "SELECT co.id_compra, co.fecha,
                    COUNT(dc.id_detalle) AS num_productos,
                    SUM(dc.cantidad * dc.precio_unitario * (1 + dc.impuesto / 100)) AS total
             FROM Compra co
             LEFT JOIN Detalle_Compra dc ON co.id_compra = dc.id_compra
             WHERE co.cedula_cliente = :cedula
             GROUP BY co.id_compra, co.fecha
             ORDER BY co.fecha DESC"
        );
        $stmt->execute([':cedula' => $cedula]);
        return $stmt->fetchAll();
    }

    // ── REGISTRAR NUEVA COMPRA (cabecera) ─────────────────
    /* SQL: Inserta la cabecera de la compra y retorna el ID generado */
    public function crearCompra(string $cedula_cliente, string $fecha): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO Compra (cedula_cliente, fecha)
             VALUES (:cedula, :fecha)"
        );
        $stmt->execute([':cedula' => $cedula_cliente, ':fecha' => $fecha]);
        return (int) $this->pdo->lastInsertId();
    }

    // ── AGREGAR LÍNEA AL DETALLE DE COMPRA ────────────────
    /* SQL: Inserta cada producto dentro del detalle de una compra */
    public function agregarDetalle(int $id_compra, array $item): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO Detalle_Compra
                (id_compra, id_producto, cantidad, precio_unitario, impuesto)
             VALUES
                (:id_compra, :id_producto, :cantidad, :precio_unitario, :impuesto)"
        );
        return $stmt->execute([
            ':id_compra'      => $id_compra,
            ':id_producto'    => $item['id_producto'],
            ':cantidad'       => $item['cantidad'],
            ':precio_unitario'=> $item['precio_unitario'],
            ':impuesto'       => $item['impuesto'],
        ]);
    }

    // ── ELIMINAR COMPRA Y SUS DETALLES ────────────────────
    /* SQL: Primero borra detalles (FK), luego la cabecera */
    public function delete(int $id_compra): bool {
        $this->pdo->prepare(
            "DELETE FROM Detalle_Compra WHERE id_compra = :id"
        )->execute([':id' => $id_compra]);

        $stmt = $this->pdo->prepare(
            "DELETE FROM Compra WHERE id_compra = :id"
        );
        return $stmt->execute([':id' => $id_compra]);
    }
}
?>