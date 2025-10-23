<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>


<div class="platos-wrapper">
    <h2 class="titulo-seccion">🍽 Gestión de Platos</h2>
    <div class="botonera-top">
        <a href="<?= BASE_URL ?>cocina/crearPlato" class="btn-nuevo">+ Nuevo Plato</a>
    </div>
    <table class="tabla-platos">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($platos as $plato): ?>
            <tr>
                <td>
                    <?php if ($plato['imagen']): ?>
                        <img src="<?= BASE_URL . ltrim($plato['imagen'], '/') ?>" alt="Plato" class="plato-thumb">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/80x50?text=Sin+foto" class="plato-thumb">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($plato['nombre']) ?></td>
                <td><?= htmlspecialchars($plato['categoria']) ?></td>
                <td>S/<?= number_format($plato['precio'], 2) ?></td>
                <td>
                    <span class="badge <?= $plato['estado'] === 'activo' ? 'activo' : 'inactivo' ?>">
                        <?= ucfirst($plato['estado']) ?>
                    </span>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>cocina/editarPlato/<?= $plato['id_plato'] ?>" class="btn-editar">
                        ✏️ Editar
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/platos.css">
<script src="<?= BASE_URL ?>assets/js/cocina.js"></script>