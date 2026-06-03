<?php
// Vista: Lista de clientes
$ruta_css = '../Public/Css/Estilos.css';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes — Tienda</title>
  <link rel="stylesheet" href="<?= $ruta_css ?>">
</head>
<body>
 
<nav class="navbar">
  <a href="index.php" class="navbar-brand"> <span>Los</span>Socialistas</a>
  <ul class="navbar-nav">
    <li><a href="index.php?c=reporte">Dashboard</a></li>
    <li><a href="index.php?c=categoria">Categorías</a></li>
    <li><a href="index.php?c=producto">Productos</a></li>
    <li><a href="index.php?c=cliente" class="active">Clientes</a></li>
    <li><a href="index.php?c=proveedor">Proveedores</a></li>
    <li><a href="index.php?c=compra">Compras</a></li>
  </ul>
</nav>
 
<div class="wrapper">
 
  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <a href="index.php?c=reporte">Inicio</a>
    <span class="sep">›</span>
    <span>Clientes</span>
  </div>
 
  <!-- Header -->
  <div class="page-header">
    <h1><span></span>Clientes</h1>
    <a href="index.php?c=cliente&a=crear" class="btn btn-primary">+ Nuevo cliente</a>
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
    <?php if (empty($clientes)): ?>
      <div class="empty-state">
        <div class="empty-icon"></div>
        <p>No hay clientes registrados aún.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Cédula</th>
              <th>Nombre completo</th>
              <th>Teléfono</th>
              <th>Correo</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($clientes as $cli): ?>
            <tr>
              <td>
                <span class="badge badge-azul">
                  <?= htmlspecialchars($cli['cedula']) ?>
                </span>
              </td>
              <td>
                <div class="cliente-nombre">
                  <strong><?= htmlspecialchars($cli['nombre'] . ' ' . $cli['apellido']) ?></strong>
                </div>
              </td>
              <td>
                <?php if (!empty($cli['telefono'])): ?>
                  <span class="dato-contacto"> <?= htmlspecialchars($cli['telefono']) ?></span>
                <?php else: ?>
                  <span class="texto-vacio">—</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($cli['correo'])): ?>
                  <span class="dato-contacto"> <?= htmlspecialchars($cli['correo']) ?></span>
                <?php else: ?>
                  <span class="texto-vacio">—</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="table-actions">
                  <a href="index.php?c=compra&a=historial&cedula=<?= urlencode($cli['cedula']) ?>"
                     class="btn btn-primary btn-sm"> Compras</a>
                  <a href="index.php?c=cliente&a=editar&cedula=<?= urlencode($cli['cedula']) ?>"
                     class="btn btn-warning btn-sm"> Editar</a>
                  <a href="index.php?c=cliente&a=eliminar&cedula=<?= urlencode($cli['cedula']) ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Eliminar al cliente <?= htmlspecialchars($cli['nombre'] . ' ' . $cli['apellido']) ?>?')">
                      Eliminar</a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
 
      <!-- Contador -->
      <div class="tabla-footer">
        <span class="texto-vacio">Total: <strong><?= count($clientes) ?></strong> cliente<?= count($clientes) !== 1 ? 's' : '' ?> registrado<?= count($clientes) !== 1 ? 's' : '' ?></span>
      </div>
    <?php endif; ?>
  </div>
 
</div>
 
<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>