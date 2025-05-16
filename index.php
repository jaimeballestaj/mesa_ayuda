<?php include_once __DIR__ . '/views/templates/header.php'; ?>
<?php include_once __DIR__ . '/views/templates/navbar.php'; ?>

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

.index-container {
    flex: 1 0 auto;
    display: flex;
    align-items: center;
    padding: 2rem 0;
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
.card-login { --card-color: #5A4FCF; --card-color-dark: #4339A8; }
.card-login .option-icon { background-color: rgba(90, 79, 207, 0.1); color: #5A4FCF; }

.card-register { --card-color: #28A745; --card-color-dark: #208638; }
.card-register .option-icon { background-color: rgba(40, 167, 69, 0.1); color: #28A745; }

.card-admin { --card-color: #DC3545; --card-color-dark: #bd2130; }
.card-admin .option-icon { background-color: rgba(220, 53, 69, 0.1); color: #DC3545; }

.card-tickets { --card-color: #17A2B8; --card-color-dark: #138496; }
.card-tickets .option-icon { background-color: rgba(23, 162, 184, 0.1); color: #17A2B8; }

.card-equipos { --card-color: #6C757D; --card-color-dark: #5a6268; }
.card-equipos .option-icon { background-color: rgba(108, 117, 125, 0.1); color: #6C757D; }

.card-support { --card-color: #FFC107; --card-color-dark: #d39e00; }
.card-support .option-icon { background-color: rgba(255, 193, 7, 0.1); color: #FFC107; }

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

<div class="index-container">
    <div class="main-content">
        <div class="hero-section">
            <h1>Sistema de Mesa de Ayuda</h1>
            <p>Gestiona solicitudes, registra usuarios y accede al panel de administración</p>
        </div>

        <div class="cards-grid">
            <a href="views/auth/login.php" class="text-decoration-none">
                <div class="card-option card-login">
                    <div class="option-icon">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                    <h5 class="option-title">Iniciar Sesión</h5>
                    <p class="option-description">Accede con tu cuenta de usuario</p>
                </div>
            </a>

            <a href="views/auth/registro.php" class="text-decoration-none">
                <div class="card-option card-register">
                    <div class="option-icon">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h5 class="option-title">Registrarse</h5>
                    <p class="option-description">Crea una nueva cuenta</p>
                </div>
            </a>

            <a href="views/admin/login.php" class="text-decoration-none">
                <div class="card-option card-admin">
                    <div class="option-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h5 class="option-title">Administrador</h5>
                    <p class="option-description">Panel exclusivo para el administrador</p>
                </div>
            </a>

            <a href="views/tickets/index.php" class="text-decoration-none">
                <div class="card-option card-tickets">
                    <div class="option-icon">
                        <i class="bi bi-ticket-detailed-fill"></i>
                    </div>
                    <h5 class="option-title">Tickets</h5>
                    <p class="option-description">Consulta y gestiona tus tickets</p>
                </div>
            </a>

            <a href="views/equipos/index.php" class="text-decoration-none">
                <div class="card-option card-equipos">
                    <div class="option-icon">
                        <i class="bi bi-pc-display-horizontal"></i>
                    </div>
                    <h5 class="option-title">Equipos</h5>
                    <p class="option-description">Gestión de equipos registrados</p>
                </div>
            </a>

            <a href="#" class="text-decoration-none">
                <div class="card-option card-support">
                    <div class="option-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h5 class="option-title">Soporte</h5>
                    <p class="option-description">Contacta al soporte técnico</p>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/views/templates/footer.php'; ?>

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