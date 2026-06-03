<?php
// Vista: Historial de compras de un cliente
$ruta_css = '../Public/Css/Estilos.css';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de Compras — Tienda</title>
  <link rel="stylesheet" href="<?= $ruta_css ?>">
</head>
<body>
 
<nav class="navbar">
  <a href="index.php" class="navbar-brand">🛒 <span>Los</span>Socialistas</a>
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
    <a href="index.php?c=cliente">Clientes</a>
    <span class="sep">›</span>
    <span>Historial de compras</span>
  </div>
 
  <!-- Header con datos del cliente -->
  <?php if ($cliente): ?>
  <div class="page-header">
    <h1><span></span><?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></h1>
    <a href="index.php?c=compra&a=nueva" class="btn btn-primary">+ Nueva compra</a>
  </div>
 
  <div class="info-banner" style="margin-bottom:1.8rem;">
    <span class="info-banner-icon">🪪</span>
    <div style="display:flex; gap:2rem; flex-wrap:wrap;">
      <span><strong>Cédula:</strong> <?= htmlspecialchars($cliente['cedula']) ?></span>
      <?php if (!empty($cliente['telefono'])): ?>
        <span>📞 <?= htmlspecialchars($cliente['telefono']) ?></span>
      <?php endif; ?>
      <?php if (!empty($cliente['correo'])): ?>
        <span>✉️ <?= htmlspecialchars($cliente['correo']) ?></span>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
 
  <!-- Mensaje flash -->
  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['msg']['tipo'] === 'success' ? 'success' : 'error' ?>">
      <?= htmlspecialchars($_SESSION['msg']['texto']) ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>
 
  <!-- Tabla de compras -->
  <div class="card">
    <?php if (empty($compras)): ?>
      <div class="empty-state">
        <div class="empty-icon">🧾</div>
        <p>Este cliente no tiene compras registradas aún.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th># Compra</th>
              <th>Fecha</th>
              <th>Productos</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $gran_total = 0;
            foreach ($compras as $compra):
              $gran_total += $compra['total'];
            ?>
            <tr>
              <td>
                <span class="badge badge-azul">#<?= $compra['id_compra'] ?></span>
              </td>
              <td>
                <span class="dato-contacto">📅 <?= date('d/m/Y', strtotime($compra['fecha'])) ?></span>
              </td>
              <td>
                <span class="badge badge-verde"><?= $compra['num_productos'] ?> ítem<?= $compra['num_productos'] != 1 ? 's' : '' ?></span>
              </td>
              <td>
                <strong class="monto-total">$ <?= number_format($compra['total'], 2, ',', '.') ?></strong>
              </td>
              <td>
                <div class="table-actions">
                  <a href="index.php?c=compra&a=resumen&id=<?= $compra['id_compra'] ?>"
                     class="btn btn-primary btn-sm">🔍 Ver resumen</a>
                  <a href="index.php?c=compra&a=eliminar&id=<?= $compra['id_compra'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Eliminar la compra #<?= $compra['id_compra'] ?>?')">
                     🗑️ Eliminar</a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="fila-total">
              <td colspan="3"><strong>Total acumulado del cliente</strong></td>
              <td colspan="2"><strong class="monto-total monto-destacado">$ <?= number_format($gran_total, 2, ',', '.') ?></strong></td>
            </tr>
          </tfoot>
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