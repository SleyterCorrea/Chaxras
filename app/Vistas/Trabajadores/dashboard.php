<h1>Bienvenido al panel de trabajadores</h1>
<p>Hola <?= htmlspecialchars($usuario['nombre']) ?>, tu nivel es: <strong><?= htmlspecialchars($nivel) ?></strong></p>

<?php if ($nivel == 'admin'): ?>
    <a href="<?= BASE_URL ?>admin">Ir al panel de administrador</a>
<?php elseif ($nivel == 'cocina'): ?>
    <a href="<?= BASE_URL ?>cocina">Ir al módulo de cocina</a>
<?php elseif ($nivel == 'mesero'): ?>
    <a href="<?= BASE_URL ?>mesero">Ir al módulo de pedidos</a>
<?php elseif ($nivel == 'encargadoRRHH'): ?>
    <a href="<?= BASE_URL ?>rrhh">Ir a recursos humanos</a>
<?php elseif ($nivel == 'encargadoInventario'): ?>
    <a href="<?= BASE_URL ?>inventario">Ir al inventario</a>
<?php elseif ($nivel == 'encargadoFinanciera'): ?>
    <a href="<?= BASE_URL ?>finanzas">Ir a finanzas</a>
<?php else: ?>
    <p>No tienes un módulo asignado. Contacta al administrador.</p>
<?php endif; ?>
