<?php
// Vista: Crear nueva categoría
$ruta_css = '../Public/Css/Estilos.css';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva Categoría — Tienda</title>
  <link rel="stylesheet" href="<?= $ruta_css ?>">
</head>
<body>

<nav class="navbar">
  <a href="index.php" class="navbar-brand">🛒 <span>Los</span>Socialistas</a>
  <ul class="navbar-nav">
    <li><a href="index.php?c=reporte">Dashboard</a></li>
    <li><a href="index.php?c=categoria" class="active">Categorías</a></li>
    <li><a href="index.php?c=producto">Productos</a></li>
    <li><a href="index.php?c=cliente">Clientes</a></li>
    <li><a href="index.php?c=proveedor">Proveedores</a></li>
    <li><a href="index.php?c=compra">Compras</a></li>
  </ul>
</nav>

<div class="wrapper">

  <div class="breadcrumb">
    <a href="index.php?c=reporte">Inicio</a>
    <span class="sep">›</span>
    <a href="index.php?c=categoria">Categorías</a>
    <span class="sep">›</span>
    <span>Nueva</span>
  </div>

  <div class="page-header">
    <h1><span></span>Nueva Categoría</h1>
  </div>

  <div class="card" style="max-width: 500px;">
    <?php include __DIR__ . '/_form.php'; ?>
  </div>

</div>

<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>