<?php

namespace app\controllers\retrospectives;

use app\models\entities\Sprint;

/**
 * Interfaz de la lógica de negocio expuesta por el controlador de retrospectivas.
 */
interface RetrospectivesControllerInterface {
    public function get_sprints(): array;
    public function get_sprint(int $id): ?Sprint;
    public function sprint_create(string $name, string $start_date, string $end_date): bool;
    public function sprint_update(int $id, string $name, string $start_date, string $end_date): bool;
    public function sprint_delete(int $id): bool;

    public function get_items_by_sprint(int $sprint_id): array;
    public function item_create(int $sprint_id, string $category, string $description, ?string $review_date = null): bool;
    public function item_delete(int $id): bool;
    public function item_mark_done(int $id, bool $done): bool;

    public function get_sprint_summary(int $sprint_id): array;
    public function get_previous_sprint(int $sprint_id): ?Sprint;
}