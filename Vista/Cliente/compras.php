<?php
// Vista/Cliente/create.php
// Formulario para REGISTRAR un nuevo cliente.
// Variables esperadas:
//   $errores   → array campo=>mensaje
//   $cliente   → array (repoblado tras error de validación)
 
$errores  = $errores  ?? [];
$cliente  = $cliente  ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente — MiTienda</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:#0f0f13; --surface:#1a1a23; --border:#2e2e3d;
            --accent:#f5c842; --text:#e8e8f0; --muted:#7a7a9a;
            --danger:#ff4757; --success:#2ed573; --radius:12px;
        }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;}
 
        .topbar{background:var(--surface);border-bottom:1px solid var(--border);padding:0 2rem;display:flex;align-items:center;justify-content:space-between;height:64px;position:sticky;top:0;z-index:100;}
        .topbar-brand{font-family:'Syne',sans-serif;font-size:1.3rem;font-weight:800;color:var(--accent);}
        .topbar-brand span{color:var(--text);}
        .topbar-nav{display:flex;gap:.5rem;}
        .topbar-nav a{color:var(--muted);text-decoration:none;font-size:.85rem;font-weight:500;padding:.4rem .9rem;border-radius:8px;transition:all .2s;}
        .topbar-nav a:hover,.topbar-nav a.active{background:rgba(245,200,66,.1);color:var(--accent);}
 
        .main{max-width:620px;margin:0 auto;padding:2.5rem 1.5rem;}
 
        .breadcrumb{display:flex;align-items:center;gap:.5rem;font-size:.82rem;color:var(--muted);margin-bottom:2rem;}
        .breadcrumb a{color:var(--muted);text-decoration:none;}
        .breadcrumb a:hover{color:var(--accent);}
        .breadcrumb-sep{opacity:.4;}
 
        .page-title{font-family:'Syne',sans-serif;font-size:1.8rem;font-weight:800;margin-bottom:.3rem;}
        .page-subtitle{color:var(--muted);font-size:.9rem;margin-bottom:2rem;}
 
        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:2rem;}
 
        .alert-error{background:rgba(255,71,87,.1);border:1px solid rgba(255,71,87,.3);color:#ff4757;border-radius:var(--radius);padding:.9rem 1.2rem;margin-bottom:1.5rem;font-size:.9rem;}
        .alert-error ul{margin:.4rem 0 0 1.2rem;}
        .alert-error li{margin-bottom:.2rem;}
 
        .btn{display:inline-flex;align-items:center;gap:.5rem;padding:.7rem 1.5rem;border-radius:var(--radius);font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:500;text-decoration:none;cursor:pointer;border:none;transition:all .2s;}
        .btn-primary{background:var(--accent);color:#0f0f13;}
        .btn-primary:hover{background:#f0bc20;transform:translateY(-1px);}
        .btn-outline{background:transparent;border:1px solid var(--border);color:var(--text);}
        .btn-outline:hover{border-color:var(--accent);color:var(--accent);}
 
        .form-footer{display:flex;justify-content:flex-end;gap:.8rem;margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--border);}
 
        @media(max-width:600px){.topbar-nav{display:none}.form-footer{flex-direction:column-reverse}.btn{width:100%;justify-content:center}}
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
        <span>Nuevo cliente</span>
    </nav>
 
    <h1 class="page-title">Nuevo Cliente</h1>
    <p class="page-subtitle">Completa los datos del cliente para registrarlo en el sistema.</p>
 
    <?php if (!empty($errores)): ?>
        <div class="alert-error">
            ⚠️ Por favor corrige los siguientes errores:
            <ul><?php foreach($errores as $m): ?><li><?= htmlspecialchars($m) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>
 
    <div class="card">
        <form action="../../Controlador/ClienteControlador.php" method="POST" novalidate>
            <input type="hidden" name="accion" value="crear">
            <?php $modo = 'crear'; include '_form.php'; ?>
            <div class="form-footer">
                <a href="cliente.php" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">＋ Registrar cliente</button>
            </div>
        </form>
    </div>
 
</main>
</body>
</html>