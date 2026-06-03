<?php
// Vista: Resumen completo de una compra
// Variables esperadas: $detalle (array de filas con datos de compra + líneas)
// Cada fila incluye: id_compra, fecha, cedula, cliente_nombre, cliente_apellido,
//   telefono, correo, producto_nombre, producto_codigo, categoria_nombre,
//   cantidad, precio_unitario, impuesto, subtotal, valor_impuesto, total_linea
 
$ruta_css = '../Public/Css/Estilos.css';
 
// Extraer cabecera de la primera fila
$cab = $detalle[0] ?? [];
$gran_total      = 0;
$gran_subtotal   = 0;
$gran_impuesto   = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resumen Compra #<?= $cab['id_compra'] ?? '' ?> — Tienda</title>
  <link rel="stylesheet" href="<?= $ruta_css ?>">
</head>
<body>
 
<nav class="navbar">
  <a href="index.php" class="navbar-brand">🛒 <span>Los</span>Socialistas</a>
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
    <a href="index.php?c=compra">Compras</a>
    <span class="sep">›</span>
    <span>Resumen #<?= $cab['id_compra'] ?></span>
  </div>
 
  <!-- Header -->
  <div class="page-header">
    <h1><span></span>Resumen de Compra <span class="badge badge-azul">#<?= $cab['id_compra'] ?></span></h1>
    <a href="index.php?c=compra" class="btn btn-outline">← Volver a compras</a>
  </div>
 
  <!-- Mensaje flash -->
  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['msg']['tipo'] === 'success' ? 'success' : 'error' ?>">
      <?= htmlspecialchars($_SESSION['msg']['texto']) ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>
 
  <!-- Banner de datos del cliente -->
  <div class="info-banner" style="margin-bottom: 1.8rem;">
    <span class="info-banner-icon">🪪</span>
    <div style="display:flex; gap:2rem; flex-wrap:wrap;">
      <span><strong><?= htmlspecialchars($cab['cliente_nombre'] . ' ' . $cab['cliente_apellido']) ?></strong></span>
      <span>Cédula: <?= htmlspecialchars($cab['cedula']) ?></span>
      <span> <?= date('d/m/Y', strtotime($cab['fecha'])) ?></span>
      <?php if (!empty($cab['telefono'])): ?>
        <span> <?= htmlspecialchars($cab['telefono']) ?></span>
      <?php endif; ?>
      <?php if (!empty($cab['correo'])): ?>
        <span> <?= htmlspecialchars($cab['correo']) ?></span>
      <?php endif; ?>
    </div>
  </div>
 
  <!-- Tabla de detalle -->
  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Código</th>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Precio unit.</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th>Impuesto</th>
            <th>Total línea</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($detalle as $linea):
            $gran_subtotal += $linea['subtotal'];
            $gran_impuesto += $linea['valor_impuesto'];
            $gran_total    += $linea['total_linea'];
          ?>
          <tr>
            <td>
              <span class="badge badge-azul"><?= htmlspecialchars($linea['producto_codigo']) ?></span>
            </td>
            <td><?= htmlspecialchars($linea['producto_nombre']) ?></td>
            <td>
              <span class="badge badge-verde"><?= htmlspecialchars($linea['categoria_nombre']) ?></span>
            </td>
            <td>$ <?= number_format($linea['precio_unitario'], 2, ',', '.') ?></td>
            <td><?= $linea['cantidad'] ?> u.</td>
            <td>$ <?= number_format($linea['subtotal'], 2, ',', '.') ?></td>
            <td>
              <?= $linea['impuesto'] ?>%
              <small class="form-hint">($ <?= number_format($linea['valor_impuesto'], 2, ',', '.') ?>)</small>
            </td>
            <td><strong>$ <?= number_format($linea['total_linea'], 2, ',', '.') ?></strong></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" style="text-align:right;"><strong>Subtotal sin impuestos:</strong></td>
            <td colspan="3">$ <?= number_format($gran_subtotal, 2, ',', '.') ?></td>
          </tr>
          <tr>
            <td colspan="5" style="text-align:right;"><strong>Total impuestos:</strong></td>
            <td colspan="3">$ <?= number_format($gran_impuesto, 2, ',', '.') ?></td>
          </tr>
          <tr class="fila-total">
            <td colspan="5" style="text-align:right;"><strong>Total a pagar:</strong></td>
            <td colspan="3">
              <strong class="monto-total monto-destacado">
                $ <?= number_format($gran_total, 2, ',', '.') ?>
              </strong>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
 
  <!-- Acciones -->
  <div style="display:flex; gap:0.8rem; margin-top:1.5rem;">
    <a href="index.php?c=cliente&a=historial&cedula=<?= urlencode($cab['cedula']) ?>"
       class="btn btn-warning"> Ver historial del cliente</a>
    <a href="index.php?c=compra&a=eliminar&id=<?= $cab['id_compra'] ?>"
       class="btn btn-danger"
       onclick="return confirm('¿Eliminar la compra #<?= $cab['id_compra'] ?>? Esta acción no se puede deshacer.')">
        Eliminar compra</a>
    <a href="index.php?c=compra" class="btn btn-outline">← Volver</a>
  </div>
 
</div>
 
<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>