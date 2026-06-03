<?php
// Vista: Lista general de todas las compras
$ruta_css = '../Public/Css/Estilos.css';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compras — Tienda</title>
  <link rel="stylesheet" href="<?= $ruta_css ?>">
</head>
<body>
 
<nav class="navbar">
  <a href="index.php" class="navbar-brand"> <span>Los</span>Socialistas</a>
  <ul class="navbar-nav">
    <li><a href="index.php?c=reporte">Dashboard</a></li>
    <li><a href="index.php?c=categoria">Categorías</a></li>
    <li><a href="index.php?c=producto">Productos</a></li>
    <li><a href="index.php?c=cliente">Clientes</a></li>
    <li><a href="index.php?c=proveedor">Proveedores</a></li>
    <li><a href="index.php?c=compra" class="active">Compras</a></li>
  </ul>
</nav>
 
<div class="wrapper">
 
  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <a href="index.php?c=reporte">Inicio</a>
    <span class="sep">›</span>
    <span>Compras</span>
  </div>
 
  <!-- Header -->
  <div class="page-header">
    <h1><span></span>Compras</h1>
    <a href="index.php?c=compra&a=nueva" class="btn btn-primary">+ Nueva compra</a>
  </div>
 
  <!-- Mensaje flash -->
  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['msg']['tipo'] === 'success' ? 'success' : 'error' ?>">
      <?= htmlspecialchars($_SESSION['msg']['texto']) ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>
 
  <!-- Tabla -->
  <div class="card">
    <?php if (empty($compras)): ?>
      <div class="empty-state">
        <div class="empty-icon"></div>
        <p>No hay compras registradas aún.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th># Compra</th>
              <th>Cliente</th>
              <th>Cédula</th>
              <th>Fecha</th>
              <th>Productos</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($compras as $compra): ?>
            <tr>
              <td>
                <span class="badge badge-azul">#<?= $compra['id_compra'] ?></span>
              </td>
              <td>
                <strong><?= htmlspecialchars($compra['nombre'] . ' ' . $compra['apellido']) ?></strong>
              </td>
              <td>
                <span class="dato-contacto"> <?= htmlspecialchars($compra['cedula']) ?></span>
              </td>
              <td>
                <span class="dato-contacto"> <?= date('d/m/Y', strtotime($compra['fecha'])) ?></span>
              </td>
              <td>
                <span class="badge badge-verde"><?= $compra['num_productos'] ?> ítem<?= $compra['num_productos'] != 1 ? 's' : '' ?></span>
              </td>
              <td>
                <strong class="monto-total">$ <?= number_format($compra['total'] ?? 0, 2, ',', '.') ?></strong>
              </td>
              <td>
                <div class="table-actions">
                  <a href="index.php?c=compra&a=resumen&id=<?= $compra['id_compra'] ?>"
                     class="btn btn-primary btn-sm"> Resumen</a>
                  <a href="index.php?c=cliente&a=historial&cedula=<?= urlencode($compra['cedula']) ?>"
                     class="btn btn-warning btn-sm"> Historial</a>
                  <a href="index.php?c=compra&a=eliminar&id=<?= $compra['id_compra'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Eliminar la compra #<?= $compra['id_compra'] ?>? Esta acción no se puede deshacer.')">
                      Eliminar</a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
 
      <div class="tabla-footer">
        <span class="texto-vacio">
          <strong><?= count($compras) ?></strong> compra<?= count($compras) !== 1 ? 's' : '' ?> registrada<?= count($compras) !== 1 ? 's' : '' ?>
        </span>
      </div>
    <?php endif; ?>
  </div>
 
</div>
 
<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>