<?php
// Partial: filtro de rango de fechas reutilizable para reportes
// Variables esperadas (opcionales):
//   $fecha_desde (string) — fecha inicial pre-seleccionada
//   $fecha_hasta (string) — fecha final pre-seleccionada
//   $accion      (string) — URL destino del formulario (default: reporte financiero)
$accion      = $accion      ?? 'index.php?c=reporte&a=financiero';
$fecha_desde = $fecha_desde ?? date('Y-m-01');          // primer día del mes actual
$fecha_hasta = $fecha_hasta ?? date('Y-m-d');           // hoy
?>
 
<div class="card" style="margin-bottom:1.5rem;">
  <h2 class="card-section-title">Filtrar por rango de fechas</h2>
 
  <form method="GET" action="<?= htmlspecialchars($accion) ?>">
    <?php
    // Preservar parámetros c y a que estén en la URL destino
    parse_str(parse_url($accion, PHP_URL_QUERY) ?? '', $params);
    foreach ($params as $k => $v):
    ?>
      <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
    <?php endforeach; ?>
 
    <div class="form-row" style="align-items:flex-end; gap:1rem;">
      <div class="form-group" style="flex:1; min-width:150px;">
        <label class="form-label" for="fecha_desde">Desde</label>
        <input
          type="date"
          id="fecha_desde"
          name="fecha_desde"
          class="form-control"
          value="<?= htmlspecialchars($fecha_desde) ?>"
        >
      </div>
 
      <div class="form-group" style="flex:1; min-width:150px;">
        <label class="form-label" for="fecha_hasta">Hasta</label>
        <input
          type="date"
          id="fecha_hasta"
          name="fecha_hasta"
          class="form-control"
          value="<?= htmlspecialchars($fecha_hasta) ?>"
        >
      </div>
 
      <div style="padding-bottom:0.2rem; display:flex; gap:0.6rem;">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="<?= htmlspecialchars($accion) ?>" class="btn btn-outline">Limpiar</a>
      </div>
    </div>
  </form>
</div>