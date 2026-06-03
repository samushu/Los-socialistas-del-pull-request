<?php
// Partial: formulario reutilizable para crear y editar cliente
// Variables esperadas: $cliente (array, opcional para edición)
$editando = !empty($cliente);
$accion   = $editando ? 'index.php?c=cliente&a=actualizar' : 'index.php?c=cliente&a=guardar';
$titulo   = $editando ? 'Editar cliente' : 'Nuevo cliente';
?>
<form method="POST" action="<?= $accion ?>">
  <?php if ($editando): ?>
    <input type="hidden" name="cedula" value="<?= htmlspecialchars($cliente['cedula']) ?>">
  <?php endif; ?>
 
  <div class="form-row">
    <div class="form-group">
      <label class="form-label" for="cedula">Cédula</label>
      <input
        type="text"
        id="cedula"
        name="cedula"
        class="form-control"
        placeholder="Ej: 1234567890"
        value="<?= htmlspecialchars($cliente['cedula'] ?? '') ?>"
        <?= $editando ? 'readonly' : 'required' ?>
        maxlength="20"
      >
      <?php if ($editando): ?>
        <small class="form-hint">La cédula no se puede modificar.</small>
      <?php endif; ?>
    </div>
 
    <div class="form-group">
      <label class="form-label" for="nombre">Nombre</label>
      <input
        type="text"
        id="nombre"
        name="nombre"
        class="form-control"
        placeholder="Ej: Juan"
        value="<?= htmlspecialchars($cliente['nombre'] ?? '') ?>"
        required
        maxlength="50"
      >
    </div>
  </div>
 
  <div class="form-row">
    <div class="form-group">
      <label class="form-label" for="apellido">Apellido</label>
      <input
        type="text"
        id="apellido"
        name="apellido"
        class="form-control"
        placeholder="Ej: Pérez"
        value="<?= htmlspecialchars($cliente['apellido'] ?? '') ?>"
        required
        maxlength="50"
      >
    </div>
 
    <div class="form-group">
      <label class="form-label" for="telefono">Teléfono</label>
      <input
        type="text"
        id="telefono"
        name="telefono"
        class="form-control"
        placeholder="Ej: 3101234567"
        value="<?= htmlspecialchars($cliente['telefono'] ?? '') ?>"
        maxlength="20"
      >
    </div>
  </div>
 
  <div class="form-group">
    <label class="form-label" for="correo">Correo electrónico</label>
    <input
      type="email"
      id="correo"
      name="correo"
      class="form-control"
      placeholder="Ej: juan@correo.com"
      value="<?= htmlspecialchars($cliente['correo'] ?? '') ?>"
      maxlength="100"
    >
  </div>
 
  <div style="display:flex; gap:0.8rem; margin-top:1.5rem;">
    <button type="submit" class="btn btn-success">
      <?= $editando ? '💾 Guardar cambios' : '✅ Registrar cliente' ?>
    </button>
    <a href="index.php?c=cliente" class="btn btn-outline">Cancelar</a>
  </div>
</form>