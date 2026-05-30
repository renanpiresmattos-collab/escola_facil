<?php
/**
 * Sidebar component
 * Expects $menuItems array in format:
 * [ ['title'=>'', 'url'=>'', 'submenu'=>[...]], ... ]
 */
$activeMenu = $activeMenu ?? null;
$activeSubmenu = $activeSubmenu ?? null;
?>
<aside class="app-sidebar" id="app-sidebar" data-active-menu="<?= esc((string) $activeMenu) ?>" data-active-submenu="<?= esc((string) $activeSubmenu) ?>">
    <div class="sidebar-top">
        <div class="logo">Escola Fácil</div>
    </div>
    <nav class="sidebar-nav">
        <ul class="sidebar-menu" id="sidebar-menu"></ul>
    </nav>
    <div class="sidebar-footer">
        <a href="<?= base_url('logout') ?>" class="sidebar-logout" role="button">
            Sair
        </a>
    </div>
</aside>
