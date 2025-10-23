<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<div class="contenedor">
    <h2 class="titulo">📦 Gestión de Insumos</h2>

    <div class="acciones">
        <a href="<?= BASE_URL ?>inventario/crear_insumo" class="btn crear">+ Crear insumo</a>
    </div>

    <?php if (empty($insumos)): ?>
        <p class="mensaje-alerta">No hay insumos registrados.</p>
    <?php else: ?>
    <table class="tabla-inventario">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Unidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($insumos as $i): ?>
            <tr>
                <td><?= htmlspecialchars($i['nombre']) ?></td>
                <td><?= htmlspecialchars($i['unidad']) ?></td>
                <td>
                    <span class="badge <?= $i['estado'] == 'activo' ? 'badge-activo' : 'badge-inactivo' ?>">
                        <?= ucfirst($i['estado']) ?>
                    </span>
                </td>
                <td>
                    <a href="<?= BASE_URL ?>inventario/editar_insumo/<?= $i['id_insumo'] ?>" class="btn editar">Editar</a>
                    <a href="<?= BASE_URL ?>inventario/eliminar_insumo/<?= $i['id_insumo'] ?>" class="btn eliminar">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/inventario.css">
<script src="<?= BASE_URL ?>assets/js/inventario.js"></script>