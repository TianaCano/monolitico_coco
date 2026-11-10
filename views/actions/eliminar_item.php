<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sprint_id = isset($_GET['sprint_id']) ? (int)$_GET['sprint_id'] : 0;

if ($id > 0) {
    $controller = ControllersConfig::get_retrospectives_controller();
    $result = $controller->item_delete($id);

    if ($result) {
        header("Location: ../detalle_retrospectiva.php?id={$sprint_id}&msg=Ítem eliminado correctamente");
    } else {
        header("Location: ../detalle_retrospectiva.php?id={$sprint_id}&error=No se pudo eliminar el ítem");
    }
    exit;
}

header('Location: ../retrospectivas.php');
exit;