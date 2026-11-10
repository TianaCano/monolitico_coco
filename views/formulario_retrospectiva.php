<?php
require_once __DIR__ . '/../bootstrap.php';

use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_retrospectives_controller();
$sprint = null;
$is_edit = false;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $sprint = $controller->get_sprint((int)$_GET['id']);
    if ($sprint !== null) {
        $is_edit = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $is_edit ? 'Editar Retrospectiva' : 'Nueva Retrospectiva'; ?></title>
</head>
<body>
    <header>
        <h1><?php echo $is_edit ? 'Editar Sprint / Retrospectiva' : 'Crear Nueva Retrospectiva'; ?></h1>
        <p><a href="retrospectivas.php">← Volver al listado</a></p>
    </header>

    <main>
        <form action="<?php echo $is_edit ? 'actions/modificar_retrospectiva.php' : 'actions/registrar_retrospectiva.php'; ?>" method="POST" id="form-sprint">
            <?php if ($is_edit): ?>
                <input type="hidden" name="id" value="<?php echo $sprint->get_id(); ?>">
            <?php endif; ?>

            <div>
                <label for="name">Nombre del Sprint:</label><br>
                <input type="text" id="name" name="name" required value="<?php echo $is_edit ? htmlspecialchars($sprint->get_name(), ENT_QUOTES, 'UTF-8') : ''; ?>" placeholder="Ej: Sprint 1">
            </div>
            <br>
            <div>
                <label for="start_date">Fecha de Inicio:</label><br>
                <input type="date" id="start_date" name="start_date" required value="<?php echo $is_edit ? htmlspecialchars($sprint->get_start_date(), ENT_QUOTES, 'UTF-8') : ''; ?>">
            </div>
            <br>
            <div>
                <label for="end_date">Fecha de Fin:</label><br>
                <input type="date" id="end_date" name="end_date" required value="<?php echo $is_edit ? htmlspecialchars($sprint->get_end_date(), ENT_QUOTES, 'UTF-8') : ''; ?>">
            </div>
            <br>
            <div>
                <button type="submit" id="btn-save-sprint">
                    <?php echo $is_edit ? 'Actualizar Retrospectiva' : 'Guardar Retrospectiva'; ?>
                </button>
            </div>
        </form>
    </main>
</body>
</html>