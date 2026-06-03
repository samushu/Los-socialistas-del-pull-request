<?php
// Vista/Cliente/_form.php
// Parcial reutilizable: campos del formulario de Cliente.
// Incluido desde create.php y edit.php
// Variables esperadas:
//   $cliente → array con valores actuales (o vacío en creación)
//   $errores → array campo=>mensaje
//   $modo    → 'crear' | 'editar'
 
$cliente = $cliente ?? [];
$errores = $errores ?? [];
$modo    = $modo    ?? 'crear';
 
$hasErr = fn(string $f) => isset($errores[$f]);
$errMsg = fn(string $f) => $errores[$f] ?? '';
$val    = fn(string $f, $d='') => htmlspecialchars($cliente[$f] ?? $d);
?>
 
<style>
/* ── FORM PARCIAL STYLES ─── */
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; }
@media(max-width:560px){ .form-row { grid-template-columns:1fr; } }
 
.form-group { display:flex; flex-direction:column; gap:.45rem; margin-bottom:1.4rem; }
.form-group.full { grid-column:1/-1; }
 
.form-label { font-size:.85rem; font-weight:500; color:var(--text); letter-spacing:.3px; }
.required { color:var(--danger); margin-left:2px; }
 
.form-control {
    background:var(--bg); border:1px solid var(--border); border-radius:var(--radius);
    color:var(--text); font-family:'DM Sans',sans-serif; font-size:.9rem;
    padding:.72rem 1rem; width:100%; transition:border-color .2s, box-shadow .2s;
}
.form-control::placeholder { color:var(--muted); }
.form-control:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(245,200,66,.12); }
.has-error .form-control { border-color:var(--danger); }
 
.form-error { font-size:.8rem; color:var(--danger); }
.form-error::before { content:'⚠ '; }
.form-hint { font-size:.78rem; color:var(--muted); font-style:italic; }
 
/* Input con icono */
.input-wrap { position:relative; }
.input-wrap .input-icon {
    position:absolute; left:.9rem; top:50%; transform:translateY(-50%);
    color:var(--muted); font-size:.9rem; pointer-events:none;
}
.input-wrap .form-control { padding-left:2.4rem; }
 
/* DIVIDER */
.form-divider {
    border:none; border-top:1px solid var(--border);
    margin:1.8rem 0 1.4rem;
}
.form-section-title {
    font-family:'Syne',sans-serif; font-size:.8rem; font-weight:600;
    text-transform:uppercase; letter-spacing:1px; color:var(--muted);
    margin-bottom:1.2rem;
}
</style>
 
<!-- ── SECCIÓN: IDENTIFICACIÓN ── -->
<p class="form-section-title">📋 Identificación</p>
 
<div class="form-row">
    <!-- Cédula -->
    <div class="form-group <?= $hasErr('cedula') ? 'has-error' : '' ?>">
        <label class="form-label" for="cedula">Cédula <span class="required">*</span></label>
        <div class="input-wrap">
            <span class="input-icon">🪪</span>
            <input
                type="text" id="cedula" name="cedula"
                class="form-control"
                value="<?= $val('cedula') ?>"
                placeholder="Ej: 1012345678"
                maxlength="15"
                <?= $modo==='editar' ? 'readonly' : '' ?>
                required
            >
        </div>
        <?php if ($modo==='editar'): ?>
            <span class="form-hint">La cédula no puede modificarse.</span>
        <?php endif; ?>
        <?php if ($hasErr('cedula')): ?><p class="form-error"><?= $errMsg('cedula') ?></p><?php endif; ?>
    </div>
 
    <!-- Teléfono -->
    <div class="form-group <?= $hasErr('telefono') ? 'has-error' : '' ?>">
        <label class="form-label" for="telefono">Teléfono <span class="required">*</span></label>
        <div class="input-wrap">
            <span class="input-icon">📞</span>
            <input
                type="tel" id="telefono" name="telefono"
                class="form-control"
                value="<?= $val('telefono') ?>"
                placeholder="Ej: 3001234567"
                maxlength="15"
                required
            >
        </div>
        <?php if ($hasErr('telefono')): ?><p class="form-error"><?= $errMsg('telefono') ?></p><?php endif; ?>
    </div>
</div>
 
<hr class="form-divider">
 
<!-- ── SECCIÓN: NOMBRE ── -->
<p class="form-section-title">👤 Datos personales</p>
 
<div class="form-row">
    <!-- Nombre -->
    <div class="form-group <?= $hasErr('nombre') ? 'has-error' : '' ?>">
        <label class="form-label" for="nombre">Nombre <span class="required">*</span></label>
        <input
            type="text" id="nombre" name="nombre"
            class="form-control"
            value="<?= $val('nombre') ?>"
            placeholder="Ej: María"
            maxlength="80"
            required
        >
        <?php if ($hasErr('nombre')): ?><p class="form-error"><?= $errMsg('nombre') ?></p><?php endif; ?>
    </div>
 
    <!-- Apellido -->
    <div class="form-group <?= $hasErr('apellido') ? 'has-error' : '' ?>">
        <label class="form-label" for="apellido">Apellido <span class="required">*</span></label>
        <input
            type="text" id="apellido" name="apellido"
            class="form-control"
            value="<?= $val('apellido') ?>"
            placeholder="Ej: González"
            maxlength="80"
            required
        >
        <?php if ($hasErr('apellido')): ?><p class="form-error"><?= $errMsg('apellido') ?></p><?php endif; ?>
    </div>
</div>
 
<!-- Correo -->
<div class="form-group <?= $hasErr('correo') ? 'has-error' : '' ?>">
    <label class="form-label" for="correo">Correo electrónico <span class="required">*</span></label>
    <div class="input-wrap">
        <span class="input-icon">✉️</span>
        <input
            type="email" id="correo" name="correo"
            class="form-control"
            value="<?= $val('correo') ?>"
            placeholder="Ej: maria@correo.com"
            maxlength="120"
            required
        >
    </div>
    <?php if ($hasErr('correo')): ?><p class="form-error"><?= $errMsg('correo') ?></p><?php endif; ?>
</div>
 
<!-- Preview de nombre completo (solo creación) -->
<?php if ($modo === 'crear'): ?>
<div id="previewNombre" style="display:none; margin-top:-.8rem; margin-bottom:1.4rem;">
    <div style="background:rgba(245,200,66,.07); border:1px solid rgba(245,200,66,.2); border-radius:var(--radius); padding:.8rem 1rem; font-size:.85rem; color:var(--muted);">
        👤 Se registrará como: <strong id="previewTexto" style="color:var(--accent)"></strong>
    </div>
</div>
<script>
(function(){
    const n = document.getElementById('nombre');
    const a = document.getElementById('apellido');
    const preview = document.getElementById('previewNombre');
    const texto   = document.getElementById('previewTexto');
    function actualizar(){
        const completo = (n.value.trim() + ' ' + a.value.trim()).trim();
        if(completo.length > 1){ texto.textContent = completo; preview.style.display='block'; }
        else { preview.style.display='none'; }
    }
    n.addEventListener('input', actualizar);
    a.addEventListener('input', actualizar);
})();
</script>
<?php endif; ?>