<?php
// ============================================================
// CONTROLADOR: ProveedorController.php
// Gestiona CRUD de proveedores y sus relaciones con productos
// ============================================================
require_once __DIR__ . '/../Modelo/Proveedor.php';
require_once __DIR__ . '/../Modelo/Producto.php';
require_once __DIR__ . '/../Modelo/PagoProveedor.php';

class ProveedorController {
    private Proveedor     $proveedorModel;
    private Producto      $productoModel;
    private PagoProveedor $pagoModel;

    public function __construct() {
        $this->proveedorModel = new Proveedor();
        $this->productoModel  = new Producto();
        $this->pagoModel      = new PagoProveedor();
    }

    public function index(): void {
        $proveedores = $this->proveedorModel->getAll();
        require __DIR__ . '/../Vista/proveedores/index.php';
    }

    public function crear(): void {
        require __DIR__ . '/../Vista/proveedores/form.php';
    }

    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=proveedor'); exit;
        }
        $data = [
            'nombre'   => trim($_POST['nombre']),
            'telefono' => trim($_POST['telefono']),
            'ciudad'   => trim($_POST['ciudad']),
        ];
        if ($this->proveedorModel->create($data)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Proveedor registrado.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error al registrar proveedor.'];
        }
        header('Location: index.php?c=proveedor'); exit;
    }

    public function editar(): void {
        $id        = (int) ($_GET['id'] ?? 0);
        $proveedor = $this->proveedorModel->getById($id);
        if (!$proveedor) { header('Location: index.php?c=proveedor'); exit; }
        require __DIR__ . '/../Vista/proveedores/form.php';
    }

    public function actualizar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=proveedor'); exit;
        }
        $id   = (int) $_POST['id_proveedor'];
        $data = [
            'nombre'   => trim($_POST['nombre']),
            'telefono' => trim($_POST['telefono']),
            'ciudad'   => trim($_POST['ciudad']),
        ];
        if ($this->proveedorModel->update($id, $data)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Proveedor actualizado.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error al actualizar.'];
        }
        header('Location: index.php?c=proveedor'); exit;
    }

    public function eliminar(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($this->proveedorModel->delete($id)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Proveedor eliminado.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'No se pudo eliminar.'];
        }
        header('Location: index.php?c=proveedor'); exit;
    }

    // ── ASOCIAR PRODUCTO A PROVEEDOR ──────────────────────
    public function asociar(): void {
        $id        = (int) ($_GET['id'] ?? 0);
        $proveedor = $this->proveedorModel->getById($id);
        if (!$proveedor) { header('Location: index.php?c=proveedor'); exit; }
        $productos  = $this->productoModel->getAll();
        // Productos ya vinculados a este proveedor (para mostrarlos en la vista)
        $vinculados = $this->proveedorModel->getProductosByProveedor($id);
        require __DIR__ . '/../Vista/Proveedor/asociar.php';
    }

    public function guardarAsociacion(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=proveedor'); exit;
        }
        $id_proveedor  = (int) $_POST['id_proveedor'];
        $id_producto   = (int) $_POST['id_producto'];
        $precio_compra = (float) $_POST['precio_compra'];

        if ($this->proveedorModel->asociarProducto($id_producto, $id_proveedor, $precio_compra)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Producto asociado al proveedor.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error al asociar.'];
        }
        header("Location: index.php?c=proveedor&a=asociar&id={$id_proveedor}"); exit;
    }

    // ── REGISTRAR PAGO A PROVEEDOR ────────────────────────
    public function registrarPago(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=proveedor'); exit;
        }
        $id_proveedor = (int) $_POST['id_proveedor'];
        $fecha        = $_POST['fecha'];
        $monto        = (float) $_POST['monto'];

        if ($this->pagoModel->create($id_proveedor, $fecha, $monto)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Pago registrado correctamente.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error al registrar el pago.'];
        }
        header("Location: index.php?c=proveedor"); exit;
    }
}
?>