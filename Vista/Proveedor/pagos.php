<?php
// Vista: Historial de pagos a un proveedor y formulario para nuevo pago
// Variables esperadas:
//   $proveedor (array)  — datos del proveedor
//   $pagos     (array)  — historial de pagos a este proveedor
$ruta_css = '../Public/Css/Estilos.css';
 
$total_pagado = array_sum(array_column($pagos ?? [], 'monto'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pagos — <?= htmlspecialchars($proveedor['nombre']) ?></title>
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
    <a href="index.php?c=proveedor">Proveedores</a>
    <span class="sep">›</span>
    <span>Pagos — <?= htmlspecialchars($proveedor['nombre']) ?></span>
  </div>
 
  <div class="page-header">
    <h1><span></span>Pagos al Proveedor</h1>
    <a href="index.php?c=proveedor" class="btn btn-outline">Volver</a>
  </div>
 
  <!-- Datos del proveedor -->
  <div class="info-banner" style="margin-bottom:1.8rem;">
    <div style="display:flex; gap:2rem; flex-wrap:wrap;">
      <span><strong><?= htmlspecialchars($proveedor['nombre']) ?></strong></span>
      <?php if (!empty($proveedor['ciudad'])): ?>
        <span class="dato-contacto"><?= htmlspecialchars($proveedor['ciudad']) ?></span>
      <?php endif; ?>
      <?php if (!empty($proveedor['telefono'])): ?>
        <span class="dato-contacto"><?= htmlspecialchars($proveedor['telefono']) ?></span>
      <?php endif; ?>
      <span class="dato-contacto">
        Total pagado: <strong class="monto-total">$ <?= number_format($total_pagado, 2, ',', '.') ?></strong>
      </span>
    </div>
  </div>
 
  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['msg']['tipo'] === 'success' ? 'success' : 'error' ?>">
      <?= htmlspecialchars($_SESSION['msg']['texto']) ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>
 
  <!-- Formulario nuevo pago -->
  <div class="card" style="margin-bottom:1.5rem;">
    <h2 class="card-section-title">Registrar pago</h2>
 
    <form method="POST" action="index.php?c=proveedor&a=registrarPago">
      <input type="hidden" name="id_proveedor" value="<?= $proveedor['id_proveedor'] ?>">
 
      <div class="form-row" style="align-items:flex-end; gap:1rem;">
        <div class="form-group" style="flex:1; min-width:160px;">
          <label class="form-label" for="fecha">Fecha</label>
          <input
            type="date"
            id="fecha"
            name="fecha"
            class="form-control"
            value="<?= date('Y-m-d') ?>"
            required
          >
        </div>
 
        <div class="form-group" style="flex:1; min-width:160px;">
          <label class="form-label" for="monto">Monto ($)</label>
          <input
            type="number"
            id="monto"
            name="monto"
            class="form-control"
            placeholder="Ej: 150000.00"
            min="0.01"
            step="0.01"
            required
          >
        </div>
 
        <div style="padding-bottom:0.2rem;">
          <button type="submit" class="btn btn-success">Registrar pago</button>
        </div>
      </div>
    </form>
  </div>
 
  <!-- Historial de pagos -->
  <div class="card">
    <h2 class="card-section-title">Historial de pagos</h2>
 
    <?php if (empty($pagos)): ?>
      <div class="empty-state">
        <div class="empty-icon"></div>
        <p>No hay pagos registrados para este proveedor.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th># Pago</th>
              <th>Fecha</th>
              <th>Monto</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pagos as $pago): ?>
            <tr>
              <td><span class="badge badge-azul">#<?= $pago['id_pago'] ?></span></td>
              <td>
                <span class="dato-contacto">
                  <?= date('d/m/Y', strtotime($pago['fecha'])) ?>
                </span>
              </td>
              <td>
                <strong class="monto-total">$ <?= number_format($pago['monto'], 2, ',', '.') ?></strong>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="fila-total">
              <td colspan="2"><strong>Total pagado</strong></td>
              <td>
                <strong class="monto-total monto-destacado">
                  $ <?= number_format($total_pagado, 2, ',', '.') ?>
                </strong>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
 
      <div class="tabla-footer">
        <span class="texto-vacio">
          <strong><?= count($pagos) ?></strong>
          pago<?= count($pagos) !== 1 ? 's' : '' ?> registrado<?= count($pagos) !== 1 ? 's' : '' ?>
        </span>
      </div>
    <?php endif; ?>
  </div>
 
</div>
 
<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>
 