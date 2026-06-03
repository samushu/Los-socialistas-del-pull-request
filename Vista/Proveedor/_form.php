<?php
// Partial: formulario reutilizable para crear y editar proveedor
// Variables esperadas: $proveedor (array|null)
$editando = !empty($proveedor);
$accion   = $editando
    ? 'index.php?c=proveedor&a=actualizar'
    : 'index.php?c=proveedor&a=guardar';
?>
<form method="POST" action="<?= $accion ?>">
 
  <?php if ($editando): ?>
    <input type="hidden" name="id_proveedor" value="<?= $proveedor['id_proveedor'] ?>">
  <?php endif; ?>
 
  <div class="form-group">
    <label class="form-label" for="nombre">Nombre del proveedor</label>
    <input
      type="text"
      id="nombre"
      name="nombre"
      class="form-control"
      placeholder="Ej: Distribuidora Nacional S.A."
      value="<?= htmlspecialchars($proveedor['nombre'] ?? '') ?>"
      required
      maxlength="100"
    >
  </div>
 
  <div class="form-row">
    <div class="form-group">
      <label class="form-label" for="telefono">Telefono</label>
      <input
        type="text"
        id="telefono"
        name="telefono"
        class="form-control"
        placeholder="Ej: 6011234567"
        value="<?= htmlspecialchars($proveedor['telefono'] ?? '') ?>"
        maxlength="20"
      >
    </div>
 
    <div class="form-group">
      <label class="form-label" for="ciudad">Ciudad</label>
      <input
        type="text"
        id="ciudad"
        name="ciudad"
        class="form-control"
        placeholder="Ej: Bogota"
        value="<?= htmlspecialchars($proveedor['ciudad'] ?? '') ?>"
        maxlength="50"
      >
    </div>
  </div>
 
  <div style="display:flex; gap:0.8rem; margin-top:1.5rem;">
    <button type="submit" class="btn btn-success">
      <?= $editando ? 'Guardar cambios' : 'Registrar proveedor' ?>
    </button>
    <a href="index.php?c=proveedor" class="btn btn-outline">Cancelar</a>
  </div>
 
</form>
 