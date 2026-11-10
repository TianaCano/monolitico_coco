<?php

namespace app\models\database;

/**
 * Interfaz para el conector de base de datos MySQL.
 */
interface RetroDbInterface {
    public function get_connection(): \mysqli;
    public function close_connection(): void;
}