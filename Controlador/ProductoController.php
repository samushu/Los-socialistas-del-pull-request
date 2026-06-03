<?php
// ============================================================
// CONTROLADOR: ProductoController.php
// Gestiona CRUD de productos y alertas de stock
// ============================================================
require_once __DIR__ . '/../Modelo/Producto.php';
require_once __DIR__ . '/../Modelo/Categoria.php';

class ProductoController {
    private Producto  $productoModel;
    private Categoria $categoriaModel;

    public function __construct() {
        $this->productoModel  = new Producto();
        $this->categoriaModel = new Categoria();
    }

    // ── LISTAR TODOS LOS PRODUCTOS ────────────────────────
    public function index(): void {
        $productos   = $this->productoModel->getAll();
        $stock_bajo  = $this->productoModel->getStockBajo();
        $categorias  = $this->categoriaModel->getAll();
        require __DIR__ . '/../Vista/producto/producto.php';
    }

    // ── FORMULARIO CREAR ──────────────────────────────────
    public function crear(): void {
        $categorias = $this->categoriaModel->getAll();
        $impuestos  = Producto::IMPUESTOS;
        require __DIR__ . '/../Vista/producto/_form.php';
    }

    // ── PROCESAR CREACIÓN ─────────────────────────────────
    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=producto'); exit;
        }

        // Asignar impuesto automático según categoría
        $cat = $this->categoriaModel->getById((int) $_POST['id_categoria']);
        $impuesto = Producto::IMPUESTOS[$cat['nombre']] ?? 0.00;

        $data = [
            'codigo'          => trim($_POST['codigo']),
            'nombre'          => trim($_POST['nombre']),
            'peso'            => (float) $_POST['peso'],
            'cantidad'        => (int) $_POST['cantidad'],
            'tipo_empaque'    => $_POST['tipo_empaque'],
            'precio_unitario' => (float) $_POST['precio_unitario'],
            'impuesto'        => $impuesto,
            'id_categoria'    => (int) $_POST['id_categoria'],
        ];

        if ($this->productoModel->create($data)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Producto creado correctamente.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error al crear el producto.'];
        }
        header('Location: index.php?c=producto'); exit;
    }

    // ── FORMULARIO EDITAR ─────────────────────────────────
    public function editar(): void {
        $id         = (int) ($_GET['id'] ?? 0);
        $producto   = $this->productoModel->getById($id);
        $categorias = $this->categoriaModel->getAll();
        if (!$producto) {
            header('Location: index.php?c=producto'); exit;
        }
        require __DIR__ . '/../Vista/producto/_form.php';
    }

    // ── PROCESAR ACTUALIZACIÓN ────────────────────────────
    public function actualizar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=producto'); exit;
        }
        $id  = (int) $_POST['id_producto'];
        $cat = $this->categoriaModel->getById((int) $_POST['id_categoria']);
        $impuesto = Producto::IMPUESTOS[$cat['nombre']] ?? 0.00;

        $data = [
            'codigo'          => trim($_POST['codigo']),
            'nombre'          => trim($_POST['nombre']),
            'peso'            => (float) $_POST['peso'],
            'cantidad'        => (int) $_POST['cantidad'],
            'tipo_empaque'    => $_POST['tipo_empaque'],
            'precio_unitario' => (float) $_POST['precio_unitario'],
            'impuesto'        => $impuesto,
            'id_categoria'    => (int) $_POST['id_categoria'],
        ];

        if ($this->productoModel->update($id, $data)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Producto actualizado.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error al actualizar.'];
        }
        header('Location: index.php?c=producto'); exit;
    }

    // ── ELIMINAR ──────────────────────────────────────────
    public function eliminar(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($this->productoModel->delete($id)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Producto eliminado.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'No se pudo eliminar (puede tener compras asociadas).'];
        }
        header('Location: index.php?c=producto'); exit;
    }
}
?>