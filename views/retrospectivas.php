<?php
require_once __DIR__ . '/../bootstrap.php';

use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_retrospectives_controller();
$sprints = $controller->get_sprints();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Sprints Ágiles - Retrospectivas</title>
</head>
<body>
    <header id="main-header">
        <h1>Registro de Sprints Ágiles</h1>
    </header>

    <main id="main-content">
        <?php if (isset($_GET['msg'])): ?>
            <p id="feedback-msg"><strong>Mensaje:</strong> <?php echo htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <p id="error-msg"><strong>Error:</strong> <?php echo htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <section id="actions-panel">
            <a href="formulario_retrospectiva.php" id="btn-create-retro">+ Crear Nueva Retrospectiva</a>
        </section>

        <section id="sprints-list-section">
            <h2>Listado de Retrospectivas</h2>

            <?php if (empty($sprints)): ?>
                <p>No hay retrospectivas registradas todavía.</p>
            <?php else: ?>
                <table id="table-retrospectives" border="1">
                    <thead>
                        <tr>
                            <th>Sprint</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Logros</th>
                            <th>Impedimentos</th>
                            <th>Acciones</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sprints as $sprint): ?>
                            <?php 
                                $summary = $controller->get_sprint_summary($sprint->get_id());
                                $counts = $summary['counts'];
                                $status = $summary['status'];
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($sprint->get_name(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($sprint->get_start_date(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($sprint->get_end_date(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo $counts['logro']; ?></td>
                                <td><?php echo $counts['impedimento']; ?></td>
                                <td><?php echo $counts['accion']; ?></td>
                                <td><strong><?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                <td>
                                    <a href="detalle_retrospectiva.php?id=<?php echo $sprint->get_id(); ?>">Ver Detalle</a> |
                                    <a href="formulario_retrospectiva.php?id=<?php echo $sprint->get_id(); ?>">Editar</a> |
                                    <a href="actions/eliminar_retrospectiva.php?id=<?php echo $sprint->get_id(); ?>" onclick="return confirm('¿Seguro que deseas eliminar este sprint y todos sus ítems?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>