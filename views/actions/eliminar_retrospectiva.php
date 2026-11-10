<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $controller = ControllersConfig::get_retrospectives_controller();
    $result = $controller->sprint_delete($id);

    if ($result) {
        header('Location: ../retrospectivas.php?msg=Retrospectiva eliminada correctamente');
    } else {
        header('Location: ../retrospectivas.php?error=Error al intentar eliminar la retrospectiva');
    }
    exit;
}

header('Location: ../retrospectivas.php');
exit;