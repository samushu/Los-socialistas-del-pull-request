<?php
// Vista: Clientes frecuentes — ordenados por número de compras
// Variables esperadas:
//   $clientes_frecuentes (array) — todos los clientes con total_compras y valor_total
//   $cliente_top         (array|false) — el cliente con más compras
$ruta_css = '../Public/Css/Estilos.css';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes Frecuentes — Tienda</title>
  <link rel="stylesheet" href="<?= $ruta_css ?>">
</head>
<body>
 
<nav class="navbar">
  <a href="index.php" class="navbar-brand"><span>Los</span>Socialistas</a>
  <ul class="navbar-nav">
    <li><a href="index.php?c=reporte" class="active">Dashboard</a></li>
    <li><a href="index.php?c=categoria">Categorías</a></li>
    <li><a href="index.php?c=producto">Productos</a></li>
    <li><a href="index.php?c=cliente">Clientes</a></li>
    <li><a href="index.php?c=proveedor">Proveedores</a></li>
    <li><a href="index.php?c=compra">Compras</a></li>
  </ul>
</nav>
 
<div class="wrapper">
 
  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <a href="index.php?c=reporte">Inicio</a>
    <span class="sep">›</span>
    <span>Clientes frecuentes</span>
  </div>
 
  <!-- Header -->
  <div class="page-header">
    <h1><span></span>Clientes Frecuentes</h1>
    <a href="index.php?c=reporte" class="btn btn-outline">← Volver al dashboard</a>
  </div>
 
  <!-- Banner cliente top -->
  <?php if (!empty($cliente_top)): ?>
    <div class="info-banner" style="margin-bottom:1.8rem;">
      <span class="info-banner-icon">🏆</span>
      <div style="display:flex; gap:2rem; flex-wrap:wrap; align-items:center;">
        <span><strong>Cliente más frecuente:</strong></span>
        <span><strong><?= htmlspecialchars($cliente_top['nombre'] . ' ' . $cliente_top['apellido']) ?></strong></span>
        <span>Cédula: <?= htmlspecialchars($cliente_top['cedula']) ?></span>
        <span>
          <span class="badge badge-azul"><?= $cliente_top['total_compras'] ?> compra<?= $cliente_top['total_compras'] != 1 ? 's' : '' ?></span>
        </span>
        <span class="monto-total">$ <?= number_format($cliente_top['valor_total'], 2, ',', '.') ?></span>
      </div>
    </div>
  <?php endif; ?>
 
  <!-- Tabla clientes frecuentes -->
  <div class="card">
    <?php if (empty($clientes_frecuentes)): ?>
      <div class="empty-state">
        <div class="empty-icon">👤</div>
        <p>No hay clientes con compras registradas aún.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre</th>
              <th>Cédula</th>
              <th>Teléfono</th>
              <th>N° compras</th>
              <th>Valor total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($clientes_frecuentes as $i => $cl): ?>
            <tr <?= $i === 0 ? 'class="fila-total"' : '' ?>>
              <td>
                <?php if ($i === 0): ?>
                  <span class="badge badge-amarillo">🥇 1</span>
                <?php elseif ($i === 1): ?>
                  <span class="badge badge-azul">🥈 2</span>
                <?php elseif ($i === 2): ?>
                  <span class="badge badge-rosa">🥉 3</span>
                <?php else: ?>
                  <span class="badge badge-verde"><?= $i + 1 ?></span>
                <?php endif; ?>
              </td>
              <td><strong><?= htmlspecialchars($cl['nombre'] . ' ' . $cl['apellido']) ?></strong></td>
              <td><?= htmlspecialchars($cl['cedula']) ?></td>
              <td>
                <?= !empty($cl['telefono']) ? htmlspecialchars($cl['telefono']) : '<span class="texto-vacio">—</span>' ?>
              </td>
              <td>
                <span class="badge badge-azul"><?= $cl['total_compras'] ?></span>
              </td>
              <td class="monto-total">$ <?= number_format($cl['valor_total'], 2, ',', '.') ?></td>
              <td>
                <a href="index.php?c=compra&a=historial&cedula=<?= urlencode($cl['cedula']) ?>"
                   class="btn btn-warning btn-sm">Ver historial</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
 
      <div class="tabla-footer">
        <span class="texto-vacio">
          <strong><?= count($clientes_frecuentes) ?></strong>
          cliente<?= count($clientes_frecuentes) !== 1 ? 's' : '' ?> en total
        </span>
      </div>
    <?php endif; ?>
  </div>
 
</div>
 
<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>
 