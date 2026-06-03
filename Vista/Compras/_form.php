<?php
// Vista: Formulario para registrar una nueva compra
// Variables esperadas: $clientes (array), $productos (array)
$ruta_css = '../Public/Css/Estilos.css';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva Compra — Tienda</title>
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
    <span>Nueva compra</span>
  </div>
 
  <!-- Header -->
  <div class="page-header">
    <h1><span></span>Nueva Compra</h1>
  </div>
 
  <!-- Mensaje flash -->
  <?php if (!empty($_SESSION['msg'])): ?>
    <div class="alert alert-<?= $_SESSION['msg']['tipo'] === 'success' ? 'success' : 'error' ?>">
      <?= htmlspecialchars($_SESSION['msg']['texto']) ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>
 
  <form method="POST" action="index.php?c=compra&a=procesar" id="form-compra">
 
    <!-- Sección 1: datos de cabecera -->
    <div class="card" style="margin-bottom: 1.5rem;">
      <h2 class="card-section-title"> Datos de la compra</h2>
      <div class="form-row">
 
        <div class="form-group">
          <label class="form-label" for="cedula_cliente">Cliente</label>
          <select id="cedula_cliente" name="cedula_cliente" class="form-control" required>
            <option value="">— Seleccionar cliente —</option>
            <?php foreach ($clientes as $cl): ?>
              <option value="<?= htmlspecialchars($cl['cedula']) ?>">
                <?= htmlspecialchars($cl['nombre'] . ' ' . $cl['apellido']) ?>
                (<?= htmlspecialchars($cl['cedula']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
 
        <div class="form-group">
          <label class="form-label" for="fecha">Fecha</label>
          <input
            type="date"
            id="fecha"
            name="fecha"
            class="form-control"
            value="<?= date('Y-m-d') ?>"
          >
          <small class="form-hint">Si se deja vacío se usará la fecha de hoy.</small>
        </div>
 
      </div>
    </div>
 
    <!-- Sección 2: agregar productos al carrito -->
    <div class="card" style="margin-bottom: 1.5rem;">
      <h2 class="card-section-title"> Agregar productos</h2>
 
      <!-- Datos de productos disponibles para JS -->
      <script>
        const productosDisponibles = <?= json_encode(array_map(function($p) {
          return [
            'id'              => $p['id_producto'],
            'nombre'          => $p['nombre'],
            'codigo'          => $p['codigo'],
            'precio'          => (float) $p['precio_unitario'],
            'impuesto'        => (float) $p['impuesto'],
            'stock'           => (int) $p['cantidad'],
            'categoria'       => $p['categoria_nombre'],
          ];
        }, $productos), JSON_UNESCAPED_UNICODE) ?>;
      </script>
 
      <div class="form-row" style="align-items: flex-end; gap: 1rem;">
        <div class="form-group" style="flex: 2;">
          <label class="form-label" for="sel-producto">Producto</label>
          <select id="sel-producto" class="form-control">
            <option value="">— Seleccionar producto —</option>
            <?php foreach ($productos as $p): ?>
              <option
                value="<?= $p['id_producto'] ?>"
                data-precio="<?= $p['precio_unitario'] ?>"
                data-impuesto="<?= $p['impuesto'] ?>"
                data-stock="<?= $p['cantidad'] ?>"
                data-categoria="<?= htmlspecialchars($p['categoria_nombre']) ?>"
              >
                [<?= htmlspecialchars($p['codigo']) ?>] <?= htmlspecialchars($p['nombre']) ?>
                — $<?= number_format($p['precio_unitario'], 2, ',', '.') ?>
                (Stock: <?= $p['cantidad'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
 
        <div class="form-group" style="flex: 1; min-width: 120px;">
          <label class="form-label" for="sel-cantidad">Cantidad</label>
          <input type="number" id="sel-cantidad" class="form-control" value="1" min="1">
        </div>
 
        <div style="padding-bottom: 0.2rem;">
          <button type="button" class="btn btn-primary" onclick="agregarAlCarrito()">
             Agregar
          </button>
        </div>
      </div>
 
      <!-- Mensaje de validación inline -->
      <div id="msg-carrito" style="display:none;" class="alert"></div>
 
      <!-- Tabla del carrito -->
      <div class="table-wrap" id="wrap-carrito" style="display:none; margin-top:1rem;">
        <table id="tabla-carrito">
          <thead>
            <tr>
              <th>Código</th>
              <th>Producto</th>
              <th>Categoría</th>
              <th>Precio unit.</th>
              <th>Imp. %</th>
              <th>Cantidad</th>
              <th>Subtotal</th>
              <th>Total c/imp.</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="carrito-body"></tbody>
          <tfoot>
            <tr class="fila-total">
              <td colspan="7"><strong>Total a pagar</strong></td>
              <td colspan="2"><strong class="monto-total monto-destacado" id="gran-total">$ 0,00</strong></td>
            </tr>
          </tfoot>
        </table>
      </div>
 
      <!-- Inputs ocultos que se envían al servidor -->
      <div id="inputs-ocultos"></div>
    </div>
 
    <!-- Botones finales -->
    <div style="display:flex; gap:0.8rem;">
      <button type="submit" class="btn btn-success" id="btn-confirmar" disabled>
         Confirmar compra
      </button>
      <a href="index.php?c=compra" class="btn btn-outline">Cancelar</a>
    </div>
 
  </form>
</div>
 
<footer>Los Socialistas &copy; <?= date('Y') ?></footer>
 
<script>
// ── Estado del carrito ────────────────────────────────────
const carrito = {}; // { id_producto: { ...datos, cantidad } }
 
function mostrarMsgCarrito(texto, tipo) {
  const el = document.getElementById('msg-carrito');
  el.textContent = texto;
  el.className = 'alert alert-' + tipo;
  el.style.display = 'block';
  setTimeout(() => { el.style.display = 'none'; }, 3500);
}
 
function formatPeso(valor) {
  return '$ ' + Number(valor).toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
 
function agregarAlCarrito() {
  const sel      = document.getElementById('sel-producto');
  const id       = sel.value;
  const cantidad = parseInt(document.getElementById('sel-cantidad').value, 10);
 
  if (!id) { mostrarMsgCarrito('Selecciona un producto.', 'error'); return; }
  if (!cantidad || cantidad < 1) { mostrarMsgCarrito('La cantidad debe ser al menos 1.', 'error'); return; }
 
  const opt   = sel.options[sel.selectedIndex];
  const stock = parseInt(opt.dataset.stock, 10);
  const yaEnCarrito = carrito[id] ? carrito[id].cantidad : 0;
 
  if (yaEnCarrito + cantidad > stock) {
    mostrarMsgCarrito(`Stock insuficiente. Disponible: ${stock}, en carrito: ${yaEnCarrito}.`, 'error');
    return;
  }
 
  if (carrito[id]) {
    carrito[id].cantidad += cantidad;
  } else {
    carrito[id] = {
      id_producto : id,
      codigo      : opt.text.match(/\[(.+?)\]/)?.[1] ?? '',
      nombre      : opt.text.replace(/\[.+?\]\s*/, '').split('—')[0].trim(),
      categoria   : opt.dataset.categoria,
      precio      : parseFloat(opt.dataset.precio),
      impuesto    : parseFloat(opt.dataset.impuesto),
      stock       : stock,
      cantidad    : cantidad,
    };
  }
 
  renderCarrito();
  document.getElementById('sel-producto').value = '';
  document.getElementById('sel-cantidad').value = 1;
  mostrarMsgCarrito('Producto agregado al carrito.', 'success');
}
 
function quitarDelCarrito(id) {
  delete carrito[id];
  renderCarrito();
}
 
function renderCarrito() {
  const tbody   = document.getElementById('carrito-body');
  const wrap    = document.getElementById('wrap-carrito');
  const ocultos = document.getElementById('inputs-ocultos');
  const btnConf = document.getElementById('btn-confirmar');
  const ids     = Object.keys(carrito);
 
  tbody.innerHTML   = '';
  ocultos.innerHTML = '';
 
  if (ids.length === 0) {
    wrap.style.display = 'none';
    btnConf.disabled = true;
    document.getElementById('gran-total').textContent = '$ 0,00';
    return;
  }
 
  wrap.style.display = '';
  btnConf.disabled = false;
 
  let granTotal = 0;
 
  ids.forEach((id, i) => {
    const item     = carrito[id];
    const subtotal = item.cantidad * item.precio;
    const total    = subtotal * (1 + item.impuesto / 100);
    granTotal     += total;
 
    // Fila visible
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><span class="badge badge-azul">${item.codigo}</span></td>
      <td>${item.nombre}</td>
      <td><span class="badge badge-verde">${item.categoria}</span></td>
      <td>${formatPeso(item.precio)}</td>
      <td>${item.impuesto}%</td>
      <td>
        <input
          type="number"
          value="${item.cantidad}"
          min="1"
          max="${item.stock}"
          class="form-control"
          style="width:75px;"
          onchange="actualizarCantidad('${id}', this.value)"
        >
      </td>
      <td>${formatPeso(subtotal)}</td>
      <td><strong class="monto-total">${formatPeso(total)}</strong></td>
      <td>
        <button type="button" class="btn btn-danger btn-sm" onclick="quitarDelCarrito('${id}')">✕</button>
      </td>
    `;
    tbody.appendChild(tr);
 
    // Inputs ocultos para el POST
    ocultos.innerHTML += `
      <input type="hidden" name="productos[${i}][id_producto]" value="${id}">
      <input type="hidden" name="productos[${i}][cantidad]"    value="${item.cantidad}">
    `;
  });
 
  document.getElementById('gran-total').textContent = formatPeso(granTotal);
}
 
function actualizarCantidad(id, val) {
  const cantidad = parseInt(val, 10);
  if (!cantidad || cantidad < 1) return;
  if (cantidad > carrito[id].stock) {
    mostrarMsgCarrito(`Stock máximo disponible: ${carrito[id].stock}.`, 'error');
    return;
  }
  carrito[id].cantidad = cantidad;
  renderCarrito();
}
</script>
 
</body>
</html>