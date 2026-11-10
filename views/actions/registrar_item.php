<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sprint_id = isset($_POST['sprint_id']) ? (int)$_POST['sprint_id'] : 0;
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $review_date = !empty($_POST['review_date']) ? $_POST['review_date'] : null;

    $controller = ControllersConfig::get_retrospectives_controller();
    $result = $controller->item_create($sprint_id, $category, $description, $review_date);

    if ($result) {
        header("Location: ../detalle_retrospectiva.php?id={$sprint_id}&msg=Ítem agregado correctamente");
    } else {
        header("Location: ../detalle_retrospectiva.php?id={$sprint_id}&error=Error al agregar el ítem. Verifica los datos.");
    }
    exit;
}

header('Location: ../retrospectivas.php');
exit;