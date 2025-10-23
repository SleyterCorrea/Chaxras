<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<div class="ordenes-wrapper">
    <div class="popup-contenido">
        <h3 class="popup-titulo">🧾 Detalle de Orden - Mesa <?= htmlspecialchars($pedido['mesa'] ?? 'Sin asignar') ?></h3>
        <p class="popup-subinfo">📅 <?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?></p>

        <form method="POST" action="<?= BASE_URL ?>mesero/confirmar_orden">
            <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">
            <table class="popup-tabla">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Plato</th>
                        <th>Categoría</th>
                        <th>Cant.</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th>Orden Entrega</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($detalle as $item): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                            <td><?= htmlspecialchars($item['tipo']) ?></td>
                            <td><input type="number" name="cantidad[<?= $item['id_detalle'] ?>]" value="<?= $item['cantidad'] ?>" min="1" class="orden-input cantidad-input"></td>
                            <td class="precio-unitario" data-precio="<?= $item['precio'] ?>">S/<?= number_format($item['precio'], 2) ?></td>
                            <td class="subtotal">S/<?= number_format($item['cantidad'] * $item['precio'], 2) ?></td>
                            <td><input type="number" name="orden_entrega[<?= $item['id_detalle'] ?>]" value="<?= $item['orden_entrega'] > 0 ? $item['orden_entrega'] : 1 ?>" min="1" class="orden-input"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="popup-total">
                Total: <strong>S/0.00</strong>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn-verde">💾 Confirmar y Enviar a Cocina</button>
            </div>
        </form>
    </div>
</div>

<style>
.ordenes-wrapper { margin-left: 270px; padding: 100px 30px 30px; font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; min-height: 100vh; }
.popup-contenido { padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 0 12px rgba(0,0,0,0.08); animation: fadeIn 0.4s ease-in-out; }
.popup-titulo { font-size: 22px; color: #104031; margin-bottom: 5px; }
.popup-subinfo { color: #666; font-size: 14px; margin-bottom: 15px; }
.popup-tabla { width: 100%; border-collapse: collapse; font-size: 15px; }
.popup-tabla th { background: #104031; color: white; }
.popup-tabla th, .popup-tabla td { padding: 10px; border: 1px solid #ccc; text-align: center; }
.popup-total { margin-top: 15px; text-align: right; font-size: 18px; color: #104031; }
.orden-input { width: 60px; padding: 5px; font-size: 14px; text-align: center; border: 1px solid #aaa; border-radius: 5px; }
.btn-verde { background: #28a745; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-size: 15px; cursor: pointer; }
.btn-verde:hover { background: #218838; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function actualizarTotales() {
        let total = 0;
        document.querySelectorAll('tbody tr').forEach(fila => {
            const cantidadInput = fila.querySelector('input[name^="cantidad"]');
            const precio = parseFloat(fila.querySelector('.precio-unitario').dataset.precio);
            const subtotalCelda = fila.querySelector('.subtotal');

            const cantidad = parseInt(cantidadInput.value);
            const subtotal = cantidad * precio;

            subtotalCelda.textContent = `S/${subtotal.toFixed(2)}`;
            total += subtotal;
        });
        document.querySelector('.popup-total strong').textContent = `S/${total.toFixed(2)}`;
    }

    document.querySelectorAll('.cantidad-input').forEach(input => {
        input.addEventListener('input', actualizarTotales);
    });

    actualizarTotales();
});
</script>
