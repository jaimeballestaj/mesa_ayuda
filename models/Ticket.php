<?php
// Ruta: /mesa_ayuda/models/Ticket.php

class Ticket {

    private $db;

    public function __construct() {
        require_once dirname(__DIR__) . '/config/database.php';
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db->connect_error) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    public function obtenerEstadisticasPorUsuario($id_usuario) {
        $result = [
            'abiertos' => 0,
            'en_proceso' => 0,
            'cerrados' => 0,
            'total' => 0
        ];

        $query = "SELECT estado, COUNT(*) as cantidad FROM mesa_ayuda.tickets WHERE id_usuario = ? GROUP BY estado";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $estado = strtolower($row['estado']);
            if ($estado === 'abierto') $result['abiertos'] = $row['cantidad'];
            elseif ($estado === 'en proceso') $result['en_proceso'] = $row['cantidad'];
            elseif ($estado === 'cerrado') $result['cerrados'] = $row['cantidad'];
            $result['total'] += $row['cantidad'];
        }

        return $result;
    }

    public function obtenerTicketsRecientesPorUsuario($id_usuario, $limite = 3) {
        $tickets = [];
        $query = "SELECT id_ticket AS id, titulo AS asunto, fecha_creacion, id_categoria, id_estado
                  FROM mesa_ayuda.tickets
                  WHERE id_usuario = ?
                  ORDER BY fecha_creacion DESC
                  LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id_usuario, $limite);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $row['categoria'] = 'General'; // Sustituye luego por join a la tabla categorias
            $row['estado'] = 'Pendiente'; // Igual, hacer join a estados
            $tickets[] = $row;
        }

        return $tickets;
    }
}
