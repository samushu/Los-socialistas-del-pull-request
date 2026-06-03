<?php
// ============================================================
// CONTROLADOR: ReportesController.php
// Gestiona todos los reportes analíticos de la tienda
// ============================================================
require_once __DIR__ . '/../Modelo/Reportes.php';
require_once __DIR__ . '/../Modelo/Cliente.php';
require_once __DIR__ . '/../Modelo/Producto.php';
require_once __DIR__ . '/../Modelo/PagoProveedor.php';

class ReportesController {
    private Reportes      $reportesModel;
    private Cliente       $clienteModel;
    private Producto      $productoModel;
    private PagoProveedor $pagoModel;

    public function __construct() {
        $this->reportesModel = new Reportes();
        $this->clienteModel  = new Cliente();
        $this->productoModel = new Producto();
        $this->pagoModel     = new PagoProveedor();
    }

    // ── DASHBOARD GENERAL ─────────────────────────────────
    // Muestra resumen: ingresos, gastos, balance y métricas clave
    public function index(): void {
        $resumen            = $this->reportesModel->getResumenDashboard();
        $ingresos_categoria = $this->reportesModel->getIngresosPorCategoria();
        $ingresos_mes       = $this->reportesModel->getIngresosPorMes();
        $productos_top      = $this->reportesModel->getProductosMasVendidos(5);
        $balance            = $this->reportesModel->getBalance();
        require __DIR__ . '/../Vista/Reporte/index.php';
    }

    // ── INGRESOS Y GASTOS ─────────────────────────────────
    // Muestra el balance financiero: ventas vs pagos a proveedores
    public function financiero(): void {
        $balance             = $this->reportesModel->getBalance();
        $ingresos_categoria  = $this->reportesModel->getIngresosPorCategoria();
        $ingresos_mes        = $this->reportesModel->getIngresosPorMes();
        $gastos_proveedor    = $this->pagoModel->getGastosPorProveedor();
        $pagos_recientes     = $this->pagoModel->getAll();
        require __DIR__ . '/../Vista/Reporte/financiero.php';
    }

    // ── CLIENTES CON MÁS COMPRAS ──────────────────────────
    // Lista clientes ordenados por cantidad de compras + valor total
    public function clientesFrecuentes(): void {
        $clientes_frecuentes = $this->clienteModel->getMasCompras();
        $cliente_top         = $this->clienteModel->getMasFrecuente();
        require __DIR__ . '/../Vista/Reporte/clientesFrecuente.php';
    }

    // ── CLIENTES QUE SOLO HAN COMPRADO UNA VEZ ────────────
    // Útil para campañas de retención
    public function clientesUnicos(): void {
        $clientes_unicos = $this->clienteModel->getCompraUnica();
        require __DIR__ . '/../Vista/Reporte/clientesUnicos.php';
    }

    // ── CLIENTE MÁS FRECUENTE ─────────────────────────────
    // Muestra el cliente con mayor número de compras registradas
    public function clienteMasFrecuente(): void {
        $cliente_frecuente = $this->clienteModel->getMasFrecuente();
        require __DIR__ . '/../Vista/Reporte/clientesFrecuente.php';
    }

    // ── STOCK MÍNIMO ──────────────────────────────────────
    // Productos con cantidad < 5 que necesitan reposición urgente
    public function stockMinimo(): void {
        $productos_stock_bajo = $this->productoModel->getStockBajo();
        require __DIR__ . '/../Vista/Reporte/stockminimo.php';
    }

    // ── PRODUCTOS MÁS VENDIDOS ────────────────────────────
    // Top 10 productos por unidades vendidas
    public function productosTop(): void {
        $productos_top = $this->reportesModel->getProductosMasVendidos(10);
        require __DIR__ . '/../Vista/Reporte/productosTop.php';
    }
}
?>