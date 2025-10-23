<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<div class="dashboard-wrapper">
    <div class="dashboard">
        <a href="<?= BASE_URL ?>inventario" class="btn-volver">← Volver</a>

        <h2>+ Registrar Nuevo Insumo</h2>

        <form method="POST" class="formulario">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Unidad:</label>
            <select name="unidad" required>
                <option value="kg">Kg</option>
                <option value="lt">Lt</option>
                <option value="unid">Unid</option>
            </select>

            <button type="submit" class="btn-guardar">Guardar</button>
        </form>
    </div>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/insumos.css">
<script src="<?= BASE_URL ?>assets/js/insumos.js"></script>
