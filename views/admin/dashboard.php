<?php
// Archivo: views/admin/dashboard.php
require_once __DIR__ . '/../../config/config.php';
require_once ROOT_PATH . '/views/templates/header.php';
require_once ROOT_PATH . '/views/templates/navbar.php';
require_once ROOT_PATH . '/views/templates/sidebar.php';

use models\Usuario;
use models\Ticket;
use models\Tecnico;
use models\Supervisor;
use models\Equipo;
use models\Categoria;
use models\Departamento;

$usuariosTotal = (new Usuario())->contarTodos();
$ticketsTotales = Ticket::contarTodos();
$ticketsAbiertos = Ticket::contarPorEstado('abierto');
$ticketsCerrados = Ticket::contarPorEstado('cerrado');
$tecnicosActivos = Tecnico::contarActivos();
$supervisores = Supervisor::contarActivos();
$equiposTotales = Equipo::contarTodos();
$categoriasActivas = Categoria::contarActivas();
$departamentosTotales = Departamento::contarTodos();
?>

<main class="container-fluid px-4 mt-4">
    <h1 class="mt-4">Panel de Administración</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">Usuarios Registrados: <?= $usuariosTotal ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">Tickets Abiertos: <?= $ticketsAbiertos ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">Tickets Cerrados: <?= $ticketsCerrados ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body">Total Tickets: <?= $ticketsTotales ?></div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">Técnicos Activos: <?= $tecnicosActivos ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">Supervisores Activos: <?= $supervisores ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">Equipos Registrados: <?= $equiposTotales ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">Categorías Activas: <?= $categoriasActivas ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">Departamentos: <?= $departamentosTotales ?></div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Últimos Usuarios Registrados
        </div>
        <div class="card-body">
            <?php include ROOT_PATH . '/views/components/user_card.php'; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-bar me-1"></i> Estadísticas
        </div>
        <div class="card-body">
            <canvas id="adminChart" height="100"></canvas>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('adminChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Usuarios', 'Tickets', 'Técnicos', 'Supervisores', 'Equipos', 'Categorías', 'Departamentos'],
        datasets: [{
            label: 'Totales',
            data: [
                <?= $usuariosTotal ?>,
                <?= $ticketsTotales ?>,
                <?= $tecnicosActivos ?>,
                <?= $supervisores ?>,
                <?= $equiposTotales ?>,
                <?= $categoriasActivas ?>,
                <?= $departamentosTotales ?>
            ],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(99, 255, 132, 0.7)',
                'rgba(255, 99, 132, 0.7)'
            ]
        }]
    }
});
</script>

<?php require_once ROOT_PATH . '/views/templates/footer.php'; ?>
