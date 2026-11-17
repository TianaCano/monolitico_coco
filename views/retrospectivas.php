<?php
require_once __DIR__ . '/../bootstrap.php';

use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_retrospectives_controller();
$sprints = $controller->get_sprints();

$seccion = $_GET['seccion'] ?? 'inicio';

$total_retrospectivas = count($sprints);
$total_acciones = 0;
$acciones_cumplidas = 0;

foreach ($sprints as $sp) {
    $resumen = $controller->get_sprint_summary($sp->get_id());
    foreach ($resumen['items'] as $item) {
        if ($item->get_category() === 'accion') {
            $total_acciones++;
            if ($item->get_done() === true) {
                $acciones_cumplidas++;
            }
        }
    }
}

$porcentaje_cumplimiento = 0;
if ($total_acciones > 0) {
    $porcentaje_cumplimiento = round(($acciones_cumplidas / $total_acciones) * 100);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $seccion === 'mis_retrospectivas' ? 'Mis Retrospectivas' : 'Registro de Retrospectivas Ágiles'; ?></title>
    <link rel="stylesheet" href="../public/css/estilos.css">
</head>
<body>

    <?php require_once __DIR__ . '/partials/menu.php'; ?>

    <main class="contenido-principal">
        <div class="etiqueta-sesion">
            🚫 Sin inicio de sesión
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alerta-exito">
                ✓ <?php echo htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alerta-error">
                ⚠ <?php echo htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if ($seccion === 'inicio'): ?>
            <!--Imagen -->
            <section class="banner-bienvenida">
                <div class="banner-texto">
                    <h1>¡Hola, equipo!</h1>
                    <p>Aquí puedes registrar y dar seguimiento a tus retrospectivas de Scrum.</p>
                    <span class="frase-motivacional">
                        Construyamos juntos un equipo cada vez mejor ♡
                    </span>
                </div>
                <div class="banner-imagen-contenedor">
                    <img src="../public/img/equipo.gif" alt="GIF" class="imagen-banner-equipo">
                </div>
            </section>

            <!-- Barra de acciones -->
            <section class="barra-acciones">
                <a href="formulario_retrospectiva.php" class="boton-morado">
                    + Crear Nueva Retrospectiva
                </a>
                <div class="buscador-decorativo">
                    <input type="text" placeholder="🔍 Buscar por sprint..." readonly title="Elemento visual">
                </div>
            </section>

            <!-- Tarjetas-->
            <section class="tarjetas-kpi">
                <div class="tarjeta-kpi kpi-morada">
                    <div class="kpi-icono">🏆</div>
                    <div class="kpi-datos">
                        <h3><?php echo $total_retrospectivas; ?></h3>
                        <p>Retrospectivas realizadas</p>
                    </div>
                </div>

                <div class="tarjeta-kpi kpi-amarilla">
                    <div class="kpi-icono">✓</div>
                    <div class="kpi-datos">
                        <h3><?php echo $porcentaje_cumplimiento; ?>%</h3>
                        <p>Cumplimiento de acciones</p>
                    </div>
                </div>

                <div class="tarjeta-kpi kpi-verde">
                    <div class="kpi-icono">📈</div>
                    <div class="kpi-datos">
                        <h3>Mejora continua</h3>
                        <p>¡Sigamos avanzando!</p>
                    </div>
                </div>
            </section>

            <section class="contenedor-sprints">
                <div class="cabecera-seccion">
                    <h2>★ Últimas Retrospectivas</h2>
                    <a href="retrospectivas.php?seccion=mis_retrospectivas" class="enlace-ver-todas">Ver todas →</a>
                </div>

        <?php else: ?>
            <section class="cabecera-seccion cabecera-formulario">
                <div>
                    <h2>📅 Todas Mis Retrospectivas</h2>
                    <p class="subtitulo-detalle">Historial completo de sprints realizados por el equipo</p>
                </div>
                <a href="formulario_retrospectiva.php" class="boton-morado">+ Nueva Retrospectiva</a>
            </section>

            <section class="contenedor-sprints">
                <div class="cabecera-seccion">
                    <h2>Listado General</h2>
                    <a href="retrospectivas.php" class="enlace-volver">← Volver al Inicio</a>
                </div>
        <?php endif; ?>
        
            <?php if (empty($sprints)): ?>
                <p class="mensaje-vacio">
                    Aún no hay retrospectivas registradas. ¡Crea la primera con el botón morado!
                </p>
            <?php else: ?>
                <?php foreach ($sprints as $sprint): ?>
                    <?php 
                        $resumen = $controller->get_sprint_summary($sprint->get_id());
                        $conteos = $resumen['counts'];
                        $estado = $resumen['status'];

                        $clase_estado = 'estado-pendientes';
                        if ($estado === 'Completadas') {
                            $clase_estado = 'estado-completadas';
                        } elseif ($estado === 'En progreso') {
                            $clase_estado = 'estado-en-progreso';
                        }
                    ?>
                    <div class="tarjeta-sprint">
                        <a href="detalle_retrospectiva.php?id=<?php echo $sprint->get_id(); ?>" class="sprint-info-enlace" title="Ver detalle">
                            <span class="icono-cohete">🚀</span>
                            <div>
                                <h4><?php echo htmlspecialchars($sprint->get_name(), ENT_QUOTES, 'UTF-8'); ?></h4>
                                <span><?php echo htmlspecialchars($sprint->get_start_date(), ENT_QUOTES, 'UTF-8'); ?> al <?php echo htmlspecialchars($sprint->get_end_date(), ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                        </a>

                        <div class="conteo-items">
                            <div><span class="punto-logro">●</span> Logros: <?php echo $conteos['logro']; ?></div>
                            <div><span class="punto-impedimento">●</span> Impedimentos: <?php echo $conteos['impedimento']; ?></div>
                            <div><span class="punto-accion">●</span> Acciones: <?php echo $conteos['accion']; ?></div>
                        </div>

                        <div class="estado-y-botones">
                            <span class="insignia-estado <?php echo $clase_estado; ?>">
                                ✓ <?php echo htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                            <a href="formulario_retrospectiva.php?id=<?php echo $sprint->get_id(); ?>" class="enlace-icono" title="Editar">✏️</a>
                            <a href="actions/eliminar_retrospectiva.php?id=<?php echo $sprint->get_id(); ?>" 
                               class="enlace-icono" 
                               title="Eliminar"
                               onclick="return confirm('¿Seguro que deseas eliminar esta retrospectiva?');">🗑️</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

</body>
</html>