<?php
// Archivo: config/Database.php

class Database {
    private string $host = 'localhost';
    private string $dbname = 'mesa_ayuda';
    private string $username = 'root';
    private string $password = '';
    private ?PDO $conn = null;

    public function connect(): ?PDO {
        if ($this->conn !== null) {
            return $this->conn;
        }

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            Logger::error('DB Connection failed: ' . $e->getMessage());
            return null;
        }

        return $this->conn;
    }
}
