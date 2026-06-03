<?php
// Vista/Categoria/categoria.php
// Lista todas las categorías de la tienda
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías — MiTienda</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:        #0f0f13;
            --surface:   #1a1a23;
            --border:    #2e2e3d;
            --accent:    #f5c842;
            --accent2:   #ff6b35;
            --text:      #e8e8f0;
            --muted:     #7a7a9a;
            --danger:    #ff4757;
            --success:   #2ed573;
            --radius:    12px;
        }
 
        * { margin: 0; padding: 0; box-sizing: border-box; }
 
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }
 
        /* ── TOPBAR ── */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar-brand {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--accent);
            letter-spacing: -0.5px;
        }
        .topbar-brand span { color: var(--text); }
 
        .topbar-nav { display: flex; gap: .5rem; }
        .topbar-nav a {
            color: var(--muted);
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            padding: .4rem .9rem;
            border-radius: 8px;
            transition: all .2s;
        }
        .topbar-nav a:hover,
        .topbar-nav a.active {
            background: rgba(245,200,66,.1);
            color: var(--accent);
        }
 
        /* ── MAIN ── */
        .main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }
 
        /* ── PAGE HEADER ── */
        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }
        .page-title span {
            display: block;
            font-size: .85rem;
            font-weight: 400;
            font-family: 'DM Sans', sans-serif;
            color: var(--muted);
            margin-top: .4rem;
            letter-spacing: .5px;
            text-transform: uppercase;
        }
 
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .65rem 1.4rem;
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
            font-size: .9rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all .2s;
        }
        .btn-primary {
            background: var(--accent);
            color: #0f0f13;
        }
        .btn-primary:hover { background: #f0bc20; transform: translateY(-1px); }
 
        .btn-sm { padding: .4rem .9rem; font-size: .8rem; }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-outline:hover {
            border-color: var(--accent);
            color: var(--accent);
        }
        .btn-danger {
            background: transparent;
            border: 1px solid var(--danger);
            color: var(--danger);
        }
        .btn-danger:hover { background: rgba(255,71,87,.15); }
 
        /* ── ALERTS ── */
        .alert {
            padding: .9rem 1.2rem;
            border-radius: var(--radius);
            font-size: .9rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: .7rem;
        }
        .alert-success { background: rgba(46,213,115,.1); border: 1px solid rgba(46,213,115,.3); color: var(--success); }
        .alert-error   { background: rgba(255,71,87,.1);  border: 1px solid rgba(255,71,87,.3);  color: var(--danger); }
 
        /* ── TABLA ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
 
        .table-wrapper { overflow-x: auto; }
 
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .9rem;
        }
        thead { background: rgba(245,200,66,.06); }
        thead th {
            padding: 1rem 1.2rem;
            text-align: left;
            font-family: 'Syne', sans-serif;
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            white-space: nowrap;
        }
        tbody tr {
            border-top: 1px solid var(--border);
            transition: background .15s;
        }
        tbody tr:hover { background: rgba(255,255,255,.03); }
        td {
            padding: 1rem 1.2rem;
            vertical-align: middle;
        }
 
        .badge {
            display: inline-flex;
            align-items: center;
            padding: .25rem .75rem;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 500;
        }
        .badge-papeleria   { background: rgba(94,158,255,.15);  color: #5e9eff; }
        .badge-drogueria   { background: rgba(255,107,53,.15);  color: #ff6b35; }
        .badge-supermercado{ background: rgba(46,213,115,.15);  color: #2ed573; }
        .badge-aseo        { background: rgba(245,200,66,.15);  color: #f5c842; }
 
        .impuesto-chip {
            font-size: .78rem;
            color: var(--muted);
        }
        .impuesto-chip.cero { color: #2ed573; font-weight: 500; }
 
        .actions { display: flex; gap: .5rem; align-items: center; }
 
        /* Stock warning */
        .stock-warn {
            display: inline-flex; align-items: center; gap: .3rem;
            font-size: .78rem; color: var(--danger);
        }
 
        /* Empty state */
        .empty {
            text-align: center;
            padding: 4rem 2rem;
        }
        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: .4;
        }
        .empty p {
            color: var(--muted);
            margin-bottom: 1.5rem;
        }
 
        /* ── STATS ROW ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: .3rem;
        }
        .stat-label {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--muted);
        }
        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--accent);
        }
 
        /* Modal confirm */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.7);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem;
            max-width: 420px;
            width: 90%;
            animation: popIn .2s ease;
        }
        @keyframes popIn {
            from { transform: scale(.9); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }
        .modal h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.2rem;
            margin-bottom: .8rem;
        }
        .modal p { color: var(--muted); font-size: .9rem; margin-bottom: 1.5rem; }
        .modal-actions { display: flex; gap: .8rem; justify-content: flex-end; }
 
        @media (max-width: 600px) {
            .topbar-nav { display: none; }
            .page-header { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
 
<!-- ── TOPBAR ── -->
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
 
    <!-- ── ALERTS ── -->
    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success">
            ✅
            <?php
            $msg = [
                'creado'    => 'Categoría creada correctamente.',
                'editado'   => 'Categoría actualizada correctamente.',
                'eliminado' => 'Categoría eliminada correctamente.',
            ];
            echo $msg[$_GET['ok']] ?? 'Operación exitosa.';
            ?>
        </div>
    <?php endif; ?>
 
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            ⚠️ <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>
 
    <!-- ── STATS ── -->
    <?php
    // Conteos rápidos desde el controlador (variables inyectadas)
    // $totalCategorias, $totalProductos, $productosBajoStock
    $totalCategorias   = $totalCategorias   ?? 4;
    $totalProductos    = $totalProductos    ?? 0;
    $productosBajoStock= $productosBajoStock?? 0;
    ?>
    <div class="stats-row">
        <div class="stat-card">
            <span class="stat-label">Categorías</span>
            <span class="stat-value"><?= $totalCategorias ?></span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Productos registrados</span>
            <span class="stat-value"><?= $totalProductos ?></span>
        </div>
        <div class="stat-card">
            <span class="stat-label">⚠ Bajo stock (&lt;5)</span>
            <span class="stat-value" style="color:<?= $productosBajoStock > 0 ? 'var(--danger)' : 'var(--success)' ?>">
                <?= $productosBajoStock ?>
            </span>
        </div>
    </div>
 
    <!-- ── PAGE HEADER ── -->
    <div class="page-header">
        <div class="page-title">
            Categorías
            <span>Gestión de categorías de productos</span>
        </div>
        <a href="create.php" class="btn btn-primary">
            ＋ Nueva categoría
        </a>
    </div>
 
    <!-- ── TABLA ── -->
    <div class="card">
        <?php
        // $categorias debe ser inyectada por el controlador
        // Estructura esperada: [['id','nombre','impuesto','total_productos','bajo_stock'], ...]
        $categorias = $categorias ?? [];
        ?>
 
        <?php if (empty($categorias)): ?>
            <div class="empty">
                <div class="empty-icon">🗂️</div>
                <p>No hay categorías registradas todavía.</p>
                <a href="create.php" class="btn btn-primary">Crear primera categoría</a>
            </div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Impuesto</th>
                        <th>Productos</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($categorias as $i => $cat): ?>
                    <?php
                    $slug = strtolower(str_replace(['é','ó','á','ú','í',' '],
                                                   ['e','o','a','u','i','-'],
                                                   $cat['nombre']));
                    $impMap = ['papeleria'=>'7%','drogueria'=>'3%','aseo'=>'5%','supermercado'=>'0%'];
                    $imp = $impMap[$slug] ?? ($cat['impuesto'].'%');
                    $bajoStock = ($cat['bajo_stock'] ?? 0) > 0;
                    ?>
                    <tr>
                        <td style="color:var(--muted)"><?= $i + 1 ?></td>
                        <td>
                            <span class="badge badge-<?= $slug ?>">
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="impuesto-chip <?= $imp === '0%' ? 'cero' : '' ?>">
                                <?= $imp === '0%' ? 'Sin impuesto' : $imp ?>
                            </span>
                        </td>
                        <td><?= (int)($cat['total_productos'] ?? 0) ?> productos</td>
                        <td>
                            <?php if ($bajoStock): ?>
                                <span class="stock-warn">⚠ <?= $cat['bajo_stock'] ?> bajo mínimo</span>
                            <?php else: ?>
                                <span style="color:var(--success); font-size:.8rem;">✓ OK</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="edit.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline">
                                    ✏ Editar
                                </a>
                                <button
                                    class="btn btn-sm btn-danger"
                                    onclick="confirmarEliminar(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['nombre']) ?>')">
                                    🗑
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
 
</main>
 
<!-- ── MODAL CONFIRMAR ELIMINAR ── -->
<div class="modal-overlay" id="modalEliminar">
    <div class="modal">
        <h3>Eliminar categoría</h3>
        <p id="modalMsg">¿Seguro que deseas eliminar esta categoría? Se eliminará junto con todos sus productos asociados.</p>
        <div class="modal-actions">
            <button class="btn btn-outline btn-sm" onclick="cerrarModal()">Cancelar</button>
            <a id="modalConfirm" href="#" class="btn btn-danger btn-sm">Sí, eliminar</a>
        </div>
    </div>
</div>
 
<script>
function confirmarEliminar(id, nombre) {
    document.getElementById('modalMsg').textContent =
        `¿Seguro que deseas eliminar la categoría "${nombre}"? Se eliminará junto con todos sus productos asociados.`;
    document.getElementById('modalConfirm').href =
        `../../Controlador/CategoriaControlador.php?accion=eliminar&id=${id}`;
    document.getElementById('modalEliminar').classList.add('open');
}
function cerrarModal() {
    document.getElementById('modalEliminar').classList.remove('open');
}
document.getElementById('modalEliminar').addEventListener('click', function(e){
    if (e.target === this) cerrarModal();
});
</script>
 
</body>
</html>