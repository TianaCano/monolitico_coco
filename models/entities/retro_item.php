<?php

namespace app\models\entities;

/**
 * Entidad que representa un ítem de retrospectiva (logro, impedimento o acción).
 */
class RetroItem {
    private int $id;
    private int $sprint_id;
    private string $category;
    private string $description;
    private ?bool $done;
    private ?string $review_date;

    public function __construct(
        int $id,
        int $sprint_id,
        string $category,
        string $description,
        ?bool $done = null,
        ?string $review_date = null
    ) {
        $this->id = $id;
        $this->sprint_id = $sprint_id;
        $this->category = $category;
        $this->description = $description;
        $this->done = $done;
        $this->review_date = $review_date;
    }

    public function get_id(): int {
        return $this->id;
    }

    public function get_sprint_id(): int {
        return $this->sprint_id;
    }

    public function get_category(): string {
        return $this->category;
    }

    public function get_description(): string {
        return $this->description;
    }

    public function get_done(): ?bool {
        return $this->done;
    }

    public function get_review_date(): ?string {
        return $this->review_date;
    }
}