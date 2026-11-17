<?php
$pagina_actual = basename($_SERVER['PHP_SELF']);
$seccion_actual = $_GET['seccion'] ?? 'inicio';
?>
<aside class="menu-lateral">
    <div class="logo-contenedor">
        <span class="logo-icono">👥</span>
        <div class="logo-texto">
            Registro de<br>
            <strong>Retrospectivas</strong><br>
            <span>Ágiles</span>
        </div>
    </div>

    <nav class="navegacion">
        <ul>
            <li class="<?php echo ($pagina_actual === 'retrospectivas.php' && $seccion_actual === 'inicio') ? 'activo' : ''; ?>">
                <a href="retrospectivas.php">
                    <span>🏠</span> Inicio
                </a>
            </li>

            <li class="<?php echo ($pagina_actual === 'retrospectivas.php' && $seccion_actual === 'mis_retrospectivas') ? 'activo' : ''; ?>">
                <a href="retrospectivas.php?seccion=mis_retrospectivas">
                    <span>📅</span> Mis Retrospectivas
                </a>
            </li>
            <li class="opcion-decorativa">
                <a href="#" onclick="return false;">
                    <span>📊</span> Seguimiento
                </a>
            </li>
            <li class="opcion-decorativa">
                <a href="#" onclick="return false;">
                    <span>⚙️</span> Configuración
                </a>
            </li>
        </ul>
    </nav>

    <div class="pie-menu">
        <p>Reflexiona,<br>mejora,<br>evoluciona</p>
        <span class="corazon">♡</span>
    </div>
</aside>