<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/dashboardadmin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dashboard-wrapper">
    <h2>📊 Bienvenido, <?= htmlspecialchars($nombre_usuario) ?></h2>

    <div class="cards">
        <div class="card">
            <h3>👥 Trabajadores</h3>
            <p><?= $total_trabajadores ?></p>
            <span class="small">Último: <?= htmlspecialchars($ultimo_ingresado) ?></span>
        </div>
        <div class="card">
            <h3>📦 Lotes hoy</h3>
            <p><?= $lotesHoy ?></p>
        </div>
        <div class="card">
            <h3>📝 Pedidos Hoy</h3>
            <p><?= $pedidos_dia ?></p>
        </div>
    </div>

    <div class="charts">
        <div class="chart-container">
            <h4>📈 Estado de Pedidos</h4>
            <canvas id="estadoPedidosChart"></canvas>
        </div>
        <div class="chart-container">
            <h4>📊 Distribución de Trabajadores</h4>
            <canvas id="distribucionTrabajadoresChart"></canvas>
        </div>
    </div>

    <div class="chart-container-full">
        <h4>💰 Ingresos vs Egresos del Mes</h4>
        <canvas id="ingresosEgresosChart"></canvas>
    </div>
</div>

<script>
    const estadoPedidosData = <?= json_encode($estado_pedidos) ?>;
    const distribucionData = <?= json_encode($distribucion_niveles) ?>;
    const ingresosEgresosMes = <?= json_encode($ingresos_egresos_mes) ?>;
</script>
<script src="<?= BASE_URL ?>assets/js/dashboardadmin.js"></script>
