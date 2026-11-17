<?php
require_once __DIR__ . '/../bootstrap.php';

use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_retrospectives_controller();
$sprint = null;
$es_edicion = false;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $sprint = $controller->get_sprint((int)$_GET['id']);
    if ($sprint !== null) {
        $es_edicion = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $es_edicion ? 'Editar Retrospectiva' : 'Nueva Retrospectiva'; ?></title>
    <link rel="stylesheet" href="../public/css/estilos.css">
</head>
<body>

    <?php require_once __DIR__ . '/partials/menu.php'; ?>

    <main class="contenido-principal">
        <div class="cabecera-seccion" style="margin-bottom: 20px;">
            <h2><?php echo $es_edicion ? '✏️ Modificar Retrospectiva' : '🚀 Nueva Retrospectiva de Sprint'; ?></h2>
            <a href="retrospectivas.php" style="color: #532c94; text-decoration: none; font-weight: 700;">← Volver al inicio</a>
        </div>

        <div class="formulario-caja">
            <form action="<?php echo $es_edicion ? 'actions/modificar_retrospectiva.php' : 'actions/registrar_retrospectiva.php'; ?>" method="POST">
                <?php if ($es_edicion): ?>
                    <input type="hidden" name="id" value="<?php echo $sprint->get_id(); ?>">
                <?php endif; ?>

                <div class="campo-formulario">
                    <label for="name">Nombre del Sprint:</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo $es_edicion ? htmlspecialchars($sprint->get_name(), ENT_QUOTES, 'UTF-8') : ''; ?>" 
                           placeholder="Ejemplo: Sprint 1">
                </div>

                <div class="campo-formulario">
                    <label for="start_date">Fecha de Inicio:</label>
                    <input type="date" id="start_date" name="start_date" required 
                           value="<?php echo $es_edicion ? htmlspecialchars($sprint->get_start_date(), ENT_QUOTES, 'UTF-8') : ''; ?>">
                </div>

                <div class="campo-formulario">
                    <label for="end_date">Fecha de Finalización:</label>
                    <input type="date" id="end_date" name="end_date" required 
                           value="<?php echo $es_edicion ? htmlspecialchars($sprint->get_end_date(), ENT_QUOTES, 'UTF-8') : ''; ?>">
                </div>

                <button type="submit" class="boton-morado">
                    <?php echo $es_edicion ? 'Actualizar Retrospectiva' : 'Guardar Retrospectiva'; ?>
                </button>
            </form>
        </div>
    </main>

</body>
</html>