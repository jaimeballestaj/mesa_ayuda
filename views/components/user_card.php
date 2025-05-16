<?php
// Archivo: views/components/user_card.php

use models\Usuario;

$usuarios = (new Usuario())->listarUltimos(5);

if (empty($usuarios)) {
    echo '<p>No hay usuarios registrados recientemente.</p>';
    return;
}
?>

<div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Usuarios recientes</h5>
    <div>
        <a href="../admin/usuarios.php" class="btn btn-sm btn-outline-primary">Ver todos</a>
        <button onclick="exportTableToCSV('usuarios.csv')" class="btn btn-sm btn-outline-success">Exportar</button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped" id="tablaUsuarios">
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Fecha Registro</th></tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id']) ?></td>
                    <td><?= htmlspecialchars($u['nombre']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['rol']) ?></td>
                    <td><?= htmlspecialchars($u['fecha_registro']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function exportTableToCSV(filename) {
    const csv = [];
    const rows = document.querySelectorAll("#tablaUsuarios tr");
    for (const row of rows) {
        const cols = row.querySelectorAll("td, th");
        const rowData = Array.from(cols).map(td => '"' + td.innerText + '"');
        csv.push(rowData.join(","));
    }
    const csvContent = csv.join("\n");
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
}
</script>