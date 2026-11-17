<?php

namespace app\models\config;

use app\models\database\RetroDb;
use app\models\mappers\RetrospectivesModelMapper;
use app\models\repositories\RetrospectivesRepository;
use app\models\repositories\RetrospectivesRepositoryInterface;

class ModelsConfig {
    public static function get_retrospectives_repository(): RetrospectivesRepositoryInterface {
        $db = new RetroDb();
        $mapper = new RetrospectivesModelMapper();
        return new RetrospectivesRepository($db, $mapper);
    }
}