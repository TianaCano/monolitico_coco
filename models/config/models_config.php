<?php

namespace app\models\config;

use app\models\database\RetroDb;
use app\models\mappers\RetrospectivesModelMapper;
use app\models\repositories\RetrospectivesRepository;
use app\models\repositories\RetrospectivesRepositoryInterface;

/**
 * Ensamblador de la capa de modelos: inicializa DB, mappers y repositorio.
 */
class ModelsConfig {
    public static function get_retrospectives_repository(): RetrospectivesRepositoryInterface {
        $db = new RetroDb();
        $mapper = new RetrospectivesModelMapper();
        return new RetrospectivesRepository($db, $mapper);
    }
}