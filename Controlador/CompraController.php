<?php
// ============================================================
// CONTROLADOR: CompraController.php
// Gestiona el flujo completo de una compra con transacción DB
// ============================================================
require_once __DIR__ . '/../Modelo/Compra.php';
require_once __DIR__ . '/../Modelo/Cliente.php';
require_once __DIR__ . '/../Modelo/Producto.php';

class CompraController {
    private Compra   $compraModel;
    private Cliente  $clienteModel;
    private Producto $productoModel;

    public function __construct() {
        $this->compraModel   = new Compra();
        $this->clienteModel  = new Cliente();
        $this->productoModel = new Producto();
    }

    // ── LISTAR COMPRAS ────────────────────────────────────
    public function index(): void {
        $compras = $this->compraModel->getAll();
        require __DIR__ . '/../Vista/Compras/compras.php';
    }

    // ── FORMULARIO NUEVA COMPRA ───────────────────────────
    public function nueva(): void {
        $clientes   = $this->clienteModel->getAll();
        $productos  = $this->productoModel->getAll();
        require __DIR__ . '/../Vista/Compras/_form.php';
    }

    // ── PROCESAR COMPRA (con transacción) ─────────────────
    public function procesar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=compra'); exit;
        }

        $cedula    = trim($_POST['cedula_cliente']);
        $fecha     = $_POST['fecha'] ?: date('Y-m-d');
        $productos = $_POST['productos'] ?? []; // array de {id, cantidad}

        // Validaciones básicas
        if (empty($cedula) || empty($productos)) {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Datos incompletos.'];
            header('Location: index.php?c=compra&a=nueva'); exit;
        }

        // Verificar que el cliente existe
        if (!$this->clienteModel->getByCedula($cedula)) {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Cliente no encontrado.'];
            header('Location: index.php?c=compra&a=nueva'); exit;
        }

        // ── Transacción: cabecera + detalles + stock ──────
        $pdo = Database::getInstance()->getConnection();
        try {
            $pdo->beginTransaction();

            // 1) Crear cabecera de la compra
            $id_compra = $this->compraModel->crearCompra($cedula, $fecha);

            // 2) Procesar cada línea del carrito
            foreach ($productos as $item) {
                $id_producto = (int) $item['id_producto'];
                $cantidad    = (int) $item['cantidad'];

                if ($cantidad <= 0) continue;

                // Obtener precio e impuesto vigentes del producto
                $prod = $this->productoModel->getById($id_producto);
                if (!$prod) continue;

                // Verificar stock disponible
                if ($prod['cantidad'] < $cantidad) {
                    throw new Exception("Stock insuficiente para: {$prod['nombre']}");
                }

                // Insertar línea de detalle
                $this->compraModel->agregarDetalle($id_compra, [
                    'id_producto'    => $id_producto,
                    'cantidad'       => $cantidad,
                    'precio_unitario'=> $prod['precio_unitario'],
                    'impuesto'       => $prod['impuesto'],
                ]);

                // Descontar del stock
                $this->productoModel->actualizarStock($id_producto, $cantidad);
            }

            $pdo->commit();
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Compra registrada exitosamente.'];
            // Redirigir al resumen de la compra
            header("Location: index.php?c=compra&a=resumen&id={$id_compra}"); exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error: ' . $e->getMessage()];
            header('Location: index.php?c=compra&a=nueva'); exit;
        }
    }

    // ── RESUMEN DE UNA COMPRA ─────────────────────────────
    public function resumen(): void {
        $id_compra = (int) ($_GET['id'] ?? 0);
        $detalle   = $this->compraModel->getDetalleCompleto($id_compra);
        if (empty($detalle)) {
            header('Location: index.php?c=compra'); exit;
        }
        require __DIR__ . '/../Vista/Compras/resumen.php';
    }

    // ── HISTORIAL DE UN CLIENTE ───────────────────────────
    public function historial(): void {
        $cedula  = $_GET['cedula'] ?? '';
        $cliente = $this->clienteModel->getByCedula($cedula);
        $compras = $this->compraModel->getByCliente($cedula);
        require __DIR__ . '/../Vista/Compras/resumen.php';
    }

    // ── ELIMINAR COMPRA ───────────────────────────────────
    public function eliminar(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($this->compraModel->delete($id)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Compra eliminada.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error al eliminar la compra.'];
        }
        header('Location: index.php?c=compra'); exit;
    }
}
