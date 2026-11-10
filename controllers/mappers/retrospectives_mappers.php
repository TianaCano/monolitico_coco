<?php

namespace app\controllers\mappers;

use app\models\entities\RetroItem;

/**
 * Realiza cálculos y transformaciones para preparar los datos de la vista.
 */
class RetrospectivesControllerMapper {
    /**
     * Calcula el estado del sprint según el cumplimiento de sus acciones:
     * - Todas cumplidas = 'Completadas'
     * - Algunas cumplidas = 'En progreso'
     * - Ninguna o sin acciones = 'Pendientes'
     */
    public function calculate_status(array $items): string {
        $actions = array_filter($items, function(RetroItem $item) {
            return $item->get_category() === 'accion';
        });

        if (empty($actions)) {
            return 'Pendientes';
        }

        $totalActions = count($actions);
        $doneCount = 0;

        foreach ($actions as $action) {
            if ($action->get_done() === true) {
                $doneCount++;
            }
        }

        if ($doneCount === $totalActions) {
            return 'Completadas';
        }

        if ($doneCount > 0) {
            return 'En progreso';
        }

        return 'Pendientes';
    }

    /**
     * Cuenta la cantidad de ítems por categoría ('logro', 'impedimento', 'accion').
     */
    public function count_categories(array $items): array {
        $counts = [
            'logro' => 0,
            'impedimento' => 0,
            'accion' => 0
        ];

        foreach ($items as $item) {
            $cat = $item->get_category();
            if (isset($counts[$cat])) {
                $counts[$cat]++;
            }
        }

        return $counts;
    }
}