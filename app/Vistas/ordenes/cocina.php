<h1>Órdenes en cocina</h1>

<?php if (empty($ordenes)): ?>
    <p>No hay órdenes pendientes o en preparación en este momento.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Mesero</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ordenes as $orden): ?>
                <tr>
                    <td><?= htmlspecialchars($orden['id_pedido']) ?></td>
                    <td><?= htmlspecialchars($orden['fecha']) ?></td>
                    <td><?= htmlspecialchars($orden['estado']) ?></td>
                    <td><?= htmlspecialchars($orden['mesero'] ?? 'N/A') ?></td>
                    <td>
                        <form method="POST" action="<?= BASE_URL ?>cocina/actualizarEstado">
                            <input type="hidden" name="id_detalle" value="<?= $orden['id_pedido'] ?>">
                            <button name="estado" value="servido">Servir</button>
                            <button name="estado" value="cancelado">Cancelar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
