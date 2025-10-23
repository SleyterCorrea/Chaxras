<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<div class="contenedor-inventario">
    <a href="<?= BASE_URL ?>inventario" class="btn-volver">← Volver</a>
    <h2>📦 Registrar Nuevo Lote</h2>

    <form method="POST" class="formulario-inventario">
        <label for="id_insumo">Insumo:</label>
        <select name="id_insumo" id="id_insumo" required>
            <?php foreach ($insumos as $insumo): ?>
                <option value="<?= $insumo['id_insumo'] ?>"><?= htmlspecialchars($insumo['nombre']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="cantidad">Cantidad:</label>
        <input type="number" step="0.01" name="cantidad" id="cantidad" required>

        <label for="fecha_ingreso">Fecha de Ingreso:</label>
        <input type="date" name="fecha_ingreso" id="fecha_ingreso" required>

        <label for="fecha_estimado_termino">Fecha Estimada de Consumo:</label>
        <input type="date" name="fecha_estimado_termino" id="fecha_estimado_termino" required>

        <label for="costo_total">Costo Total:</label>
        <input type="number" step="0.01" name="costo_total" id="costo_total" required>

        <button type="submit" class="btn-guardar">Guardar Lote</button>
    </form>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/inventario.css">
<script src="<?= BASE_URL ?>assets/js/inventario.js"></script>
