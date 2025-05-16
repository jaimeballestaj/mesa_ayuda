<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = '/mesa_ayuda';
if (!defined('BASE_URL')) {
    define('BASE_URL', $base_path);
}

$is_logged_in = isset($_SESSION['usuario_id']);
$usuario_nombre = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : '';

$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<!-- Navbar profesional con diseño colorido y moderno -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-lg fixed-top"
     style="background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7); border-bottom: 3px solid #fbbf24;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="<?= $base_path ?>/">
            <div class="bg-gradient rounded-3 p-2 me-3 shadow"
                 style="background: linear-gradient(135deg, #fbbf24, #f59e0b);">
                <i class="bi bi-headset text-white" style="font-size: 1.8rem;"></i>
            </div>
            <div>
                <span class="text-white fs-4">Mesa de Ayuda</span>
                <div class="text-warning small fw-light" style="margin-top: -5px;">Soporte Técnico</div>
            </div>
        </a>

        <button class="navbar-toggler border-0 shadow-sm" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a href="<?= $base_path ?>/"
                       class="nav-link text-white px-4 py-2 rounded-pill me-2 position-relative <?= ($current_page == 'index.php' && $current_dir == 'mesa_ayuda') ? 'active-link' : '' ?>"
                       style="transition: all 0.3s ease;">
                        <i class="bi bi-house-door-fill me-2"></i>Inicio
                    </a>
                </li>

                <?php if ($is_logged_in): ?>
                    <li class="nav-item">
                        <a href="<?= $base_path ?>/views/tickets/list.php"
                           class="nav-link text-white px-4 py-2 rounded-pill me-2 position-relative <?= ($current_dir == 'tickets') ? 'active-link' : '' ?>"
                           style="transition: all 0.3s ease;">
                            <i class="bi bi-ticket-detailed-fill me-2"></i>Mis Tickets
                        </a>
                    </li>

                    <?php if (isset($_SESSION['es_admin']) && $_SESSION['es_admin']): ?>
                        <li class="nav-item">
                            <a href="<?= $base_path ?>/views/admin/index.php"
                               class="nav-link text-white px-4 py-2 rounded-pill me-2 position-relative <?= ($current_dir == 'admin') ? 'active-link' : '' ?>"
                               style="transition: all 0.3s ease;">
                                <i class="bi bi-speedometer2 me-2"></i>Panel Admin
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center px-4 py-2 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25"
                           href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                           style="transition: all 0.3s ease;">
                            <div class="bg-gradient rounded-circle p-1 me-2 shadow-sm"
                                 style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="bi bi-person-circle text-white" style="font-size: 1.3rem;"></i>
                            </div>
                            <span class="fw-medium"><?= htmlspecialchars($usuario_nombre) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 animate slideIn"
                            style="background: linear-gradient(135deg, #f3f4f6, #ffffff);">
                            <li><a class="dropdown-item py-2 px-4" href="<?= $base_path ?>/views/usuario/perfil.php"><i class="bi bi-person-fill me-2 text-primary"></i> Mi Perfil</a></li>
                            <li><a class="dropdown-item py-2 px-4" href="<?= $base_path ?>/views/usuario/mis_equipos.php"><i class="bi bi-pc-display me-2 text-purple"></i> Mis Equipos</a></li>
                            <li><a class="dropdown-item py-2 px-4" href="<?= $base_path ?>/views/usuario/mis_prestamos.php"><i class="bi bi-laptop me-2 text-info"></i> Mis Préstamos</a></li>
                            <li><a class="dropdown-item py-2 px-4" href="<?= $base_path ?>/views/tickets/create.php"><i class="bi bi-plus-circle-fill me-2 text-success"></i> Nuevo Ticket</a></li>
                            <li><hr class="dropdown-divider my-2"></li>
                            <li><a class="dropdown-item py-2 px-4 text-danger" href="<?= $base_path ?>/views/auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?= $base_path ?>/views/auth/login.php"
                           class="btn btn-light btn-sm px-4 py-2 me-3 rounded-pill shadow-sm fw-medium"
                           style="transition: all 0.3s ease;">
                            <i class="bi bi-box-arrow-in-right me-2 text-primary"></i>
                            <span class="text-primary">Iniciar Sesión</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base_path ?>/views/auth/registro.php"
                           class="btn btn-warning btn-sm px-4 py-2 rounded-pill shadow fw-medium"
                           style="background: linear-gradient(135deg, #fbbf24, #f59e0b); border: none; transition: all 0.3s ease;">
                            <i class="bi bi-person-plus-fill me-2"></i> Registrarse
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div style="height: 90px;"></div>
