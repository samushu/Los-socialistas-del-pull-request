<?php
// Vista: Lista general de productos
// Variables esperadas: $productos (array), $stock_bajo (array), $categorias (array)
$ruta_css = '../Public/Css/Estilos.css';
 
// Colores de badge por categoría
$colores = [
    'Papeleria'    => 'azul',
    'Drogueria'    => 'verde',
    'Supermercado' => 'amarillo',
    'Aseo'         => 'rosa',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Productos — Tienda</title>
  <link rel="stylesheet" href="<?= $ruta_css ?>">
</head>
<body>
 
<nav class="navbar">
  <a href="index.php" class="navbar-brand"> <span>Los</span>Socialistas</a>
  <ul class="navbar-nav">
    <li><a href="index.php?c=reporte">Dashboard</a></li>
    <li><a href="index.php?c=categoria">Categorías</a></li>
    <li><a href="index.php?c=producto" class="active">Productos</a></li>
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
    <span>Productos</span>
  </div>
 
  <!-- Header -->
  <div class="page-header">
    <h1><span></span>Productos</h1>
    <a href="index.php?c=producto&a=crear" class="btn btn-primary">+ Nuevo producto</a>
  </div>
 
  <!-- Mensaje flash -->
  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['msg']['tipo'] === 'success' ? 'success' : 'error' ?>">
      <?= htmlspecialchars($_SESSION['msg']['texto']) ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>
 
  <!-- Alerta de stock bajo -->
  <?php if (!empty($stock_bajo)): ?>
    <div class="alert alert-error" style="margin-bottom: 1.5rem;">
       <strong><?= count($stock_bajo) ?></strong> producto<?= count($stock_bajo) !== 1 ? 's' : '' ?>
      con stock bajo (menos de 5 unidades):
      <?php foreach ($stock_bajo as $i => $sp): ?>
        <strong><?= htmlspecialchars($sp['nombre']) ?></strong>
        (<?= $sp['cantidad'] ?> u.)<?= $i < count($stock_bajo) - 1 ? ', ' : '.' ?>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
 
  <!-- Tabla de productos -->
  <div class="card">
    <?php if (empty($productos)): ?>
      <div class="empty-state">
        <div class="empty-icon"></div>
        <p>No hay productos registrados aún.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Código</th>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Precio unit.</th>
              <th>Impuesto</th>
              <th>Stock</th>
              <th>Peso (kg)</th>
              <th>Empaque</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($productos as $p):
              $color     = $colores[$p['categoria_nombre']] ?? 'azul';
              $stockBajo = $p['cantidad'] < 5;
            ?>
            <tr <?= $stockBajo ? 'class="fila-alerta"' : '' ?>>
              <td>
                <span class="badge badge-azul"><?= htmlspecialchars($p['codigo']) ?></span>
              </td>
              <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
              <td>
                <span class="badge badge-<?= $color ?>">
                  <?= htmlspecialchars($p['categoria_nombre']) ?>
                </span>
              </td>
              <td class="monto-total">$ <?= number_format($p['precio_unitario'], 2, ',', '.') ?></td>
              <td><?= $p['impuesto'] ?>%</td>
              <td>
                <?php if ($stockBajo): ?>
                  <span class="badge badge-rosa"> <?= $p['cantidad'] ?></span>
                <?php else: ?>
                  <span class="badge badge-verde"><?= $p['cantidad'] ?></span>
                <?php endif; ?>
              </td>
              <td><?= $p['peso'] ? number_format($p['peso'], 2, ',', '.') : '—' ?></td>
              <td><?= htmlspecialchars($p['tipo_empaque'] ?? '—') ?></td>
              <td>
                <div class="table-actions">
                  <a href="index.php?c=producto&a=editar&id=<?= $p['id_producto'] ?>"
                     class="btn btn-warning btn-sm"> Editar</a>
                  <a href="index.php?c=producto&a=eliminar&id=<?= $p['id_producto'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Eliminar el producto «<?= htmlspecialchars($p['nombre']) ?>»? Si tiene compras asociadas no se podrá eliminar.')">
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
          <strong><?= count($productos) ?></strong> producto<?= count($productos) !== 1 ? 's' : '' ?> registrado<?= count($productos) !== 1 ? 's' : '' ?>
        </span>
      </div>
    <?php endif; ?>
  </div>
 
</div>
 
<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>
 