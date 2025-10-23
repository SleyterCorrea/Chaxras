<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<div class="plato-form-wrapper">
    <h2 class="titulo-seccion">✏️ Editar Plato</h2>
    <form method="POST" enctype="multipart/form-data" class="form-plato">
        <div class="form-group">
            <label>Nombre del Plato:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($plato['nombre']) ?>" required>
        </div>

        <div class="form-group">
            <label>Categoría:</label>
            <select name="categoria" required>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['id_tipo_plato'] ?>" 
                        <?= $cat['id_tipo_plato'] == $plato['id_tipo_plato'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Precio:</label>
            <input type="number" name="precio" step="0.01" min="0" value="<?= $plato['precio'] ?>" required>
        </div>

        <div class="form-group">
            <label>Estado:</label>
            <select name="estado" required>
                <option value="activo" <?= $plato['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                <option value="inactivo" <?= $plato['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>

        <div class="form-group">
            <label>Imagen:</label>
            <input type="file" name="imagen" accept="image/*" onchange="previewImagen(event)">
            <div class="img-preview">
                <img id="preview" 
                     src="<?= $plato['imagen'] 
                            ? BASE_URL . ltrim($plato['imagen'],'/') 
                            : 'https://via.placeholder.com/180x120?text=Preview' ?>" 
                     alt="Preview">
            </div>
        </div>

        <div class="botonera">
            <button type="submit" class="btn-guardar">💾 Actualizar Plato</button>
            <a href="<?= BASE_URL ?>cocina/platos" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/form-platos.css">
<script src="<?= BASE_URL ?>assets/js/form-platos.js"></script>
