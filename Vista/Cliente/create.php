<?php
// Vista/Cliente/compras.php
// Historial de compras de UN cliente específico.
// Variables esperadas del controlador:
//   $cliente  → array ['id','cedula','nombre','apellido','telefono','correo']
//   $compras  → array de compras del cliente:
//               [['id','fecha','subtotal','impuesto_total','total','items'=>[...]]]
//               items: [['producto','categoria','cantidad','precio_unit','impuesto_pct','subtotal_item']]
//   $resumen  → array ['total_compras','total_gastado','primera_compra','ultima_compra']
 
$cliente = $cliente ?? [];
$compras  = $compras  ?? [];
$resumen  = $resumen  ?? [];
 
if (empty($cliente)) {
    header('Location: cliente.php?error=' . urlencode('Cliente no encontrado.'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras de <?= htmlspecialchars($cliente['nombre'].' '.$cliente['apellido']) ?> — MiTienda</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:#0f0f13; --surface:#1a1a23; --border:#2e2e3d;
            --accent:#f5c842; --text:#e8e8f0; --muted:#7a7a9a;
            --danger:#ff4757; --success:#2ed573; --info:#5e9eff;
            --orange:#ff6b35; --radius:12px;
        }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;}
 
        /* TOPBAR */
        .topbar{background:var(--surface);border-bottom:1px solid var(--border);padding:0 2rem;display:flex;align-items:center;justify-content:space-between;height:64px;position:sticky;top:0;z-index:100;}
        .topbar-brand{font-family:'Syne',sans-serif;font-size:1.3rem;font-weight:800;color:var(--accent);}
        .topbar-brand span{color:var(--text);}
        .topbar-nav{display:flex;gap:.5rem;}
        .topbar-nav a{color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:500;padding:.4rem .9rem;border-radius:8px;transition:all .2s;}
        .topbar-nav a:hover,.topbar-nav a.active{background:rgba(245,200,66,.1);color:var(--accent);}
 
        .main{max-width:900px;margin:0 auto;padding:2.5rem 1.5rem;}
 
        /* BREADCRUMB */
        .breadcrumb{display:flex;align-items:center;gap:.5rem;font-size:.82rem;color:var(--muted);margin-bottom:1.5rem;}
        .breadcrumb a{color:var(--muted);text-decoration:none;}
        .breadcrumb a:hover{color:var(--accent);}
        .breadcrumb-sep{opacity:.4;}
 
        /* CLIENTE HERO */
        .cliente-hero{
            background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
            padding:1.5rem 2rem; display:flex; align-items:center; gap:1.5rem;
            margin-bottom:1.5rem; flex-wrap:wrap;
        }
        .avatar{
            width:56px; height:56px; border-radius:50%;
            background:linear-gradient(135deg,var(--accent),var(--orange));
            display:flex; align-items:center; justify-content:center;
            font-family:'Syne',sans-serif; font-weight:800; font-size:1.3rem; color:#0f0f13;
            flex-shrink:0;
        }
        .cliente-info { flex:1; }
        .cliente-name{font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;}
        .cliente-meta{font-size:.85rem;color:var(--muted);margin-top:.3rem;display:flex;gap:1.5rem;flex-wrap:wrap;}
        .cliente-meta span{display:flex;align-items:center;gap:.3rem;}
 
        /* RESUMEN STATS */
        .stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;}
        .stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1.1rem 1.4rem;display:flex;flex-direction:column;gap:.3rem;}
        .stat-label{font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);}
        .stat-value{font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800;color:var(--accent);}
        .stat-sub{font-size:.78rem;color:var(--muted);}
 
        /* PAGE HEADER */
        .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .page-title{font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;}
 
        /* BUTTONS */
        .btn{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1.3rem;border-radius:var(--radius);font-family:'DM Sans',sans-serif;font-size:.88rem;font-weight:500;text-decoration:none;cursor:pointer;border:none;transition:all .2s;}
        .btn-primary{background:var(--accent);color:#0f0f13;}
        .btn-primary:hover{background:#f0bc20;transform:translateY(-1px);}
        .btn-outline{background:transparent;border:1px solid var(--border);color:var(--text);}
        .btn-outline:hover{border-color:var(--accent);color:var(--accent);}
        .btn-sm{padding:.38rem .85rem;font-size:.8rem;}
 
        /* COMPRA CARD */
        .compra-card{
            background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
            margin-bottom:1rem; overflow:hidden;
        }
        .compra-header{
            padding:1rem 1.5rem; display:flex; align-items:center; justify-content:space-between;
            cursor:pointer; user-select:none; transition:background .15s; flex-wrap:wrap; gap:.5rem;
        }
        .compra-header:hover{background:rgba(255,255,255,.03);}
        .compra-id{font-family:'Syne',sans-serif;font-size:.85rem;font-weight:700;color:var(--accent);}
        .compra-fecha{font-size:.82rem;color:var(--muted);margin-top:.15rem;}
        .compra-total{font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:800;color:var(--text);}
        .compra-toggle{font-size:.8rem;color:var(--muted);transition:transform .3s;}
        .compra-toggle.open{transform:rotate(180deg);}
 
        .compra-body{
            border-top:1px solid var(--border);
            display:none; /* toggled by JS */
        }
        .compra-body.open{display:block;}
 
        /* Items table */
        .items-table{width:100%;border-collapse:collapse;font-size:.84rem;}
        .items-table th{
            padding:.7rem 1.5rem; text-align:left;
            font-family:'Syne',sans-serif;font-size:.7rem;font-weight:600;
            text-transform:uppercase;letter-spacing:1px;color:var(--muted);
            background:rgba(245,200,66,.04); white-space:nowrap;
        }
        .items-table td{padding:.75rem 1.5rem;border-top:1px solid var(--border);vertical-align:middle;}
 
        .badge-cat{display:inline-flex;align-items:center;padding:.2rem .65rem;border-radius:20px;font-size:.75rem;font-weight:500;}
        .badge-papeleria   {background:rgba(94,158,255,.15);color:#5e9eff;}
        .badge-drogueria   {background:rgba(255,107,53,.15);color:#ff6b35;}
        .badge-supermercado{background:rgba(46,213,115,.15);color:#2ed573;}
        .badge-aseo        {background:rgba(245,200,66,.15);color:#f5c842;}
 
        /* Totales footer */
        .compra-footer{
            padding:1rem 1.5rem; border-top:1px solid var(--border);
            display:flex; justify-content:flex-end;
        }
        .totales{text-align:right;font-size:.88rem;}
        .totales-row{display:flex;justify-content:space-between;gap:3rem;padding:.2rem 0;color:var(--muted);}
        .totales-row.total{color:var(--text);font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;border-top:1px solid var(--border);margin-top:.4rem;padding-top:.6rem;}
 
        /* EMPTY */
        .empty{text-align:center;padding:4rem 2rem;}
        .empty-icon{font-size:3rem;margin-bottom:1rem;opacity:.4;}
        .empty p{color:var(--muted);margin-bottom:1.5rem;}
 
        @media(max-width:600px){.topbar-nav{display:none}.cliente-meta{flex-direction:column;gap:.4rem}}
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
 
    <nav class="breadcrumb">
        <a href="../Dashboard/index.php">Inicio</a>
        <span class="breadcrumb-sep">›</span>
        <a href="cliente.php">Clientes</a>
        <span class="breadcrumb-sep">›</span>
        <span><?= htmlspecialchars($cliente['nombre'].' '.$cliente['apellido']) ?></span>
        <span class="breadcrumb-sep">›</span>
        <span>Historial de compras</span>
    </nav>
 
    <!-- CLIENTE HERO -->
    <?php
    $iniciales = strtoupper(mb_substr($cliente['nombre'],0,1).mb_substr($cliente['apellido'],0,1));
    ?>
    <div class="cliente-hero">
        <div class="avatar"><?= $iniciales ?></div>
        <div class="cliente-info">
            <div class="cliente-name">
                <?= htmlspecialchars($cliente['nombre'].' '.$cliente['apellido']) ?>
            </div>
            <div class="cliente-meta">
                <span>🪪 CC <?= htmlspecialchars($cliente['cedula']) ?></span>
                <span>📞 <?= htmlspecialchars($cliente['telefono']) ?></span>
                <span>✉️ <?= htmlspecialchars($cliente['correo']) ?></span>
            </div>
        </div>
        <a href="edit.php?id=<?= $cliente['id'] ?>" class="btn btn-outline btn-sm">✏ Editar cliente</a>
    </div>
 
    <!-- RESUMEN -->
    <div class="stats-row">
        <div class="stat-card">
            <span class="stat-label">Total de compras</span>
            <span class="stat-value"><?= $resumen['total_compras'] ?? count($compras) ?></span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Total gastado</span>
            <span class="stat-value">$<?= number_format($resumen['total_gastado'] ?? 0, 2) ?></span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Primera compra</span>
            <span class="stat-value" style="font-size:1rem; margin-top:.3rem;">
                <?= $resumen['primera_compra'] ?? '—' ?>
            </span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Última compra</span>
            <span class="stat-value" style="font-size:1rem; margin-top:.3rem;">
                <?= $resumen['ultima_compra'] ?? '—' ?>
            </span>
        </div>
    </div>
 
    <!-- HEADER -->
    <div class="page-header">
        <h2 class="page-title">📋 Historial de compras</h2>
        <a href="../Compras/create.php?cliente_id=<?= $cliente['id'] ?>" class="btn btn-primary">
            ＋ Nueva compra
        </a>
    </div>
 
    <!-- LISTA DE COMPRAS -->
    <?php if (empty($compras)): ?>
        <div class="empty">
            <div class="empty-icon">🛒</div>
            <p>Este cliente aún no ha realizado compras.</p>
            <a href="../Compras/create.php?cliente_id=<?= $cliente['id'] ?>" class="btn btn-primary">
                Registrar primera compra
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($compras as $idx => $compra): ?>
        <?php
        $catSlug = fn($n) => strtolower(str_replace(['é','ó','á','ú','í',' '],['e','o','a','u','i','-'],$n));
        ?>
        <div class="compra-card">
            <div class="compra-header" onclick="toggleCompra(<?= $idx ?>)">
                <div>
                    <div class="compra-id">Compra #<?= str_pad($compra['id'], 5, '0', STR_PAD_LEFT) ?></div>
                    <div class="compra-fecha">📅 <?= htmlspecialchars($compra['fecha']) ?></div>
                </div>
                <div style="display:flex;align-items:center;gap:1rem;">
                    <div class="compra-total">$<?= number_format($compra['total'], 2) ?></div>
                    <span class="compra-toggle" id="toggle-<?= $idx ?>">▼</span>
                </div>
            </div>
 
            <div class="compra-body" id="body-<?= $idx ?>">
                <!-- Items -->
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Precio unit.</th>
                            <th>IVA</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($compra['items'] as $item): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($item['producto']) ?></strong></td>
                            <td>
                                <span class="badge-cat badge-<?= $catSlug($item['categoria']) ?>">
                                    <?= htmlspecialchars($item['categoria']) ?>
                                </span>
                            </td>
                            <td><?= (int)$item['cantidad'] ?> und.</td>
                            <td>$<?= number_format($item['precio_unit'], 2) ?></td>
                            <td style="color:var(--muted);">
                                <?= $item['impuesto_pct'] > 0 ? $item['impuesto_pct'].'%' : '<span style="color:var(--success)">Sin IVA</span>' ?>
                            </td>
                            <td><strong>$<?= number_format($item['subtotal_item'], 2) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
 
                <!-- Totales -->
                <div class="compra-footer">
                    <div class="totales">
                        <div class="totales-row">
                            <span>Subtotal</span>
                            <span>$<?= number_format($compra['subtotal'], 2) ?></span>
                        </div>
                        <div class="totales-row">
                            <span>Impuesto</span>
                            <span>$<?= number_format($compra['impuesto_total'], 2) ?></span>
                        </div>
                        <div class="totales-row total">
                            <span>TOTAL</span>
                            <span>$<?= number_format($compra['total'], 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
 
    <!-- VOLVER -->
    <div style="margin-top:2rem;">
        <a href="cliente.php" class="btn btn-outline">← Volver a Clientes</a>
    </div>
 
</main>
 
<script>
function toggleCompra(idx) {
    const body   = document.getElementById('body-' + idx);
    const toggle = document.getElementById('toggle-' + idx);
    const open   = body.classList.toggle('open');
    toggle.classList.toggle('open', open);
}
// Abrir la primera compra por defecto
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('body-0')) toggleCompra(0);
});
</script>
</body>
</html>
 