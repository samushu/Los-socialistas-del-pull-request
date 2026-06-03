<?php
// Vista: Editar producto existente
// Variables esperadas: $producto (array), $categorias (array)
$ruta_css = '../Public/Css/Estilos.css';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Producto — Tienda</title>
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
 
  <div class="breadcrumb">
    <a href="index.php?c=reporte">Inicio</a>
    <span class="sep">›</span>
    <a href="index.php?c=producto">Productos</a>
    <span class="sep">›</span>
    <span>Editar</span>
  </div>
 
  <div class="page-header">
    <h1><span></span>Editar Producto</h1>
  </div>
 
  <!-- Banner con datos actuales del producto -->
  <div class="info-banner" style="margin-bottom: 1.5rem;">
    <span class="info-banner-icon"></span>
    <div style="display:flex; gap:2rem; flex-wrap:wrap;">
      <span><strong><?= htmlspecialchars($producto['nombre']) ?></strong></span>
      <span class="dato-contacto">Código: <?= htmlspecialchars($producto['codigo']) ?></span>
      <span class="dato-contacto">Categoría: <?= htmlspecialchars($producto['categoria_nombre']) ?></span>
      <span class="dato-contacto">Stock actual:
        <?php if ($producto['cantidad'] < 5): ?>
          <span class="badge badge-rosa"> <?= $producto['cantidad'] ?> u.</span>
        <?php else: ?>
          <span class="badge badge-verde"><?= $producto['cantidad'] ?> u.</span>
        <?php endif; ?>
      </span>
    </div>
  </div>
 
  <div class="card form-card">
    <?php include __DIR__ . '/_form.php'; ?>
  </div>
 
</div>
 
<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>