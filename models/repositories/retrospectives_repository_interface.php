<?php

namespace app\models\repositories;

use app\models\entities\Sprint;
use app\models\entities\RetroItem;

/**
 * Contrato que define las operaciones de persistencia para sprints e ítems.
 */
interface RetrospectivesRepositoryInterface {
    public function get_sprints(): array;
    public function get_sprint(int $id): ?Sprint;
    public function sprint_create(Sprint $sprint): bool;
    public function sprint_update(Sprint $sprint): bool;
    public function sprint_delete(int $id): bool;
    public function get_previous_sprint(int $current_sprint_id): ?Sprint;

    public function get_items_by_sprint(int $sprint_id): array;
    public function item_create(RetroItem $item): bool;
    public function item_delete(int $id): bool;
    public function item_mark_done(int $id, bool $done): bool;
}