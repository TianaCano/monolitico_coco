<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    $controller = ControllersConfig::get_retrospectives_controller();
    $result = $controller->sprint_create($name, $start_date, $end_date);

    if ($result) {
        header('Location: ../retrospectivas.php?msg=Retrospectiva creada exitosamente');
    } else {
        header('Location: ../retrospectivas.php?error=Datos inválidos o fecha de fin menor a fecha de inicio');
    }
    exit;
}

header('Location: ../retrospectivas.php');
exit;