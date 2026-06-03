<?php
// Vista: Lista de categorías
$ruta_css = '../Public/Css/Estilos.css';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Categorías — Tienda</title>
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

  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <a href="index.php?c=reporte">Inicio</a>
    <span class="sep">›</span>
    <span>Categorías</span>
  </div>

  <!-- Header -->
  <div class="page-header">
    <h1><span></span>Categorías</h1>
    <a href="index.php?c=categoria&a=crear" class="btn btn-primary">+ Nueva categoría</a>
  </div>

  <!-- Mensaje flash -->
  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['msg']['tipo'] === 'success' ? 'success' : 'error' ?>">
      <?= htmlspecialchars($_SESSION['msg']['texto']) ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>

  <!-- Tabla -->
  <div class="card">
    <?php if (empty($categorias)): ?>
      <div class="empty-state">
        <div class="empty-icon">📂</div>
        <p>No hay categorías registradas aún.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $colores = ['Papeleria' => 'azul', 'Drogueria' => 'verde', 'Supermercado' => 'amarillo', 'Aseo' => 'rosa'];
            foreach ($categorias as $cat):
              $color = $colores[$cat['nombre']] ?? 'azul';
            ?>
            <tr>
              <td><?= $cat['id_categoria'] ?></td>
              <td>
                <span class="badge badge-<?= $color ?>">
                  <?= htmlspecialchars($cat['nombre']) ?>
                </span>
              </td>
              <td>
                <div class="table-actions">
                  <a href="index.php?c=categoria&a=editar&id=<?= $cat['id_categoria'] ?>"
                     class="btn btn-warning btn-sm">✏️ Editar</a>
                  <a href="index.php?c=categoria&a=eliminar&id=<?= $cat['id_categoria'] ?>"
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('¿Eliminar la categoría <?= htmlspecialchars($cat['nombre']) ?>?')">
                     🗑️ Eliminar</a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

</div>

<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
</body>
</html>