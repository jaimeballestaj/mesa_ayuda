<?php
require_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/../templates/header.php';
include_once __DIR__ . '/../templates/navbar.php';
?>

<!-- Bootstrap 5 y Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<?php
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $telefono = !empty($_POST['telefono']) ? trim($_POST['telefono']) : null;
    $usuario = trim($_POST['usuario']);
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    $id_departamento = $_POST['id_departamento'];
    $id_ubicacion = $_POST['id_ubicacion'];

    // Validaciones
    if ($contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden";
    } elseif (strlen($contrasena) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres";
    } else {
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Manejo de foto de perfil
        $foto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $foto_nombre = time() . "_" . basename($_FILES['foto']['name']);
            $ruta_destino = __DIR__ . '/../../uploads/' . $foto_nombre;
            
            if (!file_exists(__DIR__ . '/../../uploads/')) {
                mkdir(__DIR__ . '/../../uploads/', 0777, true);
            }
            
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
            $tipo_archivo = $_FILES['foto']['type'];
            
            if (in_array($tipo_archivo, $tipos_permitidos)) {
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
                    $foto = $foto_nombre;
                }
            } else {
                $error = "Formato de imagen no válido";
            }
        }

        if (empty($error)) {
            try {
                // Verificar si el usuario o correo ya existen
                $check_sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = ? OR correo = ?";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->execute([$usuario, $correo]);
                
                if ($check_stmt->fetchColumn() > 0) {
                    $error = "El usuario o correo ya están registrados";
                } else {
                    $sql = "INSERT INTO usuarios 
                            (nombre, apellido, correo, telefono, usuario, contrasena, foto, fecha_registro, id_departamento, id_ubicacion, activo)
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, 1)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        $nombre,
                        $apellido,
                        $correo,
                        $telefono,
                        $usuario,
                        $contrasena_hash,
                        $foto,
                        $id_departamento,
                        $id_ubicacion
                    ]);

                    $success = "Registro exitoso. Redirigiendo al login...";
                    header("Refresh: 2; URL=login.php?registro=exito");
                }
            } catch (PDOException $e) {
                $error = "Error al registrar: " . $e->getMessage();
            }
        }
    }
}

// Obtener departamentos y ubicaciones
$departamentos = [];
$ubicaciones = [];

try {
    $result_departamentos = $conn->query("SELECT id_departamento, nombre FROM departamentos WHERE activo = 1 ORDER BY nombre");
    $departamentos = $result_departamentos->fetchAll(PDO::FETCH_ASSOC);
    
    $result_ubicaciones = $conn->query("SELECT id_ubicacion, nombre FROM ubicaciones WHERE activo = 1 ORDER BY nombre");
    $ubicaciones = $result_ubicaciones->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al cargar datos: " . $e->getMessage();
}
?>

<link rel="stylesheet" href="../../assets/css/registro.css">

<style>
/* Ajustes específicos para esta página */
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
}

.navbar {
    flex-shrink: 0;
}

.registro-container {
    flex: 1 0 auto;
    display: flex;
    margin: 0;
    padding: 0;
}

.footer {
    flex-shrink: 0;
    margin-top: 0 !important;
}
</style>

<div class="registro-container">
    <div class="row g-0 w-100 flex-grow-1">
        <!-- Panel izquierdo -->
        <div class="col-lg-4 left-panel d-none d-lg-flex">
            <div class="left-content">
                <div class="shield-icon">
                    <i class="bi bi-shield-check-fill"></i>
                </div>
                
                <h2>Bienvenido al Sistema</h2>
                <p>Únete a nuestro equipo y accede a todas las herramientas de gestión empresarial.</p>
                
                <div class="features">
                    <div class="feature">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Gestión integral de recursos</span>
                    </div>
                    <div class="feature">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Colaboración en tiempo real</span>
                    </div>
                    <div class="feature">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Reportes y análisis avanzados</span>
                    </div>
                </div>
                
                <div class="login-link">
                    <p>¿Ya tienes una cuenta?</p>
                    <a href="login.php" class="btn btn-outline-light">Iniciar sesión</a>
                </div>
            </div>
        </div>

        <!-- Panel derecho - Formulario -->
        <div class="col-lg-8 right-panel">
            <div class="form-container">
                <div class="form-header">
                    <h1>Crear cuenta nueva</h1>
                    <p>Complete el formulario para registrarse</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <!-- Progress Tabs -->
                <div class="progress-container">
                    <div class="step active" data-step="0">
                        <i class="bi bi-person-fill"></i>
                        <span>Información Personal</span>
                    </div>
                    <div class="step" data-step="1">
                        <i class="bi bi-key-fill"></i>
                        <span>Credenciales</span>
                    </div>
                    <div class="step" data-step="2">
                        <i class="bi bi-building-fill"></i>
                        <span>Datos Laborales</span>
                    </div>
                    <div class="step" data-step="3">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Confirmación</span>
                    </div>
                </div>

                <form method="POST" action="registro.php" enctype="multipart/form-data" id="formRegistro">
                    <!-- Step 1: Información Personal -->
                    <div class="form-step active" data-step="0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                                    <label for="nombre"><i class="bi bi-person me-1"></i>Nombre *</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
                                    <label for="apellido"><i class="bi bi-person me-1"></i>Apellido *</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="correo" name="correo" placeholder="correo@ejemplo.com" required>
                                    <label for="correo"><i class="bi bi-envelope me-1"></i>Correo Electrónico *</label>
                                </div>
                                <small class="text-muted">Utilizaremos tu correo para notificaciones importantes</small>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="1234567890" pattern="[0-9]{10}">
                                    <label for="telefono"><i class="bi bi-telephone me-1"></i>Teléfono (Opcional)</label>
                                </div>
                            </div>
                        </div>
                        <div class="nav-buttons">
                            <button type="button" class="btn btn-primary btn-next">
                                Siguiente <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Credenciales -->
                    <div class="form-step" data-step="1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="usuario123" required minlength="4">
                                    <label for="usuario"><i class="bi bi-person-circle me-1"></i>Nombre de Usuario *</label>
                                </div>
                                <small class="text-muted">Mínimo 4 caracteres</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Foto de Perfil</label>
                                <div class="photo-upload">
                                    <div class="photo-preview" id="photoPreview">
                                        <i class="bi bi-camera"></i>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="document.getElementById('foto').click()">
                                        Seleccionar archivo
                                    </button>
                                    <span class="file-name" id="fileName">Sin archivos seleccionados</span>
                                    <input type="file" class="d-none" id="foto" name="foto" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña" required minlength="8">
                                    <label for="contrasena"><i class="bi bi-lock me-1"></i>Contraseña *</label>
                                </div>
                                <small class="text-muted">Mínimo 8 caracteres</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" placeholder="Confirmar" required>
                                    <label for="confirmar_contrasena"><i class="bi bi-lock-fill me-1"></i>Confirmar Contraseña *</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mostrarContrasenas">
                                    <label class="form-check-label" for="mostrarContrasenas">
                                        Mostrar contraseñas
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="nav-buttons">
                            <button type="button" class="btn btn-secondary btn-prev">
                                <i class="bi bi-arrow-left"></i> Anterior
                            </button>
                            <button type="button" class="btn btn-primary btn-next">
                                Siguiente <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Datos Laborales -->
                    <div class="form-step" data-step="2">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="id_departamento" id="id_departamento" class="form-select" required>
                                        <option value="">Seleccione un departamento...</option>
                                        <?php foreach ($departamentos as $depto): ?>
                                            <option value="<?php echo $depto['id_departamento']; ?>">
                                                <?php echo htmlspecialchars($depto['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="id_departamento"><i class="bi bi-diagram-3 me-1"></i>Departamento *</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="id_ubicacion" id="id_ubicacion" class="form-select" required>
                                        <option value="">Seleccione una ubicación...</option>
                                        <?php foreach ($ubicaciones as $ubic): ?>
                                            <option value="<?php echo $ubic['id_ubicacion']; ?>">
                                                <?php echo htmlspecialchars($ubic['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="id_ubicacion"><i class="bi bi-geo-alt me-1"></i>Ubicación *</label>
                                </div>
                            </div>
                        </div>
                        <div class="nav-buttons">
                            <button type="button" class="btn btn-secondary btn-prev">
                                <i class="bi bi-arrow-left"></i> Anterior
                            </button>
                            <button type="button" class="btn btn-primary btn-next">
                                Siguiente <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Confirmación -->
                    <div class="form-step" data-step="3">
                        <div class="confirmation">
                            <div class="text-center mb-4">
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                <h3>Revisa tu información</h3>
                                <p class="text-muted">Verifica que todos los datos sean correctos antes de enviar</p>
                            </div>
                            <div class="summary-card">
                                <div id="resumenRegistro"></div>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terminos" required>
                                <label class="form-check-label" for="terminos">
                                    Acepto los <a href="#">términos y condiciones</a> y la 
                                    <a href="#">política de privacidad</a>
                                </label>
                            </div>
                        </div>
                        <div class="nav-buttons">
                            <button type="button" class="btn btn-secondary btn-prev">
                                <i class="bi bi-arrow-left"></i> Anterior
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Completar registro
                            </button>
                        </div>
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
    console.log('DOM Cargado');
    
    // Obtener elementos
    const form = document.getElementById('formRegistro');
    const formSteps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.step');
    const nextButtons = document.querySelectorAll('.btn-next');
    const prevButtons = document.querySelectorAll('.btn-prev');
    let currentStep = 0;

    console.log('Elementos encontrados:', {
        form: form,
        formSteps: formSteps.length,
        progressSteps: progressSteps.length,
        nextButtons: nextButtons.length,
        prevButtons: prevButtons.length
    });

    // Función para actualizar los pasos
    function updateSteps() {
        console.log('Actualizando paso:', currentStep);
        
        // Actualizar pasos del formulario
        formSteps.forEach((step, index) => {
            if (index === currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        // Actualizar indicador de progreso
        progressSteps.forEach((step, index) => {
            if (index === currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        // Actualizar resumen en el último paso
        if (currentStep === 3) {
            updateSummary();
        }
    }

    // Navegación con botones siguiente
    nextButtons.forEach((button, idx) => {
        console.log('Agregando listener a botón siguiente', idx);
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Click en siguiente, paso actual:', currentStep);
            
            if (currentStep < formSteps.length - 1) {
                currentStep++;
                updateSteps();
            }
        });
    });

    // Navegación con botones anterior
    prevButtons.forEach((button, idx) => {
        console.log('Agregando listener a botón anterior', idx);
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Click en anterior, paso actual:', currentStep);
            
            if (currentStep > 0) {
                currentStep--;
                updateSteps();
            }
        });
    });

    // Click en tabs de progreso
    progressSteps.forEach((step, index) => {
        step.addEventListener('click', function() {
            console.log('Click en tab', index);
            currentStep = index;
            updateSteps();
        });
    });

    // Actualizar resumen
    function updateSummary() {
        const nombre = document.getElementById('nombre').value || '';
        const apellido = document.getElementById('apellido').value || '';
        const correo = document.getElementById('correo').value || '';
        const usuario = document.getElementById('usuario').value || '';
        const departamento = document.getElementById('id_departamento');
        const ubicacion = document.getElementById('id_ubicacion');
        
        const departamentoText = departamento && departamento.selectedIndex > 0 ? 
            departamento.options[departamento.selectedIndex].text : 'No seleccionado';
        const ubicacionText = ubicacion && ubicacion.selectedIndex > 0 ? 
            ubicacion.options[ubicacion.selectedIndex].text : 'No seleccionada';
        
        const summaryHtml = `
            <div class="row">
                <div class="col-6"><strong>Nombre:</strong></div>
                <div class="col-6">${nombre} ${apellido}</div>
                
                <div class="col-6"><strong>Correo:</strong></div>
                <div class="col-6">${correo}</div>
                
                <div class="col-6"><strong>Usuario:</strong></div>
                <div class="col-6">${usuario}</div>
                
                <div class="col-6"><strong>Departamento:</strong></div>
                <div class="col-6">${departamentoText}</div>
                
                <div class="col-6"><strong>Ubicación:</strong></div>
                <div class="col-6">${ubicacionText}</div>
            </div>
        `;
        
        const resumenElement = document.getElementById('resumenRegistro');
        if (resumenElement) {
            resumenElement.innerHTML = summaryHtml;
        }
    }

    // Preview de imagen
    const photoInput = document.getElementById('foto');
    const photoPreview = document.getElementById('photoPreview');
    const fileName = document.getElementById('fileName');

    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">`;
                }
                reader.readAsDataURL(file);
                fileName.textContent = file.name;
            }
        });
    }

    // Mostrar/ocultar contraseñas
    const showPasswordCheckbox = document.getElementById('mostrarContrasenas');
    const passwordField = document.getElementById('contrasena');
    const confirmPasswordField = document.getElementById('confirmar_contrasena');

    if (showPasswordCheckbox) {
        showPasswordCheckbox.addEventListener('change', function() {
            const type = this.checked ? 'text' : 'password';
            if (passwordField) passwordField.type = type;
            if (confirmPasswordField) confirmPasswordField.type = type;
        });
    }

    // Prevenir envío del formulario sin validación
    if (form) {
        form.addEventListener('submit', function(e) {
            const terminos = document.getElementById('terminos');
            if (terminos && !terminos.checked) {
                e.preventDefault();
                alert('Debes aceptar los términos y condiciones');
                return false;
            }
            // Si todo está correcto, el formulario se enviará normalmente
        });
    }

    // Inicializar
    updateSteps();
    console.log('Inicialización completa');
});
</script>
</body>
</html>