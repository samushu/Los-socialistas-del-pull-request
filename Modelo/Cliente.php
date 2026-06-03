<?php
// ============================================================
// MODELO: Cliente.php
// Acceso a datos de la tabla Cliente
// ============================================================
require_once __DIR__ . '/database.php';

class Cliente {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // ── OBTENER TODOS LOS CLIENTES ────────────────────────
    /* SQL: Lista completa de clientes ordenados por apellido */
    public function getAll(): array {
        $stmt = $this->pdo->query(
            "SELECT cedula, nombre, apellido, telefono, correo
             FROM Cliente
             ORDER BY apellido, nombre"
        );
        return $stmt->fetchAll();
    }

    // ── OBTENER CLIENTE POR CÉDULA ────────────────────────
    /* SQL: Busca un cliente por su PK (cédula) */
    public function getByCedula(string $cedula): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT cedula, nombre, apellido, telefono, correo
             FROM Cliente
             WHERE cedula = :cedula"
        );
        $stmt->execute([':cedula' => $cedula]);
        return $stmt->fetch();
    }

    // ── CREAR CLIENTE ─────────────────────────────────────
    /* SQL: Inserta un nuevo cliente */
    public function create(array $data): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO Cliente (cedula, nombre, apellido, telefono, correo)
             VALUES (:cedula, :nombre, :apellido, :telefono, :correo)"
        );
        return $stmt->execute([
            ':cedula'   => $data['cedula'],
            ':nombre'   => $data['nombre'],
            ':apellido' => $data['apellido'],
            ':telefono' => $data['telefono'],
            ':correo'   => $data['correo'],
        ]);
    }

    // ── ACTUALIZAR CLIENTE ────────────────────────────────
    /* SQL: Actualiza los datos de un cliente (la cédula es inmutable) */
    public function update(string $cedula, array $data): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE Cliente
             SET nombre = :nombre, apellido = :apellido,
                 telefono = :telefono, correo = :correo
             WHERE cedula = :cedula"
        );
        return $stmt->execute([
            ':nombre'   => $data['nombre'],
            ':apellido' => $data['apellido'],
            ':telefono' => $data['telefono'],
            ':correo'   => $data['correo'],
            ':cedula'   => $cedula,
        ]);
    }

    // ── ELIMINAR CLIENTE ──────────────────────────────────
    /* SQL: Elimina un cliente por su cédula */
    public function delete(string $cedula): bool {
        $stmt = $this->pdo->prepare(
            "DELETE FROM Cliente WHERE cedula = :cedula"
        );
        return $stmt->execute([':cedula' => $cedula]);
    }

    // ── CLIENTES CON MAYOR CANTIDAD DE COMPRAS ─────────────
    /* SQL: Agrupa compras por cliente, cuenta y suma el valor total,
            ordena descendente por número de compras */
    public function getMasCompras(): array {
        $stmt = $this->pdo->query(
            "SELECT cl.cedula, cl.nombre, cl.apellido, cl.telefono,
                    COUNT(co.id_compra) AS total_compras,
                    COALESCE(SUM(
                        dc.cantidad * dc.precio_unitario * (1 + dc.impuesto / 100)
                    ), 0) AS valor_total
             FROM Cliente cl
             LEFT JOIN Compra co ON cl.cedula = co.cedula_cliente
             LEFT JOIN Detalle_Compra dc ON co.id_compra = dc.id_compra
             GROUP BY cl.cedula, cl.nombre, cl.apellido, cl.telefono
             HAVING total_compras > 0
             ORDER BY total_compras DESC, valor_total DESC"
        );
        return $stmt->fetchAll();
    }

    // ── CLIENTES QUE SOLO HAN COMPRADO UNA VEZ ────────────
    /* SQL: Filtra clientes con exactamente 1 compra registrada */
    public function getCompraUnica(): array {
        $stmt = $this->pdo->query(
            "SELECT cl.cedula, cl.nombre, cl.apellido, cl.telefono,
                    COUNT(co.id_compra) AS total_compras,
                    co.fecha AS fecha_compra,
                    COALESCE(SUM(
                        dc.cantidad * dc.precio_unitario * (1 + dc.impuesto / 100)
                    ), 0) AS valor_total
             FROM Cliente cl
             INNER JOIN Compra co ON cl.cedula = co.cedula_cliente
             LEFT JOIN Detalle_Compra dc ON co.id_compra = dc.id_compra
             GROUP BY cl.cedula, cl.nombre, cl.apellido, cl.telefono, co.fecha
             HAVING total_compras = 1
             ORDER BY cl.apellido"
        );
        return $stmt->fetchAll();
    }

    // ── CLIENTE MÁS FRECUENTE (el que más compras tiene) ──
    /* SQL: Subquery para obtener el cliente con más compras totales */
    public function getMasFrecuente(): array|false {
        $stmt = $this->pdo->query(
            "SELECT cl.cedula, cl.nombre, cl.apellido, cl.telefono, cl.correo,
                    COUNT(co.id_compra) AS total_compras,
                    COALESCE(SUM(
                        dc.cantidad * dc.precio_unitario * (1 + dc.impuesto / 100)
                    ), 0) AS valor_total
             FROM Cliente cl
             INNER JOIN Compra co ON cl.cedula = co.cedula_cliente
             LEFT JOIN Detalle_Compra dc ON co.id_compra = dc.id_compra
             GROUP BY cl.cedula, cl.nombre, cl.apellido, cl.telefono, cl.correo
             ORDER BY total_compras DESC
             LIMIT 1"
        );
        return $stmt->fetch();
    }
}
?>