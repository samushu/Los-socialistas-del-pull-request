<?php
// ============================================================
// MODELO: PagoProveedor.php
// Gestiona pagos realizados a proveedores
// ============================================================
require_once __DIR__ . '/database.php';

class PagoProveedor {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // ── TODOS LOS PAGOS CON NOMBRE DEL PROVEEDOR ─────────
    /* SQL: JOIN con Proveedor para mostrar nombre junto al pago */
    public function getAll(): array {
        $stmt = $this->pdo->query(
            "SELECT pp.id_pago, pp.fecha, pp.monto,
                    pr.id_proveedor, pr.nombre AS proveedor_nombre, pr.ciudad
             FROM Pago_Proveedor pp
             INNER JOIN Proveedor pr ON pp.id_proveedor = pr.id_proveedor
             ORDER BY pp.fecha DESC"
        );
        return $stmt->fetchAll();
    }

    // ── PAGOS DE UN PROVEEDOR ESPECÍFICO ──────────────────
    /* SQL: Filtra pagos por id_proveedor */
    public function getByProveedor(int $id_proveedor): array {
        $stmt = $this->pdo->prepare(
            "SELECT id_pago, fecha, monto
             FROM Pago_Proveedor
             WHERE id_proveedor = :id_proveedor
             ORDER BY fecha DESC"
        );
        $stmt->execute([':id_proveedor' => $id_proveedor]);
        return $stmt->fetchAll();
    }

    // ── REGISTRAR UN PAGO ─────────────────────────────────
    /* SQL: Inserta un nuevo pago a proveedor */
    public function create(int $id_proveedor, string $fecha, float $monto): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO Pago_Proveedor (id_proveedor, fecha, monto)
             VALUES (:id_proveedor, :fecha, :monto)"
        );
        return $stmt->execute([
            ':id_proveedor' => $id_proveedor,
            ':fecha'        => $fecha,
            ':monto'        => $monto,
        ]);
    }

    // ── TOTAL DE GASTOS (suma de todos los pagos) ─────────
    /* SQL: Suma total de todos los pagos a proveedores = gastos totales */
    public function getTotalGastos(): float {
        $stmt = $this->pdo->query(
            "SELECT COALESCE(SUM(monto), 0) AS total_gastos
             FROM Pago_Proveedor"
        );
        return (float) $stmt->fetchColumn();
    }

    // ── GASTOS POR PROVEEDOR ──────────────────────────────
    /* SQL: Agrupa y suma pagos por cada proveedor */
    public function getGastosPorProveedor(): array {
        $stmt = $this->pdo->query(
            "SELECT pr.id_proveedor, pr.nombre, pr.ciudad,
                    COUNT(pp.id_pago) AS num_pagos,
                    COALESCE(SUM(pp.monto), 0) AS total_pagado
             FROM Proveedor pr
             LEFT JOIN Pago_Proveedor pp ON pr.id_proveedor = pp.id_proveedor
             GROUP BY pr.id_proveedor, pr.nombre, pr.ciudad
             ORDER BY total_pagado DESC"
        );
        return $stmt->fetchAll();
    }

    // ── ELIMINAR PAGO ─────────────────────────────────────
    /* SQL: Elimina un registro de pago por su PK */
    public function delete(int $id_pago): bool {
        $stmt = $this->pdo->prepare(
            "DELETE FROM Pago_Proveedor WHERE id_pago = :id"
        );
        return $stmt->execute([':id' => $id_pago]);
    }
}
?>