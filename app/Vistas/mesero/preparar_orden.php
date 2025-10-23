<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<div class="ordenes-wrapper" style="margin-left: 300px;">
    <h2 class="titulo-principal">📝 Confirmar orden para la mesa <?= htmlspecialchars($mesa) ?></h2>

    <form id="form-guardar-orden">
        <input type="hidden" name="mesa" value="<?= $mesa ?>">

        <div class="tabla-contenedor">
            <table class="tabla-orden">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Plato</th>
                        <th>Categoría</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th>Orden entrega</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach ($detalle as $item): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                            <td><?= htmlspecialchars($item['tipo']) ?></td>
                            <td>
                                <input type="number" name="plato[<?= $item['id_plato'] ?>]" value="<?= $item['cantidad'] ?>"
                                    min="1" class="input-cantidad" data-precio="<?= $item['precio'] ?>">
                            </td>
                            <td>S/<?= number_format($item['precio'],2) ?></td>
                            <td class="subtotal">S/<?= number_format($item['precio'] * $item['cantidad'],2) ?></td>
                            <td>
                                <input type="number" name="orden_entrega[<?= $item['id_plato'] ?>]" value="1" min="1" class="input-orden">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="total-orden">
            Total: <strong>S/0.00</strong>
        </div>

        <div style="text-align: center; margin-top: 25px;">
            <button type="submit" class="btn-guardar">💾 Confirmar y enviar a cocina</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const actualizarTotal = () => {
        let total = 0;
        document.querySelectorAll('.tabla-orden tbody tr').forEach(fila => {
            const cantidad = parseInt(fila.querySelector('.input-cantidad').value) || 0;
            const precio = parseFloat(fila.querySelector('.input-cantidad').dataset.precio);
            const subtotal = cantidad * precio;
            fila.querySelector('.subtotal').textContent = `S/${subtotal.toFixed(2)}`;
            total += subtotal;
        });
        document.querySelector('.total-orden strong').textContent = `S/${total.toFixed(2)}`;
    };

    document.querySelectorAll('.input-cantidad').forEach(inp => {
        inp.addEventListener('input', actualizarTotal);
    });

    actualizarTotal();

    // AJAX para guardar la orden sin recargar
    document.getElementById('form-guardar-orden').addEventListener('submit', async function(e) {
        e.preventDefault();
        const datos = new URLSearchParams(new FormData(this));
        try {
            const resp = await fetch("<?= BASE_URL ?>mesero/guardarOrdenAjax", {
                method: "POST",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: datos
            });
            const res = await resp.json();
            alert(res.message);
            if (res.success) window.location.href = "<?= BASE_URL ?>mesero/mis_ordenes";
        } catch(err) {
            alert("Error al guardar la orden.");
            console.error(err);
        }
    });

});
</script>

<style>
.ordenes-wrapper {
    padding: 50px;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f7f6;
    min-height: 100vh;
}
.titulo-principal {
    color: #0c4b33;
    font-size: 26px;
    margin-bottom: 30px;
    text-align: center;
}
.tabla-contenedor {
    overflow-x: auto;
}
.tabla-orden {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
.tabla-orden th, .tabla-orden td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}
.tabla-orden th {
    background: #0c4b33;
    color: white;
}
.tabla-orden tbody tr:hover {
    background: #f0f8f4;
}
.input-cantidad, .input-orden {
    width: 60px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    text-align: center;
}
.total-orden {
    text-align: right;
    margin-top: 20px;
    font-size: 20px;
    color: #0c4b33;
    font-weight: bold;
}
.btn-guardar {
    background: #28a745;
    color: white;
    padding: 12px 40px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}
.btn-guardar:hover {
    background: #218838;
}
@media (max-width: 768px) {
    .ordenes-wrapper { margin-left: 0; padding: 20px; }
    .tabla-orden th, .tabla-orden td { font-size: 13px; }
    .input-cantidad, .input-orden { width: 50px; }
}
</style>
