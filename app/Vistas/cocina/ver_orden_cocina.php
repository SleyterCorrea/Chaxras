<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include __DIR__ . '/../layout/sidebar.php';
include __DIR__ . '/../layout/encabezado.php';

// Calcular total directo aquí en caso no venga del controlador
$total = 0;
foreach ($pedido['detalles'] as $p) {
    $total += ($p['precio'] ?? 0) * $p['cantidad'];
}
?>

<div class="dashboard-wrapper">
    <div class="dashboard">
        <h2>👨‍🍳 Órdenes en Cocina</h2>

        <div class="pedido-info">
            <strong>Pedido #<?= $pedido['id_pedido'] ?> 
                - Mesa <?= htmlspecialchars($pedido['mesa'] ?? '---') ?> 
                - <?= htmlspecialchars($pedido['mesero'] ?? '---') ?> 
                - <?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?>
            </strong><br>
            <strong>Total:</strong> S/<?= number_format($total, 2) ?> 
            - <strong>Estado:</strong> <?= ucfirst($pedido['estado']) ?>
        </div>

        <div class="tabla-responsive">
            <table class="tabla-cocina">
                <thead>
                    <tr>
                        <th>Plato</th>
                        <th>Categoría</th>
                        <th>Cantidad</th>
                        <th>Orden</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedido['detalles'] as $plato): ?>
                        <tr>
                            <td><?= htmlspecialchars($plato['nombre']) ?></td>
                            <td><?= htmlspecialchars($plato['tipo_plato']) ?></td>
                            <td><?= $plato['cantidad'] ?></td>
                            <td><strong><?= $plato['orden_entrega'] ?: '—' ?></strong></td>
                            <td>S/<?= number_format(($plato['precio'] ?? 0) * $plato['cantidad'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="acciones-cocina">
            <a href="<?= BASE_URL ?>cocina/marcarServido/<?= $pedido['id_pedido'] ?>" class="btn verde">✅ Servido</a>
            <a href="<?= BASE_URL ?>cocina/marcarCancelado/<?= $pedido['id_pedido'] ?>" class="btn rojo"
               onclick="return confirmarCancelacion();">❌ Cancelar</a>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/cocina_orden.css">
<script src="<?= BASE_URL ?>assets/js/cocina_orden.js"></script>

