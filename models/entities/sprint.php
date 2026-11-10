<?php

namespace app\models\entities;

/**
 * Entidad que representa un Sprint en el sistema.
 */
class Sprint {
    private int $id;
    private string $name;
    private string $start_date;
    private string $end_date;

    public function __construct(int $id, string $name, string $start_date, string $end_date) {
        $this->id = $id;
        $this->name = $name;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function get_id(): int {
        return $this->id;
    }

    public function get_name(): string {
        return $this->name;
    }

    public function get_start_date(): string {
        return $this->start_date;
    }

    public function get_end_date(): string {
        return $this->end_date;
    }
}