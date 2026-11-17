<?php

namespace app\models\database;

interface RetroDbInterface {
    public function get_connection(): \mysqli;
    public function close_connection(): void;
}