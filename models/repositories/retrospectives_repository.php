<?php

namespace app\models\repositories;

use app\models\database\RetroDbInterface;
use app\models\mappers\RetrospectivesModelMapper;
use app\models\entities\Sprint;
use app\models\entities\RetroItem;

/**
 * Implementación del repositorio usando sentencias preparadas de MySQLi.
 */
class RetrospectivesRepository implements RetrospectivesRepositoryInterface {
    private RetroDbInterface $db;
    private RetrospectivesModelMapper $mapper;

    public function __construct(RetroDbInterface $db, RetrospectivesModelMapper $mapper) {
        $this->db = $db;
        $this->mapper = $mapper;
    }

    public function get_sprints(): array {
        $conn = $this->db->get_connection();
        $sql = "SELECT id, nombre, fecha_inicio, fecha_fin FROM sprints ORDER BY fecha_inicio DESC, id DESC";
        $result = $conn->query($sql);

        $sprints = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $sprints[] = $this->mapper->to_sprint_entity($row);
            }
        }
        return $sprints;
    }

    public function get_sprint(int $id): ?Sprint {
        $conn = $this->db->get_connection();
        $stmt = $conn->prepare("SELECT id, nombre, fecha_inicio, fecha_fin FROM sprints WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $this->mapper->to_sprint_entity($row);
        }
        return null;
    }

    public function sprint_create(Sprint $sprint): bool {
        $conn = $this->db->get_connection();
        $stmt = $conn->prepare("INSERT INTO sprints (nombre, fecha_inicio, fecha_fin, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $name = $sprint->get_name();
        $startDate = $sprint->get_start_date();
        $endDate = $sprint->get_end_date();
        $stmt->bind_param("sss", $name, $startDate, $endDate);
        return $stmt->execute();
    }

    public function sprint_update(Sprint $sprint): bool {
        $conn = $this->db->get_connection();
        $stmt = $conn->prepare("UPDATE sprints SET nombre = ?, fecha_inicio = ?, fecha_fin = ?, updated_at = NOW() WHERE id = ?");
        $name = $sprint->get_name();
        $startDate = $sprint->get_start_date();
        $endDate = $sprint->get_end_date();
        $id = $sprint->get_id();
        $stmt->bind_param("sssi", $name, $startDate, $endDate, $id);
        return $stmt->execute();
    }

    public function sprint_delete(int $id): bool {
        $conn = $this->db->get_connection();
        $stmt = $conn->prepare("DELETE FROM sprints WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function get_previous_sprint(int $current_sprint_id): ?Sprint {
        $current = $this->get_sprint($current_sprint_id);
        if (!$current) {
            return null;
        }

        $conn = $this->db->get_connection();
        $stmt = $conn->prepare(
            "SELECT id, nombre, fecha_inicio, fecha_fin 
             FROM sprints 
             WHERE fecha_inicio < ? 
             ORDER BY fecha_inicio DESC, id DESC 
             LIMIT 1"
        );
        $currentStartDate = $current->get_start_date();
        $stmt->bind_param("s", $currentStartDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $this->mapper->to_sprint_entity($row);
        }
        return null;
    }

    public function get_items_by_sprint(int $sprint_id): array {
        $conn = $this->db->get_connection();
        $stmt = $conn->prepare("SELECT id, sprint_id, categoria, descripcion, cumplida, fecha_revision FROM retro_items WHERE sprint_id = ? ORDER BY id ASC");
        $stmt->bind_param("i", $sprint_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $this->mapper->to_retro_item_entity($row);
        }
        return $items;
    }

    public function item_create(RetroItem $item): bool {
        $conn = $this->db->get_connection();
        $stmt = $conn->prepare("INSERT INTO retro_items (sprint_id, categoria, descripcion, cumplida, fecha_revision, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        
        $sprintId = $item->get_sprint_id();
        $category = $item->get_category();
        $description = $item->get_description();
        $done = $item->get_done() !== null ? (int)$item->get_done() : null;
        $reviewDate = $item->get_review_date();

        $stmt->bind_param("issis", $sprintId, $category, $description, $done, $reviewDate);
        return $stmt->execute();
    }

    public function item_delete(int $id): bool {
        $conn = $this->db->get_connection();
        $stmt = $conn->prepare("DELETE FROM retro_items WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function item_mark_done(int $id, bool $done): bool {
        $conn = $this->db->get_connection();
        $stmt = $conn->prepare("UPDATE retro_items SET cumplida = ?, updated_at = NOW() WHERE id = ?");
        $doneInt = $done ? 1 : 0;
        $stmt->bind_param("ii", $doneInt, $id);
        return $stmt->execute();
    }
}