<?php

namespace app\controllers\retrospectives;

use app\models\repositories\RetrospectivesRepositoryInterface;
use app\controllers\mappers\RetrospectivesControllerMapper;
use app\models\entities\Sprint;
use app\models\entities\RetroItem;

/**
 * Controlador principal que maneja las reglas de negocio y validaciones.
 */
class RetrospectivesController implements RetrospectivesControllerInterface {
    private RetrospectivesRepositoryInterface $repository;
    private RetrospectivesControllerMapper $mapper;

    public function __construct(
        RetrospectivesRepositoryInterface $repository,
        RetrospectivesControllerMapper $mapper
    ) {
        $this->repository = $repository;
        $this->mapper = $mapper;
    }

    public function get_sprints(): array {
        return $this->repository->get_sprints();
    }

    public function get_sprint(int $id): ?Sprint {
        if ($id <= 0) {
            return null;
        }
        return $this->repository->get_sprint($id);
    }

    public function sprint_create(string $name, string $start_date, string $end_date): bool {
        $name = trim($name);
        $start_date = trim($start_date);
        $end_date = trim($end_date);

        if (empty($name) || empty($start_date) || empty($end_date)) {
            return false;
        }

        if ($end_date < $start_date) {
            return false;
        }

        $sprint = new Sprint(0, $name, $start_date, $end_date);
        return $this->repository->sprint_create($sprint);
    }

    public function sprint_update(int $id, string $name, string $start_date, string $end_date): bool {
        if ($id <= 0) {
            return false;
        }

        $name = trim($name);
        $start_date = trim($start_date);
        $end_date = trim($end_date);

        if (empty($name) || empty($start_date) || empty($end_date)) {
            return false;
        }

        if ($end_date < $start_date) {
            return false;
        }

        $sprint = new Sprint($id, $name, $start_date, $end_date);
        return $this->repository->sprint_update($sprint);
    }

    public function sprint_delete(int $id): bool {
        if ($id <= 0) {
            return false;
        }
        return $this->repository->sprint_delete($id);
    }

    public function get_items_by_sprint(int $sprint_id): array {
        if ($sprint_id <= 0) {
            return [];
        }
        return $this->repository->get_items_by_sprint($sprint_id);
    }

    public function item_create(int $sprint_id, string $category, string $description, ?string $review_date = null): bool {
        $description = trim($description);
        $allowed = ['logro', 'impedimento', 'accion'];

        if ($sprint_id <= 0 || empty($description) || !in_array($category, $allowed, true)) {
            return false;
        }

        $done = ($category === 'accion') ? false : null;
        $review_date = !empty($review_date) ? trim($review_date) : null;

        $item = new RetroItem(0, $sprint_id, $category, $description, $done, $review_date);
        return $this->repository->item_create($item);
    }

    public function item_delete(int $id): bool {
        if ($id <= 0) {
            return false;
        }
        return $this->repository->item_delete($id);
    }

    public function item_mark_done(int $id, bool $done): bool {
        if ($id <= 0) {
            return false;
        }
        return $this->repository->item_mark_done($id, $done);
    }

    public function get_sprint_summary(int $sprint_id): array {
        $items = $this->get_items_by_sprint($sprint_id);
        $counts = $this->mapper->count_categories($items);
        $status = $this->mapper->calculate_status($items);

        return [
            'counts' => $counts,
            'status' => $status,
            'items' => $items
        ];
    }

    public function get_previous_sprint(int $sprint_id): ?Sprint {
        if ($sprint_id <= 0) {
            return null;
        }
        return $this->repository->get_previous_sprint($sprint_id);
    }
}