<?php
// views/templates/sidebar.php - Menú lateral principal
?>
<div class="d-flex">
    <div class="bg-white border-end shadow-sm" id="sidebar-wrapper" style="min-width: 200px;">
        <div class="sidebar-heading fw-bold text-center py-3">Menú</div>
        <div class="list-group list-group-flush">
            <a href="<?= APP_URL ?>views/admin/dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
            <a href="<?= APP_URL ?>views/admin/usuarios.php" class="list-group-item list-group-item-action">Usuarios</a>
            <a href="<?= APP_URL ?>views/admin/tecnicos.php" class="list-group-item list-group-item-action">Técnicos</a>
            <a href="<?= APP_URL ?>views/admin/supervisores.php" class="list-group-item list-group-item-action">Supervisores</a>
            <a href="<?= APP_URL ?>views/admin/reports.php" class="list-group-item list-group-item-action">Reportes</a>
        </div>
    </div>
    <div class="container-fluid p-4"> <!-- Comienza el contenido principal -->
