<?php
// Vista: Detalle línea a línea de una compra
// Variables esperadas: $detalle (mismo formato que resumen.php)
// Nota: esta vista es un alias de resumen enfocado en el desglose por producto.
// Se puede usar desde el controlador si se prefiere una pantalla dedicada al detalle.
 
$ruta_css = '../Public/Css/Estilos.css';
 
$cab           = $detalle[0] ?? [];
$gran_total    = 0;
$gran_subtotal = 0;
$gran_impuesto = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle Compra #<?= $cab['id_compra'] ?? '' ?> — Tienda</title>
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
    <a href="index.php?c=compra">Compras</a>
    <span class="sep">›</span>
    <a href="index.php?c=compra&a=resumen&id=<?= $cab['id_compra'] ?>">Resumen #<?= $cab['id_compra'] ?></a>
    <span class="sep">›</span>
    <span>Detalle</span>
  </div>
 
  <!-- Header -->
  <div class="page-header">
    <h1><span></span>Detalle de Compra <span class="badge badge-azul">#<?= $cab['id_compra'] ?></span></h1>
    <a href="index.php?c=compra&a=resumen&id=<?= $cab['id_compra'] ?>" class="btn btn-outline">← Volver al resumen</a>
  </div>
 
  <!-- Info cliente + fecha -->
  <div class="info-banner" style="margin-bottom: 1.8rem;">
    <span class="info-banner-icon"></span>
    <div style="display:flex; gap:2rem; flex-wrap:wrap;">
      <span><strong><?= htmlspecialchars($cab['cliente_nombre'] . ' ' . $cab['cliente_apellido']) ?></strong></span>
      <span>Cédula: <?= htmlspecialchars($cab['cedula']) ?></span>
      <span> <?= date('d/m/Y', strtotime($cab['fecha'])) ?></span>
    </div>
  </div>
 
  <!-- Tabla detallada por producto -->
  <div class="card">
    <?php if (empty($detalle)): ?>
      <div class="empty-state">
        <div class="empty-icon"></div>
        <p>Esta compra no tiene líneas de detalle.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Código</th>
              <th>Producto</th>
              <th>Categoría</th>
              <th>Precio unit.</th>
              <th>Imp. %</th>
              <th>Cantidad</th>
              <th>Subtotal</th>
              <th>Valor impuesto</th>
              <th>Total línea</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($detalle as $i => $linea):
              $gran_subtotal += $linea['subtotal'];
              $gran_impuesto += $linea['valor_impuesto'];
              $gran_total    += $linea['total_linea'];
            ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td>
                <span class="badge badge-azul"><?= htmlspecialchars($linea['producto_codigo']) ?></span>
              </td>
              <td><?= htmlspecialchars($linea['producto_nombre']) ?></td>
              <td>
                <span class="badge badge-verde"><?= htmlspecialchars($linea['categoria_nombre']) ?></span>
              </td>
              <td>$ <?= number_format($linea['precio_unitario'], 2, ',', '.') ?></td>
              <td><?= $linea['impuesto'] ?>%</td>
              <td><?= $linea['cantidad'] ?> u.</td>
              <td>$ <?= number_format($linea['subtotal'], 2, ',', '.') ?></td>
              <td>$ <?= number_format($linea['valor_impuesto'], 2, ',', '.') ?></td>
              <td><strong>$ <?= number_format($linea['total_linea'], 2, ',', '.') ?></strong></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="7" style="text-align:right;"><strong>Subtotal sin impuestos:</strong></td>
              <td>$ <?= number_format($gran_subtotal, 2, ',', '.') ?></td>
              <td>$ <?= number_format($gran_impuesto, 2, ',', '.') ?></td>
              <td></td>
            </tr>
            <tr class="fila-total">
              <td colspan="9" style="text-align:right;"><strong>Total a pagar:</strong></td>
              <td>
                <strong class="monto-total monto-destacado">
                  $ <?= number_format($gran_total, 2, ',', '.') ?>
                </strong>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
 
      <div class="tabla-footer">
        <span class="texto-vacio">
          <strong><?= count($detalle) ?></strong> línea<?= count($detalle) !== 1 ? 's' : '' ?> de producto
        </span>
      </div>
    <?php endif; ?>
  </div>
 
  <div style="display:flex; gap:0.8rem; margin-top:1.5rem;">
    <a href="index.php?c=compra&a=resumen&id=<?= $cab['id_compra'] ?>"
       class="btn btn-primary"> Ver resumen</a>
    <a href="index.php?c=compra" class="btn btn-outline">← Volver a compras</a>
  </div>
 
</div>
 
<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>