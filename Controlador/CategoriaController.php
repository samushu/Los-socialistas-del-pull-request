<?php
// ============================================================
// CONTROLADOR: CategoriaController.php
// ============================================================
require_once __DIR__ . '/../Modelo/Categoria.php';

class CategoriaController {
    private Categoria $categoriaModel;

    public function __construct() {
        $this->categoriaModel = new Categoria();
    }

    public function index(): void {
        $categorias = $this->categoriaModel->getAll();
        require __DIR__ . '/../Vista/categorias/index.php';
    }

    public function crear(): void {
        require __DIR__ . '/../Vista/categorias/form.php';
    }

    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=categoria'); exit;
        }
        $nombre = trim($_POST['nombre']);
        if ($this->categoriaModel->create($nombre)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Categoría creada.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error al crear.'];
        }
        header('Location: index.php?c=categoria'); exit;
    }

    public function editar(): void {
        $id        = (int) ($_GET['id'] ?? 0);
        $categoria = $this->categoriaModel->getById($id);
        if (!$categoria) { header('Location: index.php?c=categoria'); exit; }
        require __DIR__ . '/../Vista/categorias/form.php';
    }

    public function actualizar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=categoria'); exit;
        }
        $id     = (int) $_POST['id_categoria'];
        $nombre = trim($_POST['nombre']);
        if ($this->categoriaModel->update($id, $nombre)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Categoría actualizada.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error al actualizar.'];
        }
        header('Location: index.php?c=categoria'); exit;
    }

    public function eliminar(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($this->categoriaModel->delete($id)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Categoría eliminada.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'No se puede eliminar (tiene productos asociados).'];
        }
        header('Location: index.php?c=categoria'); exit;
    }
}
?>