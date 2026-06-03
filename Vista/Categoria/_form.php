<?php
// Vista/Categoria/_form.php
// Parcial reutilizable: campos del formulario de Categoría.
// Se incluye desde create.php y edit.php.
// Variables esperadas del contexto:
//   $categoria  → array con los valores actuales (o array vacío en creación)
//   $errores    → array asociativo campo=>mensaje (o array vacío)
//   $modo       → 'crear' | 'editar'
 
$categoria = $categoria ?? [];
$errores   = $errores   ?? [];
$modo      = $modo      ?? 'crear';
 
// Categorías fijas del negocio
$categoriasDisponibles = [
    'Papelería'    => ['impuesto' => 7,  'icono' => '✏️'],
    'Droguería'    => ['impuesto' => 3,  'icono' => '💊'],
    'Supermercado' => ['impuesto' => 0,  'icono' => '🛒'],
    'Aseo'         => ['impuesto' => 5,  'icono' => '🧹'],
];
 
// Helper para marcar error
$hasErr = fn(string $campo) => isset($errores[$campo]);
$errMsg = fn(string $campo) => $errores[$campo] ?? '';
$val    = fn(string $campo, $default = '') =>
              htmlspecialchars($categoria[$campo] ?? $default);
?>
 
<!-- ── CAMPO: Nombre (selector de las 4 categorías) ── -->
<div class="form-group <?= $hasErr('nombre') ? 'has-error' : '' ?>">
    <label class="form-label" for="nombre">
        Nombre de la categoría
        <span class="required">*</span>
    </label>
 
    <div class="cat-selector">
        <?php foreach ($categoriasDisponibles as $nombre => $info): ?>
        <?php
        $slug     = strtolower(str_replace(['é','ó','á','ú','í'], ['e','o','a','u','i'], $nombre));
        $selected = (($categoria['nombre'] ?? '') === $nombre);
        ?>
        <label class="cat-option <?= $selected ? 'selected' : '' ?>" for="cat_<?= $slug ?>">
            <input
                type="radio"
                id="cat_<?= $slug ?>"
                name="nombre"
                value="<?= htmlspecialchars($nombre) ?>"
                <?= $selected ? 'checked' : '' ?>
                <?= ($modo === 'editar') ? 'disabled' : '' ?>
                required
            >
            <span class="cat-icon"><?= $info['icono'] ?></span>
            <span class="cat-name"><?= $nombre ?></span>
            <span class="cat-tax">
                <?= $info['impuesto'] > 0 ? "IVA {$info['impuesto']}%" : 'Sin IVA' ?>
            </span>
        </label>
        <?php endforeach; ?>
    </div>
 
    <?php if ($modo === 'editar'): ?>
        <!-- Mantener el valor en modo editar aunque el input esté disabled -->
        <input type="hidden" name="nombre" value="<?= $val('nombre') ?>">
        <p class="form-hint">El nombre de la categoría no puede cambiarse una vez creada.</p>
    <?php endif; ?>
 
    <?php if ($hasErr('nombre')): ?>
        <p class="form-error"><?= $errMsg('nombre') ?></p>
    <?php endif; ?>
</div>
 
<!-- ── CAMPO: Descripción (opcional) ── -->
<div class="form-group <?= $hasErr('descripcion') ? 'has-error' : '' ?>">
    <label class="form-label" for="descripcion">Descripción</label>
    <textarea
        id="descripcion"
        name="descripcion"
        class="form-control"
        rows="3"
        placeholder="Descripción breve de la categoría (opcional)…"><?= $val('descripcion') ?></textarea>
    <?php if ($hasErr('descripcion')): ?>
        <p class="form-error"><?= $errMsg('descripcion') ?></p>
    <?php endif; ?>
</div>
 
<!-- ── INFO: Impuesto automático ── -->
<div class="info-box" id="infoImpuesto" style="display:none;">
    <span class="info-icon">ℹ️</span>
    <div>
        <strong>Impuesto asignado automáticamente</strong>
        <p id="infoImpuestoTexto"></p>
    </div>
</div>
 
<!-- Campo oculto: impuesto se calcula en el controlador según nombre,
     pero lo mostramos al usuario de forma informativa via JS -->
<input type="hidden" name="impuesto" id="campoImpuesto"
       value="<?= $val('impuesto', '0') ?>">
 
<style>
/* ── FORM PARCIAL STYLES ─────────────────────────────────── */
.form-group {
    display: flex;
    flex-direction: column;
    gap: .5rem;
    margin-bottom: 1.5rem;
}
.form-label {
    font-size: .85rem;
    font-weight: 500;
    color: var(--text);
    letter-spacing: .3px;
}
.required { color: var(--danger); margin-left: 2px; }
 
.form-control {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
    font-size: .9rem;
    padding: .75rem 1rem;
    width: 100%;
    transition: border-color .2s, box-shadow .2s;
    resize: vertical;
}
.form-control::placeholder { color: var(--muted); }
.form-control:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(245,200,66,.15);
}
.has-error .form-control { border-color: var(--danger); }
 
.form-error {
    font-size: .8rem;
    color: var(--danger);
    display: flex;
    align-items: center;
    gap: .3rem;
}
.form-error::before { content: '⚠ '; }
 
.form-hint {
    font-size: .78rem;
    color: var(--muted);
    font-style: italic;
}
 
/* ── CAT SELECTOR ── */
.cat-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: .8rem;
}
.cat-option {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .4rem;
    padding: 1.2rem 1rem;
    border: 2px solid var(--border);
    border-radius: var(--radius);
    cursor: pointer;
    text-align: center;
    transition: all .2s;
    user-select: none;
    background: var(--bg);
}
.cat-option:hover {
    border-color: var(--accent);
    background: rgba(245,200,66,.05);
}
.cat-option.selected,
.cat-option input:checked ~ * {
    border-color: var(--accent);
    background: rgba(245,200,66,.08);
}
.cat-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0; height: 0;
}
.cat-icon { font-size: 2rem; line-height: 1; }
.cat-name {
    font-family: 'Syne', sans-serif;
    font-size: .9rem;
    font-weight: 600;
    color: var(--text);
}
.cat-tax {
    font-size: .75rem;
    color: var(--muted);
}
.cat-option.selected .cat-name { color: var(--accent); }
.cat-option.selected .cat-tax  { color: var(--accent); opacity: .7; }
 
/* Disabled en modo editar */
.cat-option input[disabled] + .cat-icon {
    /* el padre ya tiene el estado selected */
}
.cat-option:has(input[disabled]) {
    cursor: default;
    opacity: .7;
}
.cat-option:has(input[disabled]):not(.selected):hover {
    border-color: var(--border);
    background: var(--bg);
}
 
/* ── INFO BOX ── */
.info-box {
    display: flex;
    align-items: flex-start;
    gap: .8rem;
    background: rgba(94,158,255,.08);
    border: 1px solid rgba(94,158,255,.25);
    border-radius: var(--radius);
    padding: 1rem 1.2rem;
    margin-bottom: 1.5rem;
}
.info-icon { font-size: 1.2rem; margin-top: .1rem; }
.info-box strong { font-size: .9rem; display: block; margin-bottom: .2rem; }
.info-box p { font-size: .83rem; color: var(--muted); margin: 0; }
</style>
 
<script>
(function () {
    const impuestos = {
        'Papelería':    { valor: 7,  texto: 'Papelería aplica un impuesto del 7% sobre el precio unitario de cada producto.' },
        'Droguería':    { valor: 3,  texto: 'Droguería aplica un impuesto del 3% sobre el precio unitario de cada producto.' },
        'Supermercado': { valor: 0,  texto: 'Supermercado no aplica impuesto. Los productos se venden a precio unitario neto.' },
        'Aseo':         { valor: 5,  texto: 'Aseo aplica un impuesto del 5% sobre el precio unitario de cada producto.' },
    };
 
    const infoBox    = document.getElementById('infoImpuesto');
    const infoTexto  = document.getElementById('infoImpuestoTexto');
    const campoImp   = document.getElementById('campoImpuesto');
    const radios     = document.querySelectorAll('input[name="nombre"]');
    const opciones   = document.querySelectorAll('.cat-option');
 
    function actualizarSeleccion(valor) {
        opciones.forEach(op => {
            const r = op.querySelector('input[type="radio"]');
            op.classList.toggle('selected', r && r.value === valor);
        });
        if (impuestos[valor]) {
            infoTexto.textContent = impuestos[valor].texto;
            campoImp.value = impuestos[valor].valor;
            infoBox.style.display = 'flex';
        }
    }
 
    radios.forEach(r => {
        r.addEventListener('change', () => actualizarSeleccion(r.value));
    });
 
    // Opciones clicables (también hacen clic en el radio oculto)
    opciones.forEach(op => {
        op.addEventListener('click', () => {
            const r = op.querySelector('input[type="radio"]');
            if (r && !r.disabled) {
                r.checked = true;
                r.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
 
    // Estado inicial
    const checked = document.querySelector('input[name="nombre"]:checked');
    if (checked) actualizarSeleccion(checked.value);
})();
</script>