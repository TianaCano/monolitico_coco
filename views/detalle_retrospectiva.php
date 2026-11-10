<?php
require_once __DIR__ . '/../bootstrap.php';

use app\controllers\config\ControllersConfig;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: retrospectivas.php?error=Identificador no proporcionado');
    exit;
}

$sprint_id = (int)$_GET['id'];
$controller = ControllersConfig::get_retrospectives_controller();
$sprint = $controller->get_sprint($sprint_id);

if ($sprint === null) {
    header('Location: retrospectivas.php?error=Sprint no encontrado');
    exit;
}

$summary = $controller->get_sprint_summary($sprint_id);
$items = $summary['items'];
$status = $summary['status'];

$previous_sprint = $controller->get_previous_sprint($sprint_id);
$previous_actions = [];
if ($previous_sprint !== null) {
    $prev_items = $controller->get_items_by_sprint($previous_sprint->get_id());
    $previous_actions = array_filter($prev_items, fn($item) => $item->get_category() === 'accion');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle: <?php echo htmlspecialchars($sprint->get_name(), ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <header>
        <h1>Retrospectiva: <?php echo htmlspecialchars($sprint->get_name(), ENT_QUOTES, 'UTF-8'); ?></h1>
        <p>
            <strong>Fechas:</strong> <?php echo htmlspecialchars($sprint->get_start_date(), ENT_QUOTES, 'UTF-8'); ?> al <?php echo htmlspecialchars($sprint->get_end_date(), ENT_QUOTES, 'UTF-8'); ?> |
            <strong>Estado:</strong> <?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>
        </p>
        <p><a href="retrospectivas.php">← Volver al listado principal</a></p>
    </header>

    <main>
        <?php if (isset($_GET['msg'])): ?>
            <p><strong>Mensaje:</strong> <?php echo htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <p><strong>Error:</strong> <?php echo htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <!-- Sección de revisión del sprint previo -->
        <section id="previous-sprint-section">
            <h2>Acciones del Sprint Anterior</h2>
            <?php if ($previous_sprint === null): ?>
                <p>No hay un sprint anterior registrado para comparar compromisos.</p>
            <?php elseif (empty($previous_actions)): ?>
                <p>El sprint anterior (<?php echo htmlspecialchars($previous_sprint->get_name(), ENT_QUOTES, 'UTF-8'); ?>) no tuvo compromisos de acción registrados.</p>
            <?php else: ?>
                <p>Revisión de compromisos adquiridos en: <strong><?php echo htmlspecialchars($previous_sprint->get_name(), ENT_QUOTES, 'UTF-8'); ?></strong></p>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Acción Prometida</th>
                            <th>¿Se Cumplió?</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($previous_actions as $action): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($action->get_description(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo $action->get_done() ? 'Sí, cumplida' : 'No cumplida'; ?></td>
                                <td>
                                    <?php if ($action->get_done()): ?>
                                        <a href="actions/marcar_cumplimiento.php?id=<?php echo $action->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>&done=0">Marcar como No Cumplida</a>
                                    <?php else: ?>
                                        <a href="actions/marcar_cumplimiento.php?id=<?php echo $action->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>&done=1">Marcar como Cumplida</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <hr>

        <!-- Sección de Ítems del Sprint Actual -->
        <section id="current-items-section">
            <h2>Resultados de este Sprint</h2>

            <!-- 1. Logros -->
            <div id="logros-block">
                <h3>Logros (Lo que salió bien)</h3>
                <ul>
                    <?php 
                        $logros = array_filter($items, fn($i) => $i->get_category() === 'logro');
                        if (empty($logros)):
                    ?>
                        <li>No se registraron logros.</li>
                    <?php else: ?>
                        <?php foreach ($logros as $item): ?>
                            <li>
                                <?php echo htmlspecialchars($item->get_description(), ENT_QUOTES, 'UTF-8'); ?>
                                [<a href="actions/eliminar_item.php?id=<?php echo $item->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>" onclick="return confirm('¿Eliminar este ítem?');">Eliminar</a>]
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- 2. Impedimentos -->
            <div id="impedimentos-block">
                <h3>Impedimentos (Lo que salió mal o causó demoras)</h3>
                <ul>
                    <?php 
                        $impedimentos = array_filter($items, fn($i) => $i->get_category() === 'impedimento');
                        if (empty($impedimentos)):
                    ?>
                        <li>No se registraron impedimentos.</li>
                    <?php else: ?>
                        <?php foreach ($impedimentos as $item): ?>
                            <li>
                                <?php echo htmlspecialchars($item->get_description(), ENT_QUOTES, 'UTF-8'); ?>
                                [<a href="actions/eliminar_item.php?id=<?php echo $item->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>" onclick="return confirm('¿Eliminar este ítem?');">Eliminar</a>]
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- 3. Acciones de mejora -->
            <div id="acciones-block">
                <h3>Acciones (Compromisos para el siguiente sprint)</h3>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Fecha Límite/Revisión</th>
                            <th>Cumplida</th>
                            <th>Cambiar Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $acciones = array_filter($items, fn($i) => $i->get_category() === 'accion');
                            if (empty($acciones)):
                        ?>
                            <tr><td colspan="5">No hay acciones registradas para este sprint.</td></tr>
                        <?php else: ?>
                            <?php foreach ($acciones as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item->get_description(), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo $item->get_review_date() ? htmlspecialchars($item->get_review_date(), ENT_QUOTES, 'UTF-8') : 'No asignada'; ?></td>
                                    <td><strong><?php echo $item->get_done() ? 'Cumplida' : 'Pendiente'; ?></strong></td>
                                    <td>
                                        <?php if ($item->get_done()): ?>
                                            <a href="actions/marcar_cumplimiento.php?id=<?php echo $item->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>&done=0">Marcar como Pendiente</a>
                                        <?php else: ?>
                                            <a href="actions/marcar_cumplimiento.php?id=<?php echo $item->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>&done=1">Marcar como Cumplida</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="actions/eliminar_item.php?id=<?php echo $item->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>" onclick="return confirm('¿Eliminar esta acción?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <hr>

        <!-- Formulario para agregar ítem -->
        <section id="add-item-section">
            <h3>Agregar Ítem a la Retrospectiva</h3>
            <form action="actions/registrar_item.php" method="POST">
                <input type="hidden" name="sprint_id" value="<?php echo $sprint_id; ?>">

                <div>
                    <label for="category">Categoría:</label><br>
                    <select name="category" id="category" required>
                        <option value="logro">Logro</option>
                        <option value="impedimento">Impedimento</option>
                        <option value="accion">Acción (Compromiso)</option>
                    </select>
                </div>
                <br>
                <div>
                    <label for="description">Descripción:</label><br>
                    <textarea name="description" id="description" rows="3" cols="40" required placeholder="Escribe lo ocurrido o la acción a tomar..."></textarea>
                </div>
                <br>
                <div>
                    <label for="review_date">Fecha de Revisión (opcional, solo para acciones):</label><br>
                    <input type="date" name="review_date" id="review_date">
                </div>
                <br>
                <div>
                    <button type="submit">Agregar Ítem</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>