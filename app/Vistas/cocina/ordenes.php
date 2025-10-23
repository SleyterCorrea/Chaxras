<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<div class="ordenes-wrapper" style="margin-left: 270px;">
    <h2>🍽 Órdenes en Cocina</h2>

    <?php if (empty($ordenes)): ?>
        <p style="margin-top:20px;">No hay pedidos pendientes.</p>
    <?php else: ?>
        <?php foreach ($ordenes as $orden): ?>
            <div class="orden-card">
                <h3>Pedido #<?= $orden['id_pedido'] ?> - Mesa <?= htmlspecialchars($orden['nombre_mesa'] ?? 'N/A') ?></h3>
                <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($orden['fecha'])) ?></p>
                <p><strong>Total:</strong> S/<?= number_format($orden['total'] ?? 0, 2) ?></p>

                <table class="orden-tabla">
                    <thead>
                        <tr>
                            <th>Plato</th>
                            <th>Categoría</th>
                            <th>Cant.</th>
                            <th>Estado</th>
                            <th>Orden Entrega</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orden['detalle'] as $d): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['plato']) ?></td>
                                <td><?= htmlspecialchars($d['tipo']) ?></td>
                                <td><?= $d['cantidad'] ?></td>
                                <td>
                                    <?= match($d['estado']) {
                                        'servido' => '✅',
                                        'cancelado' => '❌',
                                        default => '⏳',
                                    } ?>
                                </td>
                                <td><?= $d['orden_entrega'] ?? '-' ?></td>
                                <td>
                                    <?php if ($d['estado'] === 'pendiente'): ?>
                                        <form method="POST" action="<?= BASE_URL ?>cocina/actualizarEstado" style="display:inline;">
                                            <input type="hidden" name="id_detalle" value="<?= $d['id_detalle'] ?>">
                                            <input type="hidden" name="estado" value="servido">
                                            <button class="btn-accion servido">✅ Servir</button>
                                        </form>
                                        <form method="POST" action="<?= BASE_URL ?>cocina/actualizarEstado" style="display:inline;">
                                            <input type="hidden" name="id_detalle" value="<?= $d['id_detalle'] ?>">
                                            <input type="hidden" name="estado" value="cancelado">
                                            <button class="btn-accion cancelar">❌ Cancelar</button>
                                        </form>
                                    <?php else: ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.ordenes-wrapper {
    padding: 90px 30px;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f8;
    min-height: 100vh;
}
.orden-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    transition: transform 0.3s;
}
.orden-card:hover {
    transform: translateY(-4px);
}
.orden-card h3 {
    color: #104031;
    margin-bottom: 8px;
}
.orden-card p {
    margin: 4px 0;
    color: #444;
}
.orden-tabla {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 15px;
}
.orden-tabla th, .orden-tabla td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}
.orden-tabla th {
    background: #0a3d62;
    color: white;
}
.btn-accion {
    border: none;
    padding: 6px 14px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    margin: 2px;
}
.btn-accion.servido {
    background: #28a745;
    color: white;
}
.btn-accion.servido:hover {
    background: #218838;
}
.btn-accion.cancelar {
    background: #dc3545;
    color: white;
}
.btn-accion.cancelar:hover {
    background: #c82333;
}
</style>
