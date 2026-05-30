<?php
/**
 * Header component. Recebe:
 * - $pageTitle
 * - $activeMenu
 * - $activeSubmenu
 */
$pageTitle = $pageTitle ?? 'Página';
$activeMenu = $activeMenu ?? null;
$activeSubmenu = $activeSubmenu ?? null;
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= esc($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css') ?>">
    <script>window.sidebarMenuApiUrl = '<?= site_url('api/menu') ?>';</script>
</head>
<body>

    <div class="sidebar-backdrop" id="sidebar-backdrop" hidden></div>
    <?= view('components/sidebar', [
        'activeMenu' => $activeMenu,
        'activeSubmenu' => $activeSubmenu,
    ]) ?>

    <div class="app-layout">
        <header class="app-header">
            <div class="header-inner header-bar">
                <button class="menu-toggle" id="sidebar-toggle" type="button" aria-controls="app-sidebar" aria-expanded="false" aria-label="Abrir menu">
                    <span aria-hidden="true">☰</span>
                </button>
                <div class="header-title">
                    <h1><?= esc($pageTitle) ?></h1>
                </div>
            </div>
        </header>

        <main class="app-main">
