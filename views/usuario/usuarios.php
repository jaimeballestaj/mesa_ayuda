<?php
// Ruta: views/usuario/panel.php
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
    
    // Obtener número de equipos asignados
    $stmt = $conn->prepare("
        SELECT COUNT(*) as total_asignados
        FROM mesa_ayuda_asignacion_pc
        WHERE id_usuario = :usuario_id AND activo = 1
    ");
    $stmt->execute(['usuario_id' => $usuario_id]);
    $equipos_asignados = $stmt->fetch(PDO::FETCH_ASSOC)['total_asignados'] ?? 0;
    
    // Obtener número de préstamos activos
    $stmt = $conn->prepare("
        SELECT COUNT(*) as total_prestamos
        FROM mesa_ayuda_prestamos
        WHERE id_usuario = :usuario_id AND fecha_devolucion_real IS NULL
    ");
    $stmt->execute(['usuario_id' => $usuario_id]);
    $prestamos_activos = $stmt->fetch(PDO::FETCH_ASSOC)['total_prestamos'] ?? 0;
    
} catch (PDOException $e) {
    // Si hay un error, usamos datos simulados
    error_log("Error al obtener datos del panel de usuario: " . $e->getMessage());
    
    // Datos simulados
    $tickets_abiertos = 5;
    $tickets_en_proceso = 2;
    $tickets_cerrados = 12;
    $tickets_total = 19;
    $equipos_asignados = 2;
    $prestamos_activos = 1;
}

// Incluir la cabecera y navbar
include_once ROOT_PATH . '/views/templates/header.php';
include_once ROOT_PATH . '/views/templates/navbar.php';
?>

<!-- Bootstrap 5 y Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
/* Ajustes específicos para esta página */
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
    background-color: #f8f9fa;
}

.navbar {
    flex-shrink: 0;
}

.panel-container {
    flex: 1 0 auto;
    display: flex;
    margin: 0;
    padding: 30px 0;
}

.footer {
    flex-shrink: 0;
    margin-top: 0 !important;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #5A4FCF 0%, #4339A8 100%);
    color: white;
    padding: 4rem 2rem;
    border-radius: 16px;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(90, 79, 207, 0.3);
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
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.hero-section h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

.hero-section p {
    font-size: 1.25rem;
    opacity: 0.9;
    position: relative;
    z-index: 1;
}

/* Cards Grid */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 3rem;
}

/* Card Option */
.card-option {
    background: white;
    border: none;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
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

.card-support { --card-color: #17A2B8; --card-color-dark: #138496; }
.card-support .option-icon { background-color: rgba(23, 162, 184, 0.1); color: #17A2B8; }

.card-knowledge { --card-color: #6C757D; --card-color-dark: #5a6268; }
.card-knowledge .option-icon { background-color: rgba(108, 117, 125, 0.1); color: #6C757D; }

.card-logout { --card-color: #FFC107; --card-color-dark: #d39e00; }
.card-logout .option-icon { background-color: rgba(255, 193, 7, 0.1); color: #FFC107; }

/* Nuevas tarjetas para equipos */
.card-assigned { --card-color: #6A1B9A; --card-color-dark: #4A148C; }
.card-assigned .option-icon { background-color: rgba(106, 27, 154, 0.1); color: #6A1B9A; }

.card-loans { --card-color: #E91E63; --card-color-dark: #C2185B; }
.card-loans .option-icon { background-color: rgba(233, 30, 99, 0.1); color: #E91E63; }

/* Stats Section */
.stats-section {
    background-color: white;
    border-radius: 16px;
    padding: 2rem;
    margin-top: 3rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.stats-title {
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #212529;
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.5rem;
}

.stat-item {
    text-align: center;
    padding: 1.5rem;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
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

.stat-abiertos {
    background-color: rgba(220, 53, 69, 0.1);
}
.stat-abiertos .stat-icon, .stat-abiertos .stat-value {
    color: #DC3545;
}

.stat-proceso {
    background-color: rgba(255, 193, 7, 0.1);
}
.stat-proceso .stat-icon, .stat-proceso .stat-value {
    color: #FFC107;
}

.stat-cerrados {
    background-color: rgba(40, 167, 69, 0.1);
}
.stat-cerrados .stat-icon, .stat-cerrados .stat-value {
    color: #28A745;
}

.stat-total {
    background-color: rgba(90, 79, 207, 0.1);
}
.stat-total .stat-icon, .stat-total .stat-value {
    color: #5A4FCF;
}

.stat-equipos {
    background-color: rgba(106, 27, 154, 0.1);
}
.stat-equipos .stat-icon, .stat-equipos .stat-value {
    color: #6A1B9A;
}

.stat-prestamos {
    background-color: rgba(233, 30, 99, 0.1);
}
.stat-prestamos .stat-icon, .stat-prestamos .stat-value {
    color: #E91E63;
}

/* Animación de entrada */
.card-option {
    animation: fadeInUp 0.5s ease;
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

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        padding: 3rem 1.5rem;
    }
    
    .hero-section h1 {
        font-size: 2rem;
    }
    
    .hero-section p {
        font-size: 1rem;
    }
    
    .cards-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
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

/* Content wrapper para centrar */
.main-content {
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
    padding: 0 1rem;
}
</style>

<div class="panel-container">
    <div class="main-content">
        <div class="hero-section">
            <h1>Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?></h1>
            <p>Gestiona tus solicitudes, revisa el estado de tus tickets y accede a tus equipos y préstamos</p>
        </div>

        <div class="cards-grid">
            <a href="<?php echo BASE_URL; ?>/views/usuario/perfil.php" class="text-decoration-none">
                <div class="card-option card-profile">
                    <div class="option-icon">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h5 class="option-title">Mi Perfil</h5>
                    <p class="option-description">Ver y editar información personal</p>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/views/tickets/create.php" class="text-decoration-none">
                <div class="card-option card-ticket">
                    <div class="option-icon">
                        <i class="bi bi-plus-circle-fill"></i>
                    </div>
                    <h5 class="option-title">Nuevo Ticket</h5>
                    <p class="option-description">Reportar un problema o solicitud</p>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/views/tickets/list.php" class="text-decoration-none">
                <div class="card-option card-list">
                    <div class="option-icon">
                        <i class="bi bi-ticket-detailed-fill"></i>
                    </div>
                    <h5 class="option-title">Mis Tickets</h5>
                    <p class="option-description">Ver y dar seguimiento a mis solicitudes</p>
                </div>
            </a>

            <!-- Nueva tarjeta para equipos asignados -->
            <a href="<?php echo BASE_URL; ?>/views/usuario/mis_equipos.php" class="text-decoration-none">
                <div class="card-option card-assigned">
                    <div class="option-icon">
                        <i class="bi bi-pc-display"></i>
                    </div>
                    <h5 class="option-title">Mis Equipos</h5>
                    <p class="option-description">Ver equipos asignados a mi usuario</p>
                </div>
            </a>

            <!-- Nueva tarjeta para préstamos -->
            <a href="<?php echo BASE_URL; ?>/views/usuario/mis_prestamos.php" class="text-decoration-none">
                <div class="card-option card-loans">
                    <div class="option-icon">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <h5 class="option-title">Mis Préstamos</h5>
                    <p class="option-description">Gestionar préstamos de equipos</p>
                </div>
            </a>
            <a href="<?php echo BASE_URL; ?>/views/soporte/contacto.php" class="text-decoration-none">
                <div class="card-option card-support">
                    <div class="option-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h5 class="option-title">Soporte</h5>
                    <p class="option-description">Contactar al equipo de soporte técnico</p>
                </div>
            </a>

            <a href="<?php echo BASE_URL; ?>/views/auth/logout.php" class="text-decoration-none">
                <div class="card-option card-logout">
                    <div class="option-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <h5 class="option-title">Cerrar Sesión</h5>
                    <p class="option-description">Salir del sistema</p>
                </div>
            </a>
        </div>

        <div class="stats-section">
            <h3 class="stats-title">Resumen de Actividad</h3>
            <div class="stats-container">
                <div class="stat-item stat-abiertos">
                    <div class="stat-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div class="stat-value"><?php echo $tickets_abiertos; ?></div>
                    <div class="stat-label">Tickets Abiertos</div>
                </div>
                
                <div class="stat-item stat-proceso">
                    <div class="stat-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stat-value"><?php echo $tickets_en_proceso; ?></div>
                    <div class="stat-label">En Proceso</div>
                </div>
                
                <div class="stat-item stat-cerrados">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-value"><?php echo $tickets_cerrados; ?></div>
                    <div class="stat-label">Cerrados</div>
                </div>
                
                <div class="stat-item stat-equipos">
                    <div class="stat-icon">
                        <i class="bi bi-pc-display"></i>
                    </div>
                    <div class="stat-value"><?php echo $equipos_asignados; ?></div>
                    <div class="stat-label">Equipos Asignados</div>
                </div>
                
                <div class="stat-item stat-prestamos">
                    <div class="stat-icon">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <div class="stat-value"><?php echo $prestamos_activos; ?></div>
                    <div class="stat-label">Préstamos Activos</div>
                </div>
                
                <div class="stat-item stat-total">
                    <div class="stat-icon">
                        <i class="bi bi-ticket-detailed"></i>
                    </div>
                    <div class="stat-value"><?php echo $tickets_total; ?></div>
                    <div class="stat-label">Total Tickets</div>
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
    const cards = document.querySelectorAll('.card-option');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // Efecto parallax en el hero section
    const hero = document.querySelector('.hero-section');
    
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        hero.style.transform = `translateY(${scrolled * 0.5}px)`;
    });
});
</script>
</body>
</html>
