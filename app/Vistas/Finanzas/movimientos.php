<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<div class="finanzas-wrapper">
    <h2 class="titulo-finanzas">Gestión Financiera</h2>

    <div class="resumen-finanzas">
        <div class="card ingreso" onclick="mostrarSeccion('ingresos')">
            <h4>Ingresos</h4>
            <p>S/<?= number_format($total_ingresos, 2) ?></p>
        </div>
        <div class="card egreso" onclick="mostrarSeccion('egresos')">
            <h4>Egresos</h4>
            <p>S/<?= number_format($total_egresos, 2) ?></p>
        </div>
        <div class="card balance <?= $balance >= 0 ? 'positivo' : 'negativo' ?>">
            <h4>Balance</h4>
            <p>S/<?= number_format($balance, 2) ?></p>
        </div>
    </div>

    <!-- INGRESOS -->
    <div id="seccion-ingresos" class="seccion-finanzas" style="display:none;">
        <div class="cabecera-seccion">
            <h3>Ingresos</h3>
            <div class="acciones-seccion">
                <a href="<?= BASE_URL ?>finanzas/nuevoIngresoManual" class="btn btn-success">➕ Nuevo Ingreso</a>
                <a href="<?= BASE_URL ?>finanzas/exportar_excel?ingresos_desde=<?= $_GET['ingresos_desde'] ?? '' ?>&ingresos_hasta=<?= $_GET['ingresos_hasta'] ?? '' ?>" class="btn btn-exportar">📥 Exportar Excel</a>
            </div>
        </div>

        <form method="get" action="<?= BASE_URL ?>finanzas" class="filtro-form">
            <input type="hidden" name="tipo" value="ingresos">
            <label>Desde: <input type="date" name="ingresos_desde" value="<?= htmlspecialchars($_GET['ingresos_desde'] ?? '') ?>"></label>
            <label>Hasta: <input type="date" name="ingresos_hasta" value="<?= htmlspecialchars($_GET['ingresos_hasta'] ?? '') ?>"></label>
            <button type="submit" class="btn btn-filtrar">Filtrar</button>
        </form>

        <table class="tabla-finanzas">
            <thead>
                <tr>
                    <th>Fuente</th>
                    <th>Monto</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ingresos as $i): ?>
                    <tr>
                        <td><?= htmlspecialchars($i['fuente']) ?></td>
                        <td>S/<?= number_format($i['monto'], 2) ?></td>
                        <td><?= htmlspecialchars($i['descripcion']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($i['fecha'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- EGRESOS -->
    <div id="seccion-egresos" class="seccion-finanzas" style="display:none;">
        <div class="cabecera-seccion">
            <h3>Egresos</h3>
            <div class="acciones-seccion">
                <a href="<?= BASE_URL ?>finanzas/nuevoEgresoManual" class="btn btn-danger">➖ Nuevo Egreso</a>
                <a href="<?= BASE_URL ?>finanzas/exportar_excel?egresos_desde=<?= $_GET['egresos_desde'] ?? '' ?>&egresos_hasta=<?= $_GET['egresos_hasta'] ?? '' ?>" class="btn btn-exportar">📥 Exportar Excel</a>
            </div>
        </div>

        <form method="get" action="<?= BASE_URL ?>finanzas" class="filtro-form">
            <input type="hidden" name="tipo" value="egresos">
            <label>Desde: <input type="date" name="egresos_desde" value="<?= htmlspecialchars($_GET['egresos_desde'] ?? '') ?>"></label>
            <label>Hasta: <input type="date" name="egresos_hasta" value="<?= htmlspecialchars($_GET['egresos_hasta'] ?? '') ?>"></label>
            <button type="submit" class="btn btn-filtrar">Filtrar</button>
        </form>

        <table class="tabla-finanzas">
            <thead>
                <tr>
                    <th>Motivo</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($egresos as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['motivo']) ?></td>
                        <td>S/<?= number_format($e['monto'], 2) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($e['fecha'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/finanzas.css">
<script src="<?= BASE_URL ?>assets/js/finanzas.js"></script>
