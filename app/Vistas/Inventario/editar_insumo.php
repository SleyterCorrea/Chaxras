<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<div class="dashboard-wrapper">
    <a href="<?= BASE_URL ?>inventario" class="btn-volver">← Volver</a>

    <div class="dashboard">
        <h2>✏️ Editar Insumo</h2>

        <form method="POST" class="formulario">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($insumo['nombre']) ?>" required>

            <label>Unidad:</label>
            <select name="unidad" required>
                <option value="kg" <?= $insumo['unidad'] == 'kg' ? 'selected' : '' ?>>Kg</option>
                <option value="lt" <?= $insumo['unidad'] == 'lt' ? 'selected' : '' ?>>Lt</option>
                <option value="unid" <?= $insumo['unidad'] == 'unid' ? 'selected' : '' ?>>Unid</option>
            </select>

            <label>Estado:</label>
            <select name="estado" required>
                <option value="activo" <?= $insumo['estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                <option value="inactivo" <?= $insumo['estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
            </select>

            <button type="submit" class="btn-guardar">Guardar Cambios</button>
        </form>
    </div>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/insumos.css">
<script src="<?= BASE_URL ?>assets/js/insumos.js"></script>
