<?php
// Partial: formulario reutilizable para crear y editar producto
// Variables esperadas:
//   $categorias (array)          — siempre presente
//   $producto   (array|null)     — presente solo al editar
//   $impuestos  (array)          — constante Producto::IMPUESTOS, presente al crear
 
$editando = !empty($producto);
$accion   = $editando
    ? 'index.php?c=producto&a=actualizar'
    : 'index.php?c=producto&a=guardar';
?>
<form method="POST" action="<?= $accion ?>">
 
  <?php if ($editando): ?>
    <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
  <?php endif; ?>
 
  <!-- Fila 1: código y nombre -->
  <div class="form-row">
    <div class="form-group">
      <label class="form-label" for="codigo">Código</label>
      <input
        type="text"
        id="codigo"
        name="codigo"
        class="form-control"
        placeholder="Ej: PROD-001"
        value="<?= htmlspecialchars($producto['codigo'] ?? '') ?>"
        required
        maxlength="20"
      >
      <small class="form-hint">Debe ser único en el sistema.</small>
    </div>
 
    <div class="form-group">
      <label class="form-label" for="nombre">Nombre del producto</label>
      <input
        type="text"
        id="nombre"
        name="nombre"
        class="form-control"
        placeholder="Ej: Cuaderno rayado 100 hojas"
        value="<?= htmlspecialchars($producto['nombre'] ?? '') ?>"
        required
        maxlength="100"
      >
    </div>
  </div>
 
  <!-- Fila 2: categoría e impuesto (solo lectura) -->
  <div class="form-row">
    <div class="form-group">
      <label class="form-label" for="id_categoria">Categoría</label>
      <select id="id_categoria" name="id_categoria" class="form-control" required onchange="actualizarImpuesto(this)">
        <option value="">— Seleccionar categoría —</option>
        <?php foreach ($categorias as $cat): ?>
          <option
            value="<?= $cat['id_categoria'] ?>"
            data-impuesto="<?= \Producto::IMPUESTOS[$cat['nombre']] ?? 0 ?>"
            <?= isset($producto['id_categoria']) && $producto['id_categoria'] == $cat['id_categoria'] ? 'selected' : '' ?>
          >
            <?= htmlspecialchars($cat['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
 
    <div class="form-group">
      <label class="form-label" for="impuesto_preview">Impuesto aplicado</label>
      <input
        type="text"
        id="impuesto_preview"
        class="form-control"
        value="<?= isset($producto['impuesto']) ? $producto['impuesto'] . '%' : '—' ?>"
        readonly
        style="background: var(--bg-alt, #f3f4f6); cursor: not-allowed;"
      >
      <small class="form-hint">Se asigna automáticamente según la categoría.</small>
    </div>
  </div>
 
  <!-- Fila 3: precio y stock -->
  <div class="form-row">
    <div class="form-group">
      <label class="form-label" for="precio_unitario">Precio unitario ($)</label>
      <input
        type="number"
        id="precio_unitario"
        name="precio_unitario"
        class="form-control"
        placeholder="Ej: 2500.00"
        value="<?= $producto['precio_unitario'] ?? '' ?>"
        required
        min="0"
        step="0.01"
      >
    </div>
 
    <div class="form-group">
      <label class="form-label" for="cantidad">Stock inicial</label>
      <input
        type="number"
        id="cantidad"
        name="cantidad"
        class="form-control"
        placeholder="Ej: 50"
        value="<?= $producto['cantidad'] ?? '' ?>"
        required
        min="0"
      >
    </div>
  </div>
 
  <!-- Fila 4: peso y tipo de empaque -->
  <div class="form-row">
    <div class="form-group">
      <label class="form-label" for="peso">Peso (kg)</label>
      <input
        type="number"
        id="peso"
        name="peso"
        class="form-control"
        placeholder="Ej: 0.25"
        value="<?= $producto['peso'] ?? '' ?>"
        min="0"
        step="0.01"
      >
      <small class="form-hint">Opcional.</small>
    </div>
 
    <div class="form-group">
      <label class="form-label" for="tipo_empaque">Tipo de empaque</label>
      <select id="tipo_empaque" name="tipo_empaque" class="form-control">
        <option value="">— Sin especificar —</option>
        <?php foreach (['Carton', 'Plastico', 'Otro'] as $emp): ?>
          <option value="<?= $emp ?>"
            <?= isset($producto['tipo_empaque']) && $producto['tipo_empaque'] === $emp ? 'selected' : '' ?>>
            <?= $emp ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
 
  <!-- Botones -->
  <div style="display:flex; gap:0.8rem; margin-top:1.5rem;">
    <button type="submit" class="btn btn-success">
      <?= $editando ? ' Guardar cambios' : ' Crear producto' ?>
    </button>
    <a href="index.php?c=producto" class="btn btn-outline">Cancelar</a>
  </div>
 
</form>
 
<script>
// Mapa de impuestos por nombre de categoría (se construye desde PHP)
const impuestos = <?= json_encode(array_column(
    array_map(fn($cat) => [
        'id'       => $cat['id_categoria'],
        'nombre'   => $cat['nombre'],
        'impuesto' => \Producto::IMPUESTOS[$cat['nombre']] ?? 0,
    ], $categorias),
    'impuesto', 'id'
)) ?>;
 
function actualizarImpuesto(sel) {
    const id  = sel.value;
    const opt = sel.options[sel.selectedIndex];
    const imp = opt.dataset.impuesto ?? impuestos[id] ?? '—';
    document.getElementById('impuesto_preview').value = imp !== '—' ? imp + '%' : '—';
}
 
// Inicializar al cargar si ya hay categoría seleccionada (modo edición)
document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('id_categoria');
    if (sel && sel.value) actualizarImpuesto(sel);
});
</script>