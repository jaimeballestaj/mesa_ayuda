<?php
// Ruta: views/usuario/panel.php
// Iniciar el buffer de salida para evitar problemas con los headers
ob_start();

// Definir la ruta raíz del proyecto
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/mesa_ayuda');
define('BASE_URL', '/mesa_ayuda');

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "/views/auth/login.php?error=denegado");
    exit;
}

// Incluir archivo de configuración para conexión a BD
require_once ROOT_PATH . '/config/config.php';

// Obtener datos del usuario
$usuario_id = $_SESSION['usuario_id'];
$usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Usuario';

// Obtener estadísticas de tickets del usuario
try {
    // Contar tickets por estado
    $stmt = $conn->prepare("
        SELECT e.nombre AS estado, COUNT(t.id_ticket) AS cantidad
        FROM tickets t
        JOIN estados e ON t.id_estado = e.id_estado
        WHERE t.id_usuario = :usuario_id
        GROUP BY t.id_estado, e.nombre
    ");
    $stmt->execute(['usuario_id' => $usuario_id]);
    $estadisticas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Inicializar contadores
    $tickets_abiertos = 0;
    $tickets_en_proceso = 0;
    $tickets_cerrados = 0;
    $tickets_total = 0;
    
    // Procesar resultados
    foreach ($estadisticas as $stat) {
        $tickets_total += $stat['cantidad'];
        switch (strtolower($stat['estado'])) {
            case 'abierto':
                $tickets_abiertos = $stat['cantidad'];
                break;
            case 'en proceso':
                $tickets_en_proceso = $stat['cantidad'];
                break;
            case 'resuelto':
            case 'cerrado':
                $tickets_cerrados += $stat['cantidad'];
                break;
        }
    }
    
    // Obtener tickets recientes
    $stmt = $conn->prepare("
        SELECT 
            t.id_ticket,
            t.titulo,
            c.nombre AS categoria,
            e.nombre AS estado,
            t.fecha_creacion,
            TIMESTAMPDIFF(HOUR, t.fecha_creacion, NOW()) AS horas_transcurridas,
            TIMESTAMPDIFF(DAY, t.fecha_creacion, NOW()) AS dias_transcurridos
        FROM 
            tickets t
        JOIN 
            categorias c ON t.id_categoria = c.id_categoria
        JOIN 
            estados e ON t.id_estado = e.id_estado
        WHERE 
            t.id_usuario = :usuario_id
        ORDER BY 
            t.fecha_creacion DESC
        LIMIT 5
    ");
    $stmt->execute(['usuario_id' => $usuario_id]);
    $tickets_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear las fechas para una visualización amigable
    foreach ($tickets_recientes as &$ticket) {
        if ($ticket['horas_transcurridas'] < 24) {
            $ticket['fecha_formateada'] = 'Hace ' . $ticket['horas_transcurridas'] . ' hora' . ($ticket['horas_transcurridas'] != 1 ? 's' : '');
        } else if ($ticket['dias_transcurridos'] < 7) {
            $ticket['fecha_formateada'] = 'Hace ' . $ticket['dias_transcurridos'] . ' día' . ($ticket['dias_transcurridos'] != 1 ? 's' : '');
        } else {
            $ticket['fecha_formateada'] = date('d/m/Y', strtotime($ticket['fecha_creacion']));
        }
    }
    unset($ticket);
    
} catch (PDOException $e) {
    // Si hay un error, usamos datos simulados
    error_log("Error al obtener datos del panel de usuario: " . $e->getMessage());
    
    // Datos simulados
    $tickets_abiertos = 5;
    $tickets_en_proceso = 2;
    $tickets_cerrados = 12;
    $tickets_total = 19;
    
    // Tickets recientes simulados
    $tickets_recientes = [
        [
            'id_ticket' => '1245',
            'titulo' => 'Problema con impresora',
            'categoria' => 'Hardware',
            'estado' => 'Abierto',
            'fecha_formateada' => 'Hace 2 horas'
        ],
        [
            'id_ticket' => '1244',
            'titulo' => 'Error al abrir correo',
            'categoria' => 'Software',
            'estado' => 'En Proceso',
            'fecha_formateada' => 'Hace 1 día'
        ],
        [
            'id_ticket' => '1243',
            'titulo' => 'Problema de acceso a la red',
            'categoria' => 'Red',
            'estado' => 'Cerrado',
            'fecha_formateada' => 'Hace 3 días'
        ]
    ];
}

// Obtener departamento del usuario (simulado o desde la BD)
try {
    $stmt = $conn->prepare("
        SELECT d.nombre 
        FROM usuarios u
        JOIN departamentos d ON u.id_departamento = d.id_departamento
        WHERE u.id_usuario = :usuario_id
    ");
    $stmt->execute(['usuario_id' => $usuario_id]);
    $departamento_result = $stmt->fetch(PDO::FETCH_ASSOC);
    $departamento_usuario = $departamento_result ? $departamento_result['nombre'] : "General";
} catch (PDOException $e) {
    $departamento_usuario = "General";
}

// Incluir la cabecera y navbar
include_once ROOT_PATH . '/views/templates/header.php';
include_once ROOT_PATH . '/views/templates/navbar.php';
?>

<!-- Bootstrap 5 y Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
:root {
    --primary: #5A4FCF;
    --primary-dark: #4339A8;
    --secondary: #6C757D;
    --success: #28A745;
    --success-dark: #208638;
    --danger: #DC3545;
    --danger-dark: #bd2130;
    --warning: #FFC107;
    --warning-dark: #d39e00;
    --info: #17A2B8;
    --info-dark: #138496;
    --light: #F8F9FA;
    --dark: #343A40;
}

body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
    background-color: #f8f9fa;
    overflow-x: hidden;
}

.navbar {
    flex-shrink: 0;
}

.main-container {
    flex: 1 0 auto;
    display: flex;
    flex-direction: column;
    padding: 2rem 0;
}

.footer {
    flex-shrink: 0;
    margin-top: 0 !important;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 3rem 2rem;
    border-radius: 1rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(90, 79, 207, 0.3);
    z-index: 1;
    margin-bottom: 2.5rem;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
    z-index: -1;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.hero-section h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    position: relative;
}

.hero-section p {
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 700px;
    margin: 0 auto;
}

/* Cards Grid */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.card-option {
    background: white;
    border: none;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.card-option::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--card-color, #5A4FCF) 0%, var(--card-color-dark, #4339A8) 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.card-option:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.card-option:hover::before {
    transform: scaleX(1);
}

.option-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.card-option:hover .option-icon {
    transform: scale(1.1);
}

.option-icon i {
    font-size: 2.5rem;
}

.option-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.option-description {
    color: #6c757d;
    font-size: 0.95rem;
    margin: 0;
}

/* Colores específicos para cada card */
.card-profile { --card-color: #5A4FCF; --card-color-dark: #4339A8; }
.card-profile .option-icon { background-color: rgba(90, 79, 207, 0.1); color: #5A4FCF; }

.card-ticket { --card-color: #28A745; --card-color-dark: #208638; }
.card-ticket .option-icon { background-color: rgba(40, 167, 69, 0.1); color: #28A745; }

.card-list { --card-color: #DC3545; --card-color-dark: #bd2130; }
.card-list .option-icon { background-color: rgba(220, 53, 69, 0.1); color: #DC3545; }

.card-knowledge { --card-color: #17A2B8; --card-color-dark: #138496; }
.card-knowledge .option-icon { background-color: rgba(23, 162, 184, 0.1); color: #17A2B8; }

/* Stat Cards */
.stat-card {
    background: white;
    border: none;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #6c757d;
    font-size: 0.95rem;
}

.stat-abiertos .stat-icon { color: #DC3545; }
.stat-proceso .stat-icon { color: #FFC107; }
.stat-cerrados .stat-icon { color: #28A745; }
.stat-total .stat-icon { color: #5A4FCF; }

/* Tickets List */
.tickets-list {
    background: white;
    border: none;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
}

.tickets-title {
    font-weight: 700;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.tickets-title i {
    margin-right: 0.5rem;
    color: #5A4FCF;
}

.tickets-list .table {
    margin-bottom: 0;
}

.ticket-badge {
    padding: 0.35em 0.65em;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.75em;
}

.badge-abierto {
    background-color: rgba(220, 53, 69, 0.1);
    color: #DC3545;
}

.badge-proceso {
    background-color: rgba(255, 193, 7, 0.1);
    color: #d39e00;
}

.badge-cerrado, .badge-resuelto {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28A745;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .hero-section {
        padding: 2rem 1.5rem;
    }
    
    .hero-section h1 {
        font-size: 2rem;
    }
    
    .cards-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .hero-section h1 {
        font-size: 1.75rem;
    }
    
    .hero-section p {
        font-size: 1rem;
    }
    
    .card-option {
        padding: 1.5rem;
    }
    
    .option-icon {
        width: 60px;
        height: 60px;
    }
    
    .option-icon i {
        font-size: 2rem;
    }
}

/* Animation */
.fade-in-up {
    animation: fadeInUp 0.5s ease forwards;
    opacity: 0;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.delay-100 { animation-delay: 0.1s; }
.delay-200 { animation-delay: 0.2s; }
.delay-300 { animation-delay: 0.3s; }
.delay-400 { animation-delay: 0.4s; }

/* Main content wrapper */
.wrapper {
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
    padding: 0 1rem;
}
</style>

<div class="main-container">
    <div class="wrapper">
        <!-- Hero Welcome Section -->
        <div class="hero-section fade-in-up">
            <h1>¡Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?>!</h1>
            <p>Departamento: <?php echo htmlspecialchars($departamento_usuario); ?> | Fecha: <?php echo date('d/m/Y'); ?></p>
        </div>

        <!-- Acciones Rápidas -->
        <div class="cards-grid">
            <!-- Mi Perfil -->
            <a href="<?php echo BASE_URL; ?>/views/auth/perfil.php" class="text-decoration-none fade-in-up delay-100">
                <div class="card-option card-profile">
                    <div class="option-icon">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h5 class="option-title">Mi Perfil</h5>
                    <p class="option-description">Ver y editar información personal</p>
                </div>
            </a>

            <!-- Nuevo Ticket -->
            <a href="<?php echo BASE_URL; ?>/views/tickets/create.php" class="text-decoration-none fade-in-up delay-200">
                <div class="card-option card-ticket">
                    <div class="option-icon">
                        <i class="bi bi-plus-circle-fill"></i>
                    </div>
                    <h5 class="option-title">Nuevo Ticket</h5>
                    <p class="option-description">Reportar un problema o solicitud</p>
                </div>
            </a>

            <!-- Mis Tickets -->
            <a href="<?php echo BASE_URL; ?>/views/tickets/list.php" class="text-decoration-none fade-in-up delay-300">
                <div class="card-option card-list">
                    <div class="option-icon">
                        <i class="bi bi-ticket-detailed-fill"></i>
                    </div>
                    <h5 class="option-title">Mis Tickets</h5>
                    <p class="option-description">Consultar estado de mis solicitudes</p>
                </div>
            </a>

            <!-- Base de Conocimiento -->
            <a href="<?php echo BASE_URL; ?>/views/conocimiento/index.php" class="text-decoration-none fade-in-up delay-400">
                <div class="card-option card-knowledge">
                    <div class="option-icon">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <h5 class="option-title">Base de Conocimiento</h5>
                    <p class="option-description">Consultar guías y soluciones</p>
                </div>
            </a>
        </div>

        <!-- Estadísticas y Tickets Recientes -->
        <div class="row">
            <!-- Estadísticas de Tickets -->
            <div class="col-lg-4">
                <div class="stat-card stat-abiertos text-center fade-in-up">
                    <div class="stat-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div class="stat-value"><?php echo $tickets_abiertos; ?></div>
                    <div class="stat-label">Tickets Abiertos</div>
                </div>
                
                <div class="stat-card stat-proceso text-center fade-in-up" style="animation-delay: 0.1s;">
                    <div class="stat-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stat-value"><?php echo $tickets_en_proceso; ?></div>
                    <div class="stat-label">En Proceso</div>
                </div>
                
                <div class="stat-card stat-cerrados text-center fade-in-up" style="animation-delay: 0.2s;">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-value"><?php echo $tickets_cerrados; ?></div>
                    <div class="stat-label">Tickets Cerrados</div>
                </div>
                
                <div class="stat-card stat-total text-center fade-in-up" style="animation-delay: 0.3s;">
                    <div class="stat-icon">
                        <i class="bi bi-ticket-detailed"></i>
                    </div>
                    <div class="stat-value"><?php echo $tickets_total; ?></div>
                    <div class="stat-label">Total de Tickets</div>
                </div>
            </div>

            <!-- Tickets Recientes -->
            <div class="col-lg-8">
                <div class="tickets-list fade-in-up" style="animation-delay: 0.4s;">
                    <div class="tickets-title">
                        <h4><i class="bi bi-clock-history"></i> Tickets Recientes</h4>
                        <a href="<?php echo BASE_URL; ?>/views/tickets/list.php" class="btn btn-outline-primary btn-sm">
                            Ver Todos <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    
                    <?php if (empty($tickets_recientes)): ?>
                        <div class="text-center py-4">
                            <img src="<?php echo BASE_URL; ?>/assets/img/empty-tickets.svg" alt="No hay tickets" style="width: 120px; height: 120px; opacity: 0.5;" class="mb-3">
                            <p class="text-muted">No tienes tickets recientes</p>
                            <a href="<?php echo BASE_URL; ?>/views/tickets/create.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Crear nuevo ticket
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Asunto</th>
                                        <th>Categoría</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tickets_recientes as $ticket): ?>
                                        <tr>
                                            <td><strong>#<?php echo htmlspecialchars($ticket['id_ticket']); ?></strong></td>
                                            <td class="text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($ticket['titulo']); ?>">
                                                <?php echo htmlspecialchars($ticket['titulo']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($ticket['categoria']); ?></td>
                                            <td>
                                                <?php 
                                                $estado_class = '';
                                                switch (strtolower($ticket['estado'])) {
                                                    case 'abierto':
                                                        $estado_class = 'badge-abierto';
                                                        break;
                                                    case 'en proceso':
                                                        $estado_class = 'badge-proceso';
                                                        break;
                                                    case 'resuelto':
                                                    case 'cerrado':
                                                        $estado_class = 'badge-cerrado';
                                                        break;
                                                    default:
                                                        $estado_class = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="ticket-badge <?php echo $estado_class; ?>">
                                                    <?php echo htmlspecialchars($ticket['estado']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($ticket['fecha_formateada']); ?></td>
                                            <td class="text-end">
                                                <a href="<?php echo BASE_URL; ?>/views/tickets/view.php?id=<?php echo $ticket['id_ticket']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once ROOT_PATH . '/views/templates/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animación de entrada escalonada para las cards
    const cards = document.querySelectorAll('.fade-in-up');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
        }, 100 * index);
    });

    // Efecto parallax en el hero section
    const hero = document.querySelector('.hero-section');
    
    window.addEventListener('scroll', () => {
        if (hero) {
            const scrolled = window.pageYOffset;
            hero.style.transform = `translateY(${scrolled * 0.05}px)`;
        }
    });
});
</script>

<?php 
// Liberar el buffer de salida
ob_end_flush();
?>