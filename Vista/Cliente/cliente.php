<?php
// Vista/Cliente/cliente.php
// Lista todos los clientes registrados.
// Variables esperadas del controlador:
//   $clientes          → array de clientes ['id','cedula','nombre','apellido','telefono','correo','total_compras','valor_total']
//   $totalClientes     → int
//   $clienteFrecuente  → array|null  (cliente con más compras)
//   $clienteUnaVez     → array|null  (clientes con exactamente 1 compra)
 
$clientes         = $clientes         ?? [];
$totalClientes    = $totalClientes    ?? 0;
$clienteFrecuente = $clienteFrecuente ?? null;
$clienteUnaVez    = $clienteUnaVez    ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes — MiTienda</title>
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
            --info:    #5e9eff;
            --orange:  #ff6b35;
            --radius:  12px;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:var(--bg); color:var(--text); font-family:'DM Sans',sans-serif; min-height:100vh; }
 
        /* TOPBAR */
        .topbar {
            background:var(--surface); border-bottom:1px solid var(--border);
            padding:0 2rem; display:flex; align-items:center; justify-content:space-between;
            height:64px; position:sticky; top:0; z-index:100;
        }
        .topbar-brand { font-family:'Syne',sans-serif; font-size:1.3rem; font-weight:800; color:var(--accent); }
        .topbar-brand span { color:var(--text); }
        .topbar-nav { display:flex; gap:.5rem; }
        .topbar-nav a {
            color:var(--muted); text-decoration:none; font-size:.85rem; font-weight:500;
            padding:.4rem .9rem; border-radius:8px; transition:all .2s;
        }
        .topbar-nav a:hover, .topbar-nav a.active { background:rgba(245,200,66,.1); color:var(--accent); }
 
        .main { max-width:1150px; margin:0 auto; padding:2.5rem 1.5rem; }
 
        /* STATS */
        .stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:2rem; }
        .stat-card {
            background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
            padding:1.2rem 1.5rem; display:flex; flex-direction:column; gap:.3rem;
        }
        .stat-label { font-size:.75rem; text-transform:uppercase; letter-spacing:.8px; color:var(--muted); }
        .stat-value { font-family:'Syne',sans-serif; font-size:1.8rem; font-weight:800; color:var(--accent); }
 
        /* HIGHLIGHTS */
        .highlights { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1rem; margin-bottom:2rem; }
        .highlight-card {
            background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
            padding:1.2rem 1.5rem; display:flex; align-items:flex-start; gap:1rem;
        }
        .hl-icon { font-size:2rem; line-height:1; }
        .hl-label { font-size:.72rem; text-transform:uppercase; letter-spacing:.8px; color:var(--muted); margin-bottom:.2rem; }
        .hl-name { font-family:'Syne',sans-serif; font-size:1rem; font-weight:700; }
        .hl-sub { font-size:.82rem; color:var(--muted); margin-top:.2rem; }
        .hl-frecuente .hl-name { color:var(--accent); }
        .hl-unavez .hl-name { color:var(--info); }
 
        /* PAGE HEADER */
        .page-header { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
        .page-title { font-family:'Syne',sans-serif; font-size:2rem; font-weight:800; line-height:1; }
        .page-title span { display:block; font-size:.85rem; font-family:'DM Sans',sans-serif; font-weight:400; color:var(--muted); margin-top:.4rem; text-transform:uppercase; letter-spacing:.5px; }
 
        /* SEARCH BAR */
        .toolbar { display:flex; gap:.8rem; margin-bottom:1.2rem; flex-wrap:wrap; align-items:center; }
        .search-wrap { position:relative; flex:1; min-width:200px; }
        .search-wrap input {
            width:100%; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
            color:var(--text); font-family:'DM Sans',sans-serif; font-size:.9rem;
            padding:.65rem 1rem .65rem 2.5rem; transition:border-color .2s;
        }
        .search-wrap input::placeholder { color:var(--muted); }
        .search-wrap input:focus { outline:none; border-color:var(--accent); }
        .search-icon { position:absolute; left:.8rem; top:50%; transform:translateY(-50%); color:var(--muted); font-size:.9rem; }
 
        /* BUTTONS */
        .btn {
            display:inline-flex; align-items:center; gap:.5rem;
            padding:.65rem 1.4rem; border-radius:var(--radius);
            font-family:'DM Sans',sans-serif; font-size:.9rem; font-weight:500;
            text-decoration:none; cursor:pointer; border:none; transition:all .2s;
        }
        .btn-primary { background:var(--accent); color:#0f0f13; }
        .btn-primary:hover { background:#f0bc20; transform:translateY(-1px); }
        .btn-outline { background:transparent; border:1px solid var(--border); color:var(--text); }
        .btn-outline:hover { border-color:var(--accent); color:var(--accent); }
        .btn-info { background:transparent; border:1px solid var(--info); color:var(--info); }
        .btn-info:hover { background:rgba(94,158,255,.12); }
        .btn-danger { background:transparent; border:1px solid var(--danger); color:var(--danger); }
        .btn-danger:hover { background:rgba(255,71,87,.12); }
        .btn-sm { padding:.4rem .9rem; font-size:.8rem; }
 
        /* ALERTS */
        .alert { padding:.9rem 1.2rem; border-radius:var(--radius); font-size:.9rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:.7rem; }
        .alert-success { background:rgba(46,213,115,.1); border:1px solid rgba(46,213,115,.3); color:var(--success); }
        .alert-error   { background:rgba(255,71,87,.1);  border:1px solid rgba(255,71,87,.3);  color:var(--danger); }
 
        /* TABLE CARD */
        .card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; }
        .table-wrapper { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:.88rem; }
        thead { background:rgba(245,200,66,.06); }
        thead th {
            padding:1rem 1.2rem; text-align:left;
            font-family:'Syne',sans-serif; font-size:.72rem; font-weight:600;
            text-transform:uppercase; letter-spacing:1px; color:var(--muted); white-space:nowrap;
        }
        tbody tr { border-top:1px solid var(--border); transition:background .15s; }
        tbody tr:hover { background:rgba(255,255,255,.03); }
        td { padding:.9rem 1.2rem; vertical-align:middle; }
 
        /* AVATAR */
        .avatar {
            width:36px; height:36px; border-radius:50%;
            background:linear-gradient(135deg, var(--accent), var(--orange));
            display:inline-flex; align-items:center; justify-content:center;
            font-family:'Syne',sans-serif; font-weight:800; font-size:.85rem; color:#0f0f13;
            flex-shrink:0;
        }
        .client-cell { display:flex; align-items:center; gap:.8rem; }
        .client-name { font-weight:500; }
        .client-cedula { font-size:.78rem; color:var(--muted); }
 
        /* COMPRAS BADGE */
        .compras-badge {
            display:inline-flex; align-items:center; gap:.3rem;
            padding:.2rem .7rem; border-radius:20px; font-size:.78rem; font-weight:500;
        }
        .compras-badge.alta  { background:rgba(245,200,66,.15); color:var(--accent); }
        .compras-badge.media { background:rgba(94,158,255,.15);  color:var(--info); }
        .compras-badge.baja  { background:rgba(122,122,154,.15); color:var(--muted); }
        .compras-badge.una   { background:rgba(255,107,53,.15);  color:var(--orange); }
 
        .valor-total { font-family:'Syne',sans-serif; font-weight:600; font-size:.9rem; }
 
        .actions { display:flex; gap:.5rem; align-items:center; }
 
        /* EMPTY */
        .empty { text-align:center; padding:4rem 2rem; }
        .empty-icon { font-size:3rem; margin-bottom:1rem; opacity:.4; }
        .empty p { color:var(--muted); margin-bottom:1.5rem; }
 
        /* MODAL */
        .modal-overlay {
            display:none; position:fixed; inset:0; background:rgba(0,0,0,.75);
            z-index:999; align-items:center; justify-content:center;
        }
        .modal-overlay.open { display:flex; }
        .modal {
            background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
            padding:2rem; max-width:420px; width:90%; animation:popIn .2s ease;
        }
        @keyframes popIn { from{transform:scale(.9);opacity:0} to{transform:scale(1);opacity:1} }
        .modal h3 { font-family:'Syne',sans-serif; font-size:1.2rem; margin-bottom:.8rem; }
        .modal p { color:var(--muted); font-size:.9rem; margin-bottom:1.5rem; }
        .modal-actions { display:flex; gap:.8rem; justify-content:flex-end; }
 
        @media(max-width:700px){ .topbar-nav{display:none} .page-header{flex-direction:column;align-items:flex-start} }
    </style>
</head>
<body>
 
<header class="topbar">
    <div class="topbar-brand">Mi<span>Tienda</span></div>
    <nav class="topbar-nav">
        <a href="../Dashboard/index.php">Inicio</a>
        <a href="../Categoria/categoria.php">Categorías</a>
        <a href="../Producto/producto.php">Productos</a>
        <a href="cliente.php" class="active">Clientes</a>
        <a href="../Proveedor/proveedor.php">Proveedores</a>
        <a href="../Compras/compras.php">Compras</a>
        <a href="../Reporte/reporte.php">Reportes</a>
    </nav>
</header>
 
<main class="main">
 
    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success">✅
            <?= ['creado'=>'Cliente registrado correctamente.','editado'=>'Cliente actualizado.','eliminado'=>'Cliente eliminado.'][$_GET['ok']] ?? 'Operación exitosa.' ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">⚠️ <?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
 
    <!-- STATS -->
    <div class="stats-row">
        <div class="stat-card">
            <span class="stat-label">Clientes registrados</span>
            <span class="stat-value"><?= $totalClientes ?></span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Con más de 1 compra</span>
            <span class="stat-value">
                <?= count(array_filter($clientes, fn($c) => ($c['total_compras'] ?? 0) > 1)) ?>
            </span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Solo una compra</span>
            <span class="stat-value" style="color:var(--orange)">
                <?= count($clienteUnaVez) ?>
            </span>
        </div>
    </div>
 
    <!-- HIGHLIGHTS -->
    <?php if ($clienteFrecuente || !empty($clienteUnaVez)): ?>
    <div class="highlights">
        <?php if ($clienteFrecuente): ?>
        <div class="highlight-card hl-frecuente">
            <div class="hl-icon">🏆</div>
            <div>
                <div class="hl-label">Cliente más frecuente</div>
                <div class="hl-name"><?= htmlspecialchars($clienteFrecuente['nombre'].' '.$clienteFrecuente['apellido']) ?></div>
                <div class="hl-sub">
                    <?= $clienteFrecuente['total_compras'] ?> compras ·
                    $<?= number_format($clienteFrecuente['valor_total'], 2) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
 
        <?php if (!empty($clienteUnaVez)): ?>
        <div class="highlight-card hl-unavez">
            <div class="hl-icon">👤</div>
            <div>
                <div class="hl-label">Solo compraron una vez</div>
                <?php foreach (array_slice($clienteUnaVez, 0, 3) as $c): ?>
                    <div class="hl-name" style="font-size:.9rem; margin-bottom:.15rem;">
                        <?= htmlspecialchars($c['nombre'].' '.$c['apellido']) ?>
                    </div>
                <?php endforeach; ?>
                <?php if (count($clienteUnaVez) > 3): ?>
                    <div class="hl-sub">y <?= count($clienteUnaVez) - 3 ?> más…</div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
 
    <!-- HEADER + ACCIONES -->
    <div class="page-header">
        <div class="page-title">
            Clientes
            <span>Registro y seguimiento de compradores</span>
        </div>
        <a href="create.php" class="btn btn-primary">＋ Nuevo cliente</a>
    </div>
 
    <!-- SEARCH -->
    <div class="toolbar">
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" id="buscar" placeholder="Buscar por nombre, cédula o correo…">
        </div>
    </div>
 
    <!-- TABLA -->
    <div class="card">
    <?php if (empty($clientes)): ?>
        <div class="empty">
            <div class="empty-icon">👥</div>
            <p>No hay clientes registrados todavía.</p>
            <a href="create.php" class="btn btn-primary">Registrar primer cliente</a>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table id="tablaClientes">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Compras</th>
                        <th>Total gastado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($clientes as $i => $c): ?>
                    <?php
                    $iniciales = strtoupper(mb_substr($c['nombre'],0,1).mb_substr($c['apellido'],0,1));
                    $compras   = (int)($c['total_compras'] ?? 0);
                    $badgeClass = $compras >= 5 ? 'alta' : ($compras > 1 ? 'media' : ($compras === 1 ? 'una' : 'baja'));
                    ?>
                    <tr>
                        <td style="color:var(--muted)"><?= $i+1 ?></td>
                        <td>
                            <div class="client-cell">
                                <div class="avatar"><?= $iniciales ?></div>
                                <div>
                                    <div class="client-name">
                                        <?= htmlspecialchars($c['nombre'].' '.$c['apellido']) ?>
                                    </div>
                                    <div class="client-cedula">CC <?= htmlspecialchars($c['cedula']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($c['telefono']) ?></td>
                        <td style="font-size:.83rem; color:var(--muted)">
                            <?= htmlspecialchars($c['correo']) ?>
                        </td>
                        <td>
                            <span class="compras-badge <?= $badgeClass ?>">
                                <?= $compras ?> <?= $compras === 1 ? 'compra' : 'compras' ?>
                            </span>
                        </td>
                        <td>
                            <span class="valor-total">
                                $<?= number_format($c['valor_total'] ?? 0, 2) ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="compras.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-info" title="Ver compras">📋</a>
                                <a href="edit.php?id=<?= $c['id'] ?>"    class="btn btn-sm btn-outline" title="Editar">✏</a>
                                <button class="btn btn-sm btn-danger"
                                    onclick="confirmarEliminar(<?= $c['id'] ?>, '<?= htmlspecialchars($c['nombre'].' '.$c['apellido']) ?>')"
                                    title="Eliminar">🗑</button>
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
 
<!-- MODAL ELIMINAR -->
<div class="modal-overlay" id="modalEliminar">
    <div class="modal">
        <h3>Eliminar cliente</h3>
        <p id="modalMsg">¿Seguro que deseas eliminar este cliente y todo su historial de compras?</p>
        <div class="modal-actions">
            <button class="btn btn-outline btn-sm" onclick="cerrarModal()">Cancelar</button>
            <a id="modalConfirm" href="#" class="btn btn-danger btn-sm">Sí, eliminar</a>
        </div>
    </div>
</div>
 
<script>
// Búsqueda en tiempo real
document.getElementById('buscar').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tablaClientes tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
 
function confirmarEliminar(id, nombre) {
    document.getElementById('modalMsg').textContent =
        `¿Seguro que deseas eliminar al cliente "${nombre}" y todo su historial de compras?`;
    document.getElementById('modalConfirm').href =
        `../../Controlador/ClienteControlador.php?accion=eliminar&id=${id}`;
    document.getElementById('modalEliminar').classList.add('open');
}
function cerrarModal() {
    document.getElementById('modalEliminar').classList.remove('open');
}
document.getElementById('modalEliminar').addEventListener('click', e => { if (e.target === this) cerrarModal(); });
</script>
</body>
</html>