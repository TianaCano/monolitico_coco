<?php

namespace app\models\database;

/**
 * Manejador de la conexión a MySQL usando la extensión mysqli.
 */
class RetroDb implements RetroDbInterface {
    private string $host = 'localhost';
    private string $user = 'root';
    private string $password = '';
    private string $database = 'registro_retro_db';
    private ?\mysqli $connection = null;

    public function get_connection(): \mysqli {
        if ($this->connection === null) {
            $this->connection = new \mysqli(
                $this->host,
                $this->user,
                $this->password,
                $this->database
            );

            if ($this->connection->connect_error) {
                die('Error de conexión a la base de datos: ' . $this->connection->connect_error);
            }

            $this->connection->set_charset('utf8mb4');
        }

        return $this->connection;
    }

    public function close_connection(): void {
        if ($this->connection !== null) {
            $this->connection->close();
            $this->connection = null;
        }
    }
}