<?php
// Vista/Categoria/edit.php
// Formulario para EDITAR una categoría existente.
// Variables esperadas del controlador:
//   $categoria → array con los datos actuales de la categoría
//               ['id', 'nombre', 'descripcion', 'impuesto']
//   $errores   → array asociativo campo=>mensaje (vacío si no hay errores)
//   $titulo    → string (opcional)
 
$errores   = $errores   ?? [];
$categoria = $categoria ?? [];
$titulo    = $titulo    ?? 'Editar Categoría';
 
// Guardia: si no tenemos la categoría, redirigir
if (empty($categoria) || empty($categoria['id'])) {
    header('Location: categoria.php?error=' . urlencode('Categoría no encontrada.'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> — MiTienda</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:      #0f0f13;
            --surface: #1a1a23;
            --border:  #2e2e3d;
            --accent:  #f5c842;
            --text:    #e8e8f0;
            --muted:   #7a7a9a;
            --danger:  #ff4757;
            --success: #2ed573;
            --warning: #ff6b35;
            --radius:  12px;
        }
 
        * { margin: 0; padding: 0; box-sizing: border-box; }
 
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }
 
        /* TOPBAR */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
            height: 64px;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-brand {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem; font-weight: 800; color: var(--accent);
        }
        .topbar-brand span { color: var(--text); }
        .topbar-nav { display: flex; gap: .5rem; }
        .topbar-nav a {
            color: var(--muted); text-decoration: none;
            font-size: .85rem; font-weight: 500;
            padding: .4rem .9rem; border-radius: 8px; transition: all .2s;
        }
        .topbar-nav a:hover, .topbar-nav a.active {
            background: rgba(245,200,66,.1); color: var(--accent);
        }
 
        /* MAIN */
        .main { max-width: 640px; margin: 0 auto; padding: 2.5rem 1.5rem; }
 
        /* BREADCRUMB */
        .breadcrumb {
            display: flex; align-items: center; gap: .5rem;
            font-size: .82rem; color: var(--muted); margin-bottom: 2rem;
        }
        .breadcrumb a { color: var(--muted); text-decoration: none; }
        .breadcrumb a:hover { color: var(--accent); }
        .breadcrumb-sep { opacity: .4; }
        .breadcrumb-current { color: var(--text); }
 
        /* HEADER ROW */
        .header-row {
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 1rem;
            margin-bottom: 2rem; flex-wrap: wrap;
        }
        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.8rem; font-weight: 800; margin-bottom: .3rem;
        }
        .page-subtitle { color: var(--muted); font-size: .88rem; }
 
        /* ID BADGE */
        .id-badge {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: .5rem 1rem;
            font-size: .8rem; color: var(--muted);
            white-space: nowrap; align-self: flex-start;
        }
        .id-badge strong { color: var(--text); font-family: 'Syne', sans-serif; }
 
        /* CARD */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem;
        }
 
        /* ALERT */
        .alert-error {
            background: rgba(255,71,87,.1); border: 1px solid rgba(255,71,87,.3);
            color: #ff4757; border-radius: var(--radius);
            padding: .9rem 1.2rem; margin-bottom: 1.5rem; font-size: .9rem;
        }
        .alert-error ul { margin: .4rem 0 0 1.2rem; }
        .alert-error li { margin-bottom: .2rem; }
 
        /* AVISO DE EDICIÓN */
        .edit-notice {
            display: flex; align-items: flex-start; gap: .8rem;
            background: rgba(255,107,53,.08);
            border: 1px solid rgba(255,107,53,.25);
            border-radius: var(--radius);
            padding: 1rem 1.2rem;
            margin-bottom: 1.8rem;
            font-size: .85rem;
        }
        .edit-notice p { color: var(--muted); margin: 0; }
        .edit-notice strong { color: var(--warning); display: block; margin-bottom: .2rem; }
 
        /* FORM FOOTER */
        .form-footer {
            display: flex; justify-content: space-between; align-items: center;
            gap: .8rem; margin-top: 2rem; padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
        }
        .form-footer-right { display: flex; gap: .8rem; }
 
        /* BUTTONS */
        .btn {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .7rem 1.5rem; border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif; font-size: .9rem;
            font-weight: 500; text-decoration: none; cursor: pointer;
            border: none; transition: all .2s;
        }
        .btn-primary { background: var(--accent); color: #0f0f13; }
        .btn-primary:hover { background: #f0bc20; transform: translateY(-1px); }
        .btn-outline {
            background: transparent; border: 1px solid var(--border); color: var(--text);
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-danger {
            background: transparent; border: 1px solid var(--danger); color: var(--danger);
        }
        .btn-danger:hover { background: rgba(255,71,87,.12); }
        .btn-sm { padding: .45rem .9rem; font-size: .82rem; }
 
        /* MODAL */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.75); z-index: 999;
            align-items: center; justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 2rem;
            max-width: 400px; width: 90%;
            animation: popIn .2s ease;
        }
        @keyframes popIn {
            from { transform: scale(.9); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }
        .modal h3 {
            font-family: 'Syne', sans-serif; font-size: 1.1rem; margin-bottom: .7rem;
        }
        .modal p { color: var(--muted); font-size: .88rem; margin-bottom: 1.4rem; }
        .modal-actions { display: flex; gap: .8rem; justify-content: flex-end; }
 
        @media (max-width: 600px) {
            .topbar-nav { display: none; }
            .form-footer { flex-direction: column; }
            .form-footer-right { flex-direction: column-reverse; width: 100%; }
            .btn { width: 100%; justify-content: center; }
            .header-row { flex-direction: column; }
        }
    </style>
</head>
<body>
 
<!-- TOPBAR -->
<header class="topbar">
    <div class="topbar-brand">Mi<span>Tienda</span></div>
    <nav class="topbar-nav">
        <a href="../Dashboard/index.php">Inicio</a>
        <a href="categoria.php" class="active">Categorías</a>
        <a href="../Producto/producto.php">Productos</a>
        <a href="../Cliente/cliente.php">Clientes</a>
        <a href="../Proveedor/proveedor.php">Proveedores</a>
        <a href="../Compras/compras.php">Compras</a>
        <a href="../Reporte/reporte.php">Reportes</a>
    </nav>
</header>
 
<main class="main">
 
    <!-- BREADCRUMB -->
    <nav class="breadcrumb" aria-label="breadcrumb">
        <a href="../Dashboard/index.php">Inicio</a>
        <span class="breadcrumb-sep">›</span>
        <a href="categoria.php">Categorías</a>
        <span class="breadcrumb-sep">›</span>
        <span class="breadcrumb-current">
            Editar: <?= htmlspecialchars($categoria['nombre'] ?? '') ?>
        </span>
    </nav>
 
    <!-- HEADER -->
    <div class="header-row">
        <div>
            <h1 class="page-title"><?= htmlspecialchars($titulo) ?></h1>
            <p class="page-subtitle">Modifica la descripción de la categoría.</p>
        </div>
        <div class="id-badge">
            ID <strong>#<?= htmlspecialchars($categoria['id']) ?></strong>
        </div>
    </div>
 
    <!-- AVISO -->
    <div class="edit-notice">
        <span style="font-size:1.2rem; margin-top:.1rem;">⚠️</span>
        <div>
            <strong>Nombre bloqueado</strong>
            <p>
                El nombre de la categoría no puede modificarse porque ya tiene productos asociados.
                Solo puedes actualizar la descripción.
            </p>
        </div>
    </div>
 
    <!-- ERRORES GLOBALES -->
    <?php if (!empty($errores)): ?>
        <div class="alert-error">
            ⚠️ Por favor corrige los siguientes errores:
            <ul>
                <?php foreach ($errores as $campo => $msg): ?>
                    <li><?= htmlspecialchars($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
 
    <!-- FORMULARIO -->
    <div class="card">
        <form
            action="../../Controlador/CategoriaControlador.php"
            method="POST"
            novalidate
        >
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id"     value="<?= htmlspecialchars($categoria['id']) ?>">
 
            <?php
            $modo = 'editar';
            include '_form.php';
            ?>
 
            <div class="form-footer">
                <!-- Eliminar (izquierda) -->
                <button
                    type="button"
                    class="btn btn-danger btn-sm"
                    onclick="abrirModalEliminar()">
                    🗑 Eliminar categoría
                </button>
 
                <!-- Cancelar / Guardar (derecha) -->
                <div class="form-footer-right">
                    <a href="categoria.php" class="btn btn-outline">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        💾 Guardar cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
 
</main>
 
<!-- MODAL CONFIRMAR ELIMINAR -->
<div class="modal-overlay" id="modalEliminar">
    <div class="modal">
        <h3>Eliminar categoría</h3>
        <p>
            ¿Seguro que deseas eliminar
            <strong style="color:var(--text)">
                <?= htmlspecialchars($categoria['nombre'] ?? '') ?>
            </strong>?
            Esta acción es irreversible y eliminará todos los productos asociados.
        </p>
        <div class="modal-actions">
            <button class="btn btn-outline btn-sm" onclick="cerrarModal()">Cancelar</button>
            <a
                href="../../Controlador/CategoriaControlador.php?accion=eliminar&id=<?= htmlspecialchars($categoria['id']) ?>"
                class="btn btn-danger btn-sm">
                Sí, eliminar
            </a>
        </div>
    </div>
</div>
 
<script>
function abrirModalEliminar() {
    document.getElementById('modalEliminar').classList.add('open');
}
function cerrarModal() {
    document.getElementById('modalEliminar').classList.remove('open');
}
document.getElementById('modalEliminar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
 
</body>
</html>
 