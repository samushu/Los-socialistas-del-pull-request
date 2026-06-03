<?php
// Partial: formulario reutilizable para crear y editar categoría
// Variables esperadas: $categoria (array, opcional para edición)
$editando  = !empty($categoria);
$accion    = $editando ? 'index.php?c=categoria&a=actualizar' : 'index.php?c=categoria&a=guardar';
$titulo    = $editando ? 'Editar categoría' : 'Nueva categoría';
?>
<form method="POST" action="<?= $accion ?>">
  <?php if ($editando): ?>
    <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?>">
  <?php endif; ?>

  <div class="form-group">
    <label class="form-label" for="nombre">Nombre de la categoría</label>
    <input
      type="text"
      id="nombre"
      name="nombre"
      class="form-control"
      placeholder="Ej: Papeleria, Drogueria..."
      value="<?= htmlspecialchars($categoria['nombre'] ?? '') ?>"
      required
      maxlength="50"
    >
  </div>

  <div style="display:flex; gap:0.8rem; margin-top:1.5rem;">
    <button type="submit" class="btn btn-success">
      <?= $editando ? '💾 Guardar cambios' : '✅ Crear categoría' ?>
    </button>
    <a href="index.php?c=categoria" class="btn btn-outline">Cancelar</a>
  </div>
</form>