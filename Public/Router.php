<?php
// ============================================================
// ROUTER.PHP — Enruta peticiones al controlador y acción correctos
// Parámetros: ?c=controlador&a=accion
// ============================================================

// Mapa de controladores disponibles
$controladores = [
    'categoria'  => 'CategoriaController',
    'cliente'    => 'ClienteController',
    'compra'     => 'CompraController',
    'producto'   => 'ProductoController',
    'proveedor'  => 'ProveedorController',
    'reporte'    => 'ReportesController',
];

// Acciones permitidas por controlador
$acciones = [
    'CategoriaController' => ['index', 'crear', 'guardar', 'editar', 'actualizar', 'eliminar'],
    'ClienteController'   => ['index', 'crear', 'guardar', 'editar', 'actualizar', 'eliminar'],
    'CompraController'    => ['index', 'nueva', 'procesar', 'resumen', 'historial', 'eliminar'],
    'ProductoController'  => ['index', 'crear', 'guardar', 'editar', 'actualizar', 'eliminar'],
    'ProveedorController' => ['index', 'crear', 'guardar', 'editar', 'actualizar', 'eliminar',
                          'asociar', 'guardarAsociacion', 'registrarPago', 'desasociar'],
    'ReportesController'  => ['index', 'financiero', 'clientesFrecuentes', 'clientesUnicos',
                              'clienteMasFrecuente', 'stockMinimo', 'productosTop'],
];

// ── Leer parámetros de la URL ──────────────────────────────
$c = strtolower(trim($_GET['c'] ?? 'reporte'));   // controlador (default: reporte/dashboard)
$a = trim($_GET['a'] ?? 'index');                 // acción     (default: index)

// ── Validar controlador ───────────────────────────────────
if (!array_key_exists($c, $controladores)) {
    http_response_code(404);
    die('<h2>Controlador no encontrado: ' . htmlspecialchars($c) . '</h2>');
}

$claseControlador = $controladores[$c];

// ── Validar acción ────────────────────────────────────────
if (!in_array($a, $acciones[$claseControlador], true)) {
    http_response_code(404);
    die('<h2>Acción no encontrada: ' . htmlspecialchars($a) . '</h2>');
}

// ── Cargar y ejecutar controlador ────────────────────────
require_once __DIR__ . "/../Controlador/{$claseControlador}.php";

$controlador = new $claseControlador();
$controlador->$a();