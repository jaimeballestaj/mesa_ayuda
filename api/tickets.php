<?php
// api/tickets.php - API REST para gestionar tickets
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();

// Autenticación mínima (ejemplo: requiere sesión activa)
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Obtener ticket por ID
            $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id_ticket = :id");
            $stmt->execute(['id' => (int)$_GET['id']]);
            $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($ticket) {
                echo json_encode($ticket);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Ticket no encontrado']);
            }
        } else {
            // Obtener todos los tickets
            $stmt = $pdo->query("SELECT * FROM tickets");
            $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($tickets);
        }
        break;

    case 'POST':
        // Crear nuevo ticket (requiere título y descripción)
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['titulo'], $input['descripcion'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan parámetros requeridos']);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO tickets (id_usuario, id_categoria, id_prioridad, id_estado, titulo, descripcion, fecha_creacion) VALUES (:id_usuario, :id_categoria, :id_prioridad, :id_estado, :titulo, :descripcion, NOW())");
        $stmt->execute([
            'id_usuario' => $_SESSION['usuario']['id_usuario'],
            'id_categoria' => $input['id_categoria'] ?? 1,
            'id_prioridad' => $input['id_prioridad'] ?? 1,
            'id_estado' => 1, // Abierto por defecto
            'titulo' => sanitize($input['titulo']),
            'descripcion' => sanitize($input['descripcion'])
        ]);
        echo json_encode(['success' => true, 'id_ticket' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        parse_str(file_get_contents('php://input'), $put_vars);
        if (!isset($put_vars['id'], $put_vars['estado'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan parámetros requeridos']);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE tickets SET id_estado = :estado, fecha_actualizacion = NOW() WHERE id_ticket = :id");
        $stmt->execute([
            'estado' => (int)$put_vars['estado'],
            'id' => (int)$put_vars['id']
        ]);
        echo json_encode(['success' => true]);
        break;

    case 'DELETE':
        parse_str(file_get_contents('php://input'), $delete_vars);
        if (!isset($delete_vars['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Falta parámetro id']);
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM tickets WHERE id_ticket = :id");
        $stmt->execute(['id' => (int)$delete_vars['id']]);
        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}
?>
