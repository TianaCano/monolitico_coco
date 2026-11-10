<?php

// 1. Entidades
require_once __DIR__ . '/models/entities/sprint.php';
require_once __DIR__ . '/models/entities/retro_item.php';

// 2. Base de Datos
require_once __DIR__ . '/models/database/retro_db_interface.php';
require_once __DIR__ . '/models/database/retro_db.php';

// 3. Mappers del Modelo
require_once __DIR__ . '/models/mappers/retrospectives_mappers.php';

// 4. Repositorios
require_once __DIR__ . '/models/repositories/retrospectives_repository_interface.php';
require_once __DIR__ . '/models/repositories/retrospectives_repository.php';

// 5. Configuración de Modelos
require_once __DIR__ . '/models/config/models_config.php';

// 6. Mappers del Controlador
require_once __DIR__ . '/controllers/mappers/retrospectives_mappers.php';

// 7. Controladores
require_once __DIR__ . '/controllers/retrospectives/retrospectives_controller_interface.php';
require_once __DIR__ . '/controllers/retrospectives/retrospectives_controller.php';

// 8. Configuración de Controladores
require_once __DIR__ . '/controllers/config/controllers_config.php';