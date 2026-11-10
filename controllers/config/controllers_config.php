<?php

namespace app\controllers\config;

use app\models\config\ModelsConfig;
use app\controllers\mappers\RetrospectivesControllerMapper;
use app\controllers\retrospectives\RetrospectivesController;
use app\controllers\retrospectives\RetrospectivesControllerInterface;

/**
 * Ensamblador de la capa de controladores: inyecta el repositorio y mappers.
 */
class ControllersConfig {
    public static function get_retrospectives_controller(): RetrospectivesControllerInterface {
        $repository = ModelsConfig::get_retrospectives_repository();
        $mapper = new RetrospectivesControllerMapper();
        return new RetrospectivesController($repository, $mapper);
    }
}