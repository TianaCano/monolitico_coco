<?php

namespace app\models\mappers;

use app\models\entities\Sprint;
use app\models\entities\RetroItem;

/**
 * Traduce registros de la base de datos (en español) a Entidades PHP (en inglés).
 */
class RetrospectivesModelMapper {
    /**
     * Convierte una fila de la tabla sprints a un objeto Sprint.
     */
    public function to_sprint_entity(array $row): Sprint {
        return new Sprint(
            (int)$row['id'],
            (string)$row['nombre'],
            (string)$row['fecha_inicio'],
            (string)$row['fecha_fin']
        );
    }

    /**
     * Convierte una fila de la tabla retro_items a un objeto RetroItem.
     */
    public function to_retro_item_entity(array $row): RetroItem {
        $done = $row['cumplida'] !== null ? (bool)$row['cumplida'] : null;
        return new RetroItem(
            (int)$row['id'],
            (int)$row['sprint_id'],
            (string)$row['categoria'],
            (string)$row['descripcion'],
            $done,
            $row['fecha_revision'] ? (string)$row['fecha_revision'] : null
        );
    }
}