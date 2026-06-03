<?php
// Vista/Categoria/create.php
// Formulario para CREAR una nueva categoría.
// Variables esperadas del controlador:
//   $errores   → array asociativo campo=>mensaje  (vacío si no hay errores)
//   $categoria → array con valores previos (repoblado tras error de validación)
//   $titulo    → string (opcional, título de página)
 
$errores   = $errores   ?? [];
$categoria = $categoria ?? [];
$titulo    = $titulo    ?? 'Nueva Categoría';
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-brand {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem; font-weight: 800;
            color: var(--accent);
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
        .main {
            max-width: 640px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }
 
        /* BREADCRUMB */
        .breadcrumb {
            display: flex; align-items: center; gap: .5rem;
            font-size: .82rem; color: var(--muted);
            margin-bottom: 2rem;
        }
        .breadcrumb a { color: var(--muted); text-decoration: none; }
        .breadcrumb a:hover { color: var(--accent); }
        .breadcrumb-sep { opacity: .4; }
        .breadcrumb-current { color: var(--text); }
 
        /* PAGE TITLE */
        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.8rem; font-weight: 800;
            margin-bottom: .4rem;
        }
        .page-subtitle {
            color: var(--muted); font-size: .9rem; margin-bottom: 2rem;
        }
 
        /* CARD */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem;
        }
 
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
 
        /* FORM FOOTER */
        .form-footer {
            display: flex; justify-content: flex-end; gap: .8rem;
            margin-top: 2rem; padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }
 
        /* GLOBAL ERRORS */
        .alert-error {
            background: rgba(255,71,87,.1); border: 1px solid rgba(255,71,87,.3);
            color: #ff4757; border-radius: var(--radius);
            padding: .9rem 1.2rem; margin-bottom: 1.5rem;
            font-size: .9rem;
        }
        .alert-error ul { margin: .4rem 0 0 1.2rem; }
        .alert-error li { margin-bottom: .2rem; }
 
        @media (max-width: 600px) {
            .topbar-nav { display: none; }
            .form-footer { flex-direction: column-reverse; }
            .btn { width: 100%; justify-content: center; }
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
        <span class="breadcrumb-current">Nueva categoría</span>
    </nav>
 
    <h1 class="page-title"><?= htmlspecialchars($titulo) ?></h1>
    <p class="page-subtitle">
        Selecciona la categoría que deseas registrar. El impuesto se asigna automáticamente.
    </p>
 
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
            <input type="hidden" name="accion" value="crear">
 
            <?php
            $modo = 'crear';
            include '_form.php';
            ?>
 
            <div class="form-footer">
                <a href="categoria.php" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    ＋ Crear categoría
                </button>
            </div>
        </form>
    </div>
 
</main>
 
</body>
</html>