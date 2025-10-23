<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<div class="contenedor">
    <h2 class="titulo">📦 Lotes de Insumos</h2>

    <div style="text-align: right; margin-bottom: 20px;">
        <form method="GET" style="display:inline-block;">
            <label for="mes">Mes:</label>
            <select name="mes" id="mes">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= (isset($mes) && $mes == $i) ? 'selected' : '' ?>>
                        <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>
                    </option>
                <?php endfor; ?>
            </select>

            <label for="anio">Año:</label>
            <select name="anio" id="anio">
                <?php $actual = date('Y'); for ($y = $actual; $y >= ($actual - 5); $y--): ?>
                    <option value="<?= $y ?>" <?= (isset($anio) && $anio == $y) ? 'selected' : '' ?>>
                        <?= $y ?>
                    </option>
                <?php endfor; ?>
            </select>

            <button type="submit" class="btn crear" style="padding: 6px 14px;">Filtrar</button>
        </form>

        <a href="<?= BASE_URL ?>inventario/crearLote" class="btn crear" style="margin-left: 12px;">+ Registrar nuevo lote</a>
    </div>

    <?php if (empty($lotes)): ?>
        <p class="mensaje-alerta">No hay lotes registrados para este período.</p>
    <?php else: ?>
        <table class="tabla-inventario">
            <thead>
                <tr>
                    <th>Insumo</th>
                    <th>Cantidad</th>
                    <th>Fecha Ingreso</th>
                    <th>Fecha Salida Estimada</th>
                    <th>Costo Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lotes as $l): ?>
                <tr>
                    <td><?= htmlspecialchars($l['insumo']) ?></td>
                    <td><?= htmlspecialchars($l['cantidad']) ?></td>
                    <td><?= $l['fecha_ingreso'] ? date('d/m/Y H:i', strtotime($l['fecha_ingreso'])) : '—' ?></td>
                    <td><?= $l['fecha_estimado_termino'] ? date('d/m/Y', strtotime($l['fecha_estimado_termino'])) : '—' ?></td>
                    <td>S/<?= number_format($l['costo_total'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/inventario.css">
<script src="<?= BASE_URL ?>assets/js/confirmacion.js"></script>
