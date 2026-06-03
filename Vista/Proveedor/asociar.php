<?php
// Vista: Asociar productos a un proveedor
// Variables esperadas: $proveedor (array), $productos (array), $vinculados (array)
$ruta_css = '../../Public/Css/Estilos.css';
$vinculados_ids = array_column($vinculados, 'id_producto');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Productos de <?= htmlspecialchars($proveedor['nombre']) ?> — Tienda</title>
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
    <span><?= htmlspecialchars($proveedor['nombre']) ?></span>
  </div>

  <div class="page-header">
    <h1><span>📦</span> Productos del proveedor</h1>
    <a href="index.php?c=proveedor" class="btn btn-outline">← Volver</a>
  </div>

  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['msg']['tipo'] === 'success' ? 'success' : 'error' ?>">
      <?= htmlspecialchars($_SESSION['msg']['texto']) ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>

  <!-- Formulario para asociar nuevo producto -->
  <div class="card" style="margin-bottom: 2rem;">
    <h2 class="card-title">Asociar producto</h2>

    <?php
      $disponibles = array_filter($productos, fn($p) => !in_array($p['id_producto'], $vinculados_ids));
    ?>

    <?php if (empty($disponibles)): ?>
      <div class="alert alert-warning">Todos los productos ya están asociados a este proveedor.</div>
    <?php else: ?>
      <form method="POST" action="index.php?c=proveedor&a=guardarAsociacion">
        <input type="hidden" name="id_proveedor" value="<?= $proveedor['id_proveedor'] ?>">

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="id_producto">Producto</label>
            <select name="id_producto" id="id_producto" class="form-control" required>
              <option value="">— Selecciona un producto —</option>
              <?php foreach ($disponibles as $prod): ?>
                <option value="<?= $prod['id_producto'] ?>">
                  <?= htmlspecialchars($prod['codigo'] . ' — ' . $prod['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label" for="precio_compra">Precio de compra ($)</label>
            <input
              type="number"
              id="precio_compra"
              name="precio_compra"
              class="form-control"
              placeholder="0.00"
              step="0.01"
              min="0"
              required
            >
          </div>
        </div>

        <button type="submit" class="btn btn-success">Asociar producto</button>
      </form>
    <?php endif; ?>
  </div>

  <!-- Tabla de productos ya vinculados -->
  <div class="card">
    <h2 class="card-title">Productos vinculados</h2>

    <?php if (empty($vinculados)): ?>
      <div class="empty-state">
        <div class="empty-icon">📭</div>
        <p>Este proveedor aún no tiene productos asociados.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Código</th>
              <th>Producto</th>
              <th>Categoría</th>
              <th>Precio venta</th>
              <th>Precio compra</th>
              <th>Margen</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($vinculados as $v):
              $margen = $v['precio_unitario'] > 0
                ? round((($v['precio_unitario'] - $v['precio_compra']) / $v['precio_unitario']) * 100, 1)
                : 0;
              $badge = $margen >= 30 ? 'badge-verde' : ($margen >= 10 ? 'badge-amarillo' : 'badge-naranja');
            ?>
            <tr>
              <td><span class="badge badge-azul"><?= htmlspecialchars($v['codigo']) ?></span></td>
              <td><strong><?= htmlspecialchars($v['nombre']) ?></strong></td>
              <td><?= htmlspecialchars($v['categoria_nombre']) ?></td>
              <td>$<?= number_format($v['precio_unitario'], 2) ?></td>
              <td>$<?= number_format($v['precio_compra'], 2) ?></td>
              <td><span class="badge <?= $badge ?>"><?= $margen ?>%</span></td>
              <td>
                <a href="index.php?c=proveedor&a=desasociar&id_proveedor=<?= $proveedor['id_proveedor'] ?>&id_producto=<?= $v['id_producto'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('¿Quitar <?= htmlspecialchars($v['nombre']) ?> de este proveedor?')">
                   Quitar
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="tabla-footer">
        <span class="texto-vacio"><strong><?= count($vinculados) ?></strong> producto<?= count($vinculados) !== 1 ? 's' : '' ?> vinculado<?= count($vinculados) !== 1 ? 's' : '' ?></span>
      </div>
    <?php endif; ?>
  </div>

</div>

<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>