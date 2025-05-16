<?php
// Iniciamos sesión antes de cualquier salida HTML
session_start();

require_once __DIR__ . '/../../config/config.php';
global $conn;

$mensaje = "";
$tipo = "";

if (isset($_GET['registro']) && $_GET['registro'] === 'exito') {
    $mensaje = "¡Tu cuenta fue creada correctamente!";
    $tipo = "success";
} elseif (isset($_GET['logout']) && $_GET['logout'] === 'ok') {
    $mensaje = "Sesión cerrada correctamente.";
    $tipo = "info";
} elseif (isset($_GET['error']) && $_GET['error'] === 'denegado') {
    $mensaje = "Debes iniciar sesión para acceder.";
    $tipo = "warning";
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $contrasena = $_POST['contrasena'];

    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = :usuario1 OR usuario = :usuario2 LIMIT 1");
        $stmt->execute([
            'usuario1' => $usuario,
            'usuario2' => $usuario
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($contrasena, $user['contrasena'])) {
            // No necesitamos iniciar sesión aquí, ya lo hicimos al principio
            // Pero sí regeneramos el ID por seguridad
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $user['id_usuario'];
            $_SESSION['usuario_nombre'] = $user['nombre'];
            $_SESSION['usuario_rol'] = $user['rol'];

            // Redireccionamos según el rol
            if ($user['rol'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../usuario/usuarios.php");
            }
            exit();
        } else {
            $error = "Las credenciales proporcionadas no son válidas.";
        }
    } catch (PDOException $e) {
        error_log("Error DB login: " . $e->getMessage());
        $error = "Ha ocurrido un error. Intenta nuevamente.";
    }
}

// Incluimos los archivos de plantilla solo después de procesar la lógica
include_once __DIR__ . '/../templates/header.php';
include_once __DIR__ . '/../templates/navbar.php';
?>

<!-- Bootstrap 5 y Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<link rel="stylesheet" href="../../assets/css/login.css">

<style>
/* Ajustes específicos para esta página */
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
    padding-top: 80px; /* Espacio para navbar fixed */
}

.navbar {
    flex-shrink: 0;
}

.login-container {
    flex: 1 0 auto;
    display: flex;
    margin: 0;
    padding: 0;
}

.footer {
    flex-shrink: 0;
    margin-top: 0 !important;
}

/* Navbar custom */
.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

.navbar-toggler {
    border: none;
}
</style>

<div class="login-container">
    <div class="row g-0 w-100 flex-grow-1">
        <!-- Panel izquierdo -->
        <div class="col-lg-5 left-panel d-none d-lg-flex">
            <div class="left-content">
                <div class="shield-icon">
                    <i class="bi bi-headset"></i>
                </div>
                
                <h2>Sistema de Mesa de Ayuda</h2>
                <p>Gestiona solicitudes, registra usuarios y accede al panel de administración</p>
                
                <div class="features">
                    <div class="feature">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Gestión de tickets eficiente</span>
                    </div>
                    <div class="feature">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Dashboard administrativo completo</span>
                    </div>
                    <div class="feature">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Reportes y estadísticas en tiempo real</span>
                    </div>
                </div>
                
                <div class="register-link">
                    <p>¿No tienes una cuenta?</p>
                    <a href="registro.php" class="btn btn-outline-light">Registrarse</a>
                </div>
            </div>
        </div>

        <!-- Panel derecho - Formulario -->
        <div class="col-lg-7 right-panel">
            <div class="form-container">
                <div class="form-header">
                    <h1>Iniciar Sesión</h1>
                    <p>Ingresa tus credenciales para acceder al sistema</p>
                </div>

                <?php if ($mensaje): ?>
                    <div class="alert alert-<?php echo $tipo; ?> alert-modern" role="alert">
                        <i class="bi bi-<?php echo $tipo === 'success' ? 'check-circle' : ($tipo === 'info' ? 'info-circle' : 'exclamation-triangle'); ?>-fill me-2"></i>
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-modern" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="loginForm">
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario o Correo" required autofocus>
                        <label for="usuario"><i class="bi bi-person-fill me-2"></i>Usuario o Correo</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña" required>
                        <label for="contrasena"><i class="bi bi-lock-fill me-2"></i>Contraseña</label>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="mostrarContrasena">
                        <label class="form-check-label" for="mostrarContrasena">
                            Mostrar contraseña
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                        </button>
                    </div>

                    <div class="divider">
                        <span>o</span>
                    </div>

                    <div class="text-center">
                        <p class="mb-3">¿No tienes una cuenta? <a href="registro.php" class="link-primary">Registrarse aquí</a></p>
                        <p class="text-muted small">
                            <i class="bi bi-headset me-1"></i>¿Problemas para acceder? 
                            <a href="soporte.php" class="link-primary">Contacta soporte</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar contraseña
    const mostrarContrasena = document.getElementById('mostrarContrasena');
    const passwordField = document.getElementById('contrasena');

    if (mostrarContrasena) {
        mostrarContrasena.addEventListener('change', function() {
            passwordField.type = this.checked ? 'text' : 'password';
        });
    }

    // Validación del formulario
    const form = document.getElementById('loginForm');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const usuario = document.getElementById('usuario').value;
        const contrasena = document.getElementById('contrasena').value;
        
        if (!usuario || !contrasena) {
            event.stopPropagation();
            form.classList.add('was-validated');
            return;
        }
        
        // Cambiar el botón a estado de carga
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Ingresando...';
        
        // Enviar el formulario
        form.submit();
    });

    // Auto-ocultar alertas después de 10 segundos
    setTimeout(() => {
        const alertas = document.querySelectorAll('.alert');
        alertas.forEach(alerta => {
            const bsAlert = new bootstrap.Alert(alerta);
            bsAlert.close();
        });
    }, 10000);
});
</script>
</body>
</html>