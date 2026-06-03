<?php
// Vista: Lista general de proveedores
// Variables esperadas: $proveedores (array)
$ruta_css = '../Public/Css/Estilos.css';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Proveedores — Tienda</title>
  <link rel="stylesheet" href="<?= $ruta_css ?>">
</head>
<body>
 
<nav class="navbar">
  <a href="index.php" class="navbar-brand"><span>Los</span>Socialistas</a>
  <ul class="navbar-nav">
    <li><a href="index.php?c=reporte">Dashboard</a></li>
    <li><a href="index.php?c=categoria">Categorias</a></li>
    <li><a href="index.php?c=producto">Productos</a></li>
    <li><a href="index.php?c=cliente">Clientes</a></li>
    <li><a href="index.php?c=proveedor" class="active">Proveedores</a></li>
    <li><a href="index.php?c=compra">Compras</a></li>
  </ul>
</nav>
 
<div class="wrapper">
 
  <div class="breadcrumb">
    <a href="index.php?c=reporte">Inicio</a>
    <span class="sep">›</span>
    <span>Proveedores</span>
  </div>
 
  <div class="page-header">
    <h1><span></span>Proveedores</h1>
    <a href="index.php?c=proveedor&a=crear" class="btn btn-primary">+ Nuevo proveedor</a>
  </div>
 
  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['msg']['tipo'] === 'success' ? 'success' : 'error' ?>">
      <?= htmlspecialchars($_SESSION['msg']['texto']) ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>
 
  <div class="card">
    <?php if (empty($proveedores)): ?>
      <div class="empty-state">
        <div class="empty-icon"></div>
        <p>No hay proveedores registrados aun.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre</th>
              <th>Telefono</th>
              <th>Ciudad</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($proveedores as $prov): ?>
            <tr>
              <td><?= $prov['id_proveedor'] ?></td>
              <td><strong><?= htmlspecialchars($prov['nombre']) ?></strong></td>
              <td>
                <?php if (!empty($prov['telefono'])): ?>
                  <span class="dato-contacto"><?= htmlspecialchars($prov['telefono']) ?></span>
                <?php else: ?>
                  <span class="texto-vacio">—</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($prov['ciudad'])): ?>
                  <span class="badge badge-azul"><?= htmlspecialchars($prov['ciudad']) ?></span>
                <?php else: ?>
                  <span class="texto-vacio">—</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="table-actions">
                  <a href="index.php?c=proveedor&a=editar&id=<?= $prov['id_proveedor'] ?>"
                     class="btn btn-warning btn-sm">Editar</a>
                  <a href="index.php?c=proveedor&a=asociar&id=<?= $prov['id_proveedor'] ?>"
                     class="btn btn-primary btn-sm">Productos</a>
                  <a href="index.php?c=proveedor&a=pagos&id=<?= $prov['id_proveedor'] ?>"
                     class="btn btn-outline btn-sm">Pagos</a>
                  <a href="index.php?c=proveedor&a=eliminar&id=<?= $prov['id_proveedor'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('Eliminar al proveedor <?= htmlspecialchars($prov['nombre']) ?>?')">
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
          <strong><?= count($proveedores) ?></strong>
          proveedor<?= count($proveedores) !== 1 ? 'es' : '' ?> registrado<?= count($proveedores) !== 1 ? 's' : '' ?>
        </span>
      </div>
    <?php endif; ?>
  </div>
 
</div>
 
<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>