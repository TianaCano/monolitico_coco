<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sprint_id = isset($_GET['sprint_id']) ? (int)$_GET['sprint_id'] : 0;
$done = isset($_GET['done']) && (int)$_GET['done'] === 1;

if ($id > 0) {
    $controller = ControllersConfig::get_retrospectives_controller();
    $result = $controller->item_mark_done($id, $done);

    if ($result) {
        header("Location: ../detalle_retrospectiva.php?id={$sprint_id}&msg=Estado de la acción actualizado");
    } else {
        header("Location: ../detalle_retrospectiva.php?id={$sprint_id}&error=Error al actualizar el estado");
    }
    exit;
}

header('Location: ../retrospectivas.php');
exit;