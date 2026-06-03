<?php
// ============================================================
// CONTROLADOR: ClienteController.php
// Gestiona CRUD de clientes
// ============================================================
require_once __DIR__ . '/../Modelo/Cliente.php';

class ClienteController {
    private Cliente $clienteModel;

    public function __construct() {
        $this->clienteModel = new Cliente();
    }

    public function index(): void {
        $clientes = $this->clienteModel->getAll();
        require __DIR__ . '/../Vista/Clientes/cliente.php';
    }

    public function crear(): void {
        require __DIR__ . '/../Vista/Clientes/_form.php';
    }

    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=cliente'); exit;
        }
        $data = [
            'cedula'   => trim($_POST['cedula']),
            'nombre'   => trim($_POST['nombre']),
            'apellido' => trim($_POST['apellido']),
            'telefono' => trim($_POST['telefono']),
            'correo'   => trim($_POST['correo']),
        ];
        if ($this->clienteModel->create($data)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Cliente registrado correctamente.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error: la cédula ya existe o datos inválidos.'];
        }
        header('Location: index.php?c=cliente'); exit;
    }

    public function editar(): void {
        $cedula  = $_GET['cedula'] ?? '';
        $cliente = $this->clienteModel->getByCedula($cedula);
        if (!$cliente) { header('Location: index.php?c=cliente'); exit; }
        require __DIR__ . '/../Vista/Clientes/_form.php';
    }

    public function actualizar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=cliente'); exit;
        }
        $cedula = $_POST['cedula'];
        $data   = [
            'nombre'   => trim($_POST['nombre']),
            'apellido' => trim($_POST['apellido']),
            'telefono' => trim($_POST['telefono']),
            'correo'   => trim($_POST['correo']),
        ];
        if ($this->clienteModel->update($cedula, $data)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Cliente actualizado.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'Error al actualizar.'];
        }
        header('Location: index.php?c=cliente'); exit;
    }

    public function eliminar(): void {
        $cedula = $_GET['cedula'] ?? '';
        if ($this->clienteModel->delete($cedula)) {
            $_SESSION['msg'] = ['tipo' => 'success', 'texto' => 'Cliente eliminado.'];
        } else {
            $_SESSION['msg'] = ['tipo' => 'error', 'texto' => 'No se pudo eliminar (tiene compras asociadas).'];
        }
        header('Location: index.php?c=cliente'); exit;
    }
}
?>