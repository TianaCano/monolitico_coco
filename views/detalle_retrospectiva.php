<?php
require_once __DIR__ . '/../bootstrap.php';

use app\controllers\config\ControllersConfig;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: retrospectivas.php');
    exit;
}

$sprint_id = (int)$_GET['id'];
$controller = ControllersConfig::get_retrospectives_controller();
$sprint = $controller->get_sprint($sprint_id);

if ($sprint === null) {
    header('Location: retrospectivas.php?error=Sprint no encontrado');
    exit;
}

$resumen = $controller->get_sprint_summary($sprint_id);
$items = $resumen['items'];
$estado = $resumen['status'];

$sprint_anterior = $controller->get_previous_sprint($sprint_id);
$acciones_anteriores = [];
if ($sprint_anterior !== null) {
    $items_anteriores = $controller->get_items_by_sprint($sprint_anterior->get_id());
    $acciones_anteriores = array_filter($items_anteriores, fn($i) => $i->get_category() === 'accion');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle: <?php echo htmlspecialchars($sprint->get_name(), ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="../public/css/estilos.css">
</head>
<body>

    <?php require_once __DIR__ . '/partials/menu.php'; ?>

    <main class="contenido-principal">
        <div class="cabecera-seccion">
            <div>
                <h2>🚀 <?php echo htmlspecialchars($sprint->get_name(), ENT_QUOTES, 'UTF-8'); ?></h2>
                <p class="subtitulo-detalle">
                    Fechas: <?php echo htmlspecialchars($sprint->get_start_date(), ENT_QUOTES, 'UTF-8'); ?> al <?php echo htmlspecialchars($sprint->get_end_date(), ENT_QUOTES, 'UTF-8'); ?> |
                    Estado: <strong><?php echo htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?></strong>
                </p>
            </div>
            <a href="retrospectivas.php" class="enlace-volver">← Volver al listado</a>
        </div>

         <?php if (isset($_GET['msg'])): ?>
            <div class="alerta-exito">✓ <?php echo htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alerta-error">⚠ <?php echo htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <!-- Retrospectiva anterior -->
        <?php if ($sprint_anterior !== null): ?>
            <div class="contenedor-sprints seccion-sprint-anterior">
                <h3>🔍 Compromisos del Sprint Anterior (<?php echo htmlspecialchars($sprint_anterior->get_name(), ENT_QUOTES, 'UTF-8'); ?>)</h3>
                <?php if (empty($acciones_anteriores)): ?>
                    <p class="texto-vacio-anterior">No hubo compromisos registrados en el sprint anterior.</p>
                <?php else: ?>
                    <div class="lista-acciones-anteriores">
                        <?php foreach ($acciones_anteriores as $act): ?>
                            <div class="tarjeta-sprint tarjeta-sprint-compacta">
                                <span><?php echo htmlspecialchars($act->get_description(), ENT_QUOTES, 'UTF-8'); ?></span>
                                <div>
                                    <?php if ($act->get_done()): ?>
                                        <a href="actions/marcar_cumplimiento.php?id=<?php echo $act->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>&done=0" 
                                           class="insignia-estado estado-completadas">
                                           ✓ Cumplida (clic para desmarcar)
                                        </a>
                                    <?php else: ?>
                                        <a href="actions/marcar_cumplimiento.php?id=<?php echo $act->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>&done=1" 
                                           class="insignia-estado estado-pendientes">
                                           ○ Pendiente (clic para cumplir)
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="columnas-retro">
            <!--Logros -->
            <div class="columna-caja">
                <h3 class="titulo-columna titulo-logros">● Logros (Lo bueno)</h3>
                <?php 
                    $logros = array_filter($items, fn($i) => $i->get_category() === 'logro');
                    if (empty($logros)):
                ?>
                    <p class="texto-columna-vacia">No hay logros registrados.</p>
                <?php else: ?>
                    <?php foreach ($logros as $i): ?>
                        <div class="item-postit borde-logro">
                            <p><?php echo htmlspecialchars($i->get_description(), ENT_QUOTES, 'UTF-8'); ?></p>
                            <div class="pie-postit-eliminar">
                                <a href="actions/eliminar_item.php?id=<?php echo $i->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>" 
                                   onclick="return confirm('¿Eliminar ítem?');" 
                                   class="enlace-eliminar-item">Eliminar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!--Impedimentos -->
            <div class="columna-caja">
                <h3 class="titulo-columna titulo-impedimentos">● Impedimentos (Obstáculos)</h3>
                <?php 
                    $impedimentos = array_filter($items, fn($i) => $i->get_category() === 'impedimento');
                    if (empty($impedimentos)):
                ?>
                    <p class="texto-columna-vacia">No hay impedimentos.</p>
                <?php else: ?>
                    <?php foreach ($impedimentos as $i): ?>
                        <div class="item-postit borde-impedimento">
                            <p><?php echo htmlspecialchars($i->get_description(), ENT_QUOTES, 'UTF-8'); ?></p>
                            <div class="pie-postit-eliminar">
                                <a href="actions/eliminar_item.php?id=<?php echo $i->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>" 
                                   onclick="return confirm('¿Eliminar ítem?');" 
                                   class="enlace-eliminar-item">Eliminar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!--Acciones -->
            <div class="columna-caja">
                <h3 class="titulo-columna titulo-acciones">● Acciones (Compromisos)</h3>
                <?php 
                    $acciones = array_filter($items, fn($i) => $i->get_category() === 'accion');
                    if (empty($acciones)):
                ?>
                    <p class="texto-columna-vacia">No hay acciones registradas.</p>
                <?php else: ?>
                    <?php foreach ($acciones as $i): ?>
                        <div class="item-postit borde-accion">
                            <p><?php echo htmlspecialchars($i->get_description(), ENT_QUOTES, 'UTF-8'); ?></p>
                            <div class="pie-postit-accion">
                                <?php if ($i->get_done()): ?>
                                    <a href="actions/marcar_cumplimiento.php?id=<?php echo $i->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>&done=0" 
                                       class="insignia-estado estado-completadas insignia-compacta">✓ Cumplida</a>
                                <?php else: ?>
                                    <a href="actions/marcar_cumplimiento.php?id=<?php echo $i->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>&done=1" 
                                       class="insignia-estado estado-pendientes insignia-compacta">○ Pendiente</a>
                                <?php endif; ?>
                                <a href="actions/eliminar_item.php?id=<?php echo $i->get_id(); ?>&sprint_id=<?php echo $sprint_id; ?>" 
                                   onclick="return confirm('¿Eliminar acción?');" 
                                   class="enlace-eliminar-item">Eliminar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulario para agregaar-->
        <div class="formulario-caja">
            <h3 class="titulo-formulario">+ Agregar Ítem al Sprint</h3>
            <form action="actions/registrar_item.php" method="POST">
                <input type="hidden" name="sprint_id" value="<?php echo $sprint_id; ?>">

                <div class="campo-formulario">
                    <label for="category">Categoría:</label>
                    <select name="category" id="category" required>
                        <option value="logro">Logro (Lo que funcionó)</option>
                        <option value="impedimento">Impedimento (Qué mejorar)</option>
                        <option value="accion">Acción (Compromiso a futuro)</option>
                    </select>
                </div>

                <div class="campo-formulario">
                    <label for="description">Descripción:</label>
                    <textarea name="description" id="description" rows="2" required placeholder="Escribe el comentario..."></textarea>
                </div>

                <button type="submit" class="boton-morado">Guardar Ítem</button>
            </form>
        </div>
    </main>

</body>
</html>