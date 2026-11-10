<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = $_POST['name'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    $controller = ControllersConfig::get_retrospectives_controller();
    $result = $controller->sprint_update($id, $name, $start_date, $end_date);

    if ($result) {
        header('Location: ../retrospectivas.php?msg=Retrospectiva actualizada exitosamente');
    } else {
        header('Location: ../retrospectivas.php?error=No se pudo actualizar la retrospectiva');
    }
    exit;
}

header('Location: ../retrospectivas.php');
exit;