<?php
// ============================================================
// MODELO: Reportes.php
// Consultas analíticas: ingresos, gastos, reportes de clientes
// ============================================================
require_once __DIR__ . '/database.php';

class Reportes {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // ── TOTAL DE INGRESOS POR VENTAS ──────────────────────
    /* SQL: Suma el valor total de todos los detalles de compra
            incluyendo impuesto: precio * cantidad * (1 + impuesto/100) */
    public function getTotalIngresos(): float {
        $stmt = $this->pdo->query(
            "SELECT COALESCE(
                SUM(dc.cantidad * dc.precio_unitario * (1 + dc.impuesto / 100)),
             0) AS total_ingresos
             FROM Detalle_Compra dc"
        );
        return (float) $stmt->fetchColumn();
    }

    // ── INGRESOS POR CATEGORÍA ────────────────────────────
    /* SQL: JOIN de 3 tablas agrupando ventas por categoría de producto */
    public function getIngresosPorCategoria(): array {
        $stmt = $this->pdo->query(
            "SELECT c.nombre AS categoria,
                    COUNT(DISTINCT co.id_compra) AS num_ventas,
                    SUM(dc.cantidad) AS unidades_vendidas,
                    SUM(dc.cantidad * dc.precio_unitario) AS subtotal,
                    SUM(dc.cantidad * dc.precio_unitario * dc.impuesto / 100) AS total_impuesto,
                    SUM(dc.cantidad * dc.precio_unitario * (1 + dc.impuesto / 100)) AS total_con_impuesto
             FROM Detalle_Compra dc
             INNER JOIN Producto p ON dc.id_producto = p.id_producto
             INNER JOIN Categoria c ON p.id_categoria = c.id_categoria
             INNER JOIN Compra co ON dc.id_compra = co.id_compra
             GROUP BY c.id_categoria, c.nombre
             ORDER BY total_con_impuesto DESC"
        );
        return $stmt->fetchAll();
    }

    // ── INGRESOS POR MES ──────────────────────────────────
    /* SQL: Agrupa ventas por año-mes para ver tendencia temporal */
    public function getIngresosPorMes(): array {
        $stmt = $this->pdo->query(
            "SELECT DATE_FORMAT(co.fecha, '%Y-%m') AS mes,
                    COUNT(DISTINCT co.id_compra) AS num_compras,
                    SUM(dc.cantidad * dc.precio_unitario * (1 + dc.impuesto / 100)) AS total
             FROM Compra co
             INNER JOIN Detalle_Compra dc ON co.id_compra = dc.id_compra
             GROUP BY mes
             ORDER BY mes DESC
             LIMIT 12"
        );
        return $stmt->fetchAll();
    }

    // ── TOTAL DE GASTOS (pagos a proveedores) ─────────────
    /* SQL: Suma todos los pagos registrados en Pago_Proveedor */
    public function getTotalGastos(): float {
        $stmt = $this->pdo->query(
            "SELECT COALESCE(SUM(monto), 0) AS total_gastos
             FROM Pago_Proveedor"
        );
        return (float) $stmt->fetchColumn();
    }

    // ── BALANCE (ingresos - gastos) ───────────────────────
    /* Calcula el balance neto de la tienda */
    public function getBalance(): array {
        $ingresos = $this->getTotalIngresos();
        $gastos   = $this->getTotalGastos();
        return [
            'ingresos' => $ingresos,
            'gastos'   => $gastos,
            'balance'  => $ingresos - $gastos,
        ];
    }

    // ── PRODUCTOS MÁS VENDIDOS ────────────────────────────
    /* SQL: Agrupa detalles de compra por producto y ordena por cantidad */
    public function getProductosMasVendidos(int $limit = 10): array {
        $stmt = $this->pdo->prepare(
            "SELECT p.id_producto, p.codigo, p.nombre,
                    c.nombre AS categoria,
                    SUM(dc.cantidad) AS unidades_vendidas,
                    SUM(dc.cantidad * dc.precio_unitario * (1 + dc.impuesto / 100)) AS ingresos
             FROM Detalle_Compra dc
             INNER JOIN Producto p ON dc.id_producto = p.id_producto
             INNER JOIN Categoria c ON p.id_categoria = c.id_categoria
             GROUP BY p.id_producto, p.codigo, p.nombre, c.nombre
             ORDER BY unidades_vendidas DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── RESUMEN GENERAL DEL DASHBOARD ────────────────────
    /* SQL: Combina múltiples métricas clave en una sola consulta */
    public function getResumenDashboard(): array {
        // Total clientes
        $totalClientes = (int) $this->pdo->query(
            "SELECT COUNT(*) FROM Cliente"
        )->fetchColumn();

        // Total productos
        $totalProductos = (int) $this->pdo->query(
            "SELECT COUNT(*) FROM Producto"
        )->fetchColumn();

        // Total compras
        $totalCompras = (int) $this->pdo->query(
            "SELECT COUNT(*) FROM Compra"
        )->fetchColumn();

        // Total proveedores
        $totalProveedores = (int) $this->pdo->query(
            "SELECT COUNT(*) FROM Proveedor"
        )->fetchColumn();

        // Productos con stock bajo
        $stockBajo = (int) $this->pdo->query(
            "SELECT COUNT(*) FROM Producto WHERE cantidad < 5"
        )->fetchColumn();

        return [
            'total_clientes'   => $totalClientes,
            'total_productos'  => $totalProductos,
            'total_compras'    => $totalCompras,
            'total_proveedores'=> $totalProveedores,
            'stock_bajo'       => $stockBajo,
            'ingresos'         => $this->getTotalIngresos(),
            'gastos'           => $this->getTotalGastos(),
        ];
    }
}
?>