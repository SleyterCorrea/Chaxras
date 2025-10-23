<div class="detalle-orden">
    <div class="header-orden">
        <h3>🧾 Mesa <?= htmlspecialchars($pedido['mesa'] ?? 'Sin asignar') ?></h3>
        <p class="subinfo">📅 <?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?></p>
    </div>

    <table class="tabla-detalle">
        <thead>
            <tr>
                <th>#</th>
                <th>Plato</th>
                <th>Tipo</th>
                <th>Cant.</th>
                <th>Precio</th>
                <th>Subtotal</th>
                <th>Estado</th>
                <th>Orden</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; $i=1; foreach ($detalle as $item): 
                $subtotal = $item['cantidad'] * $item['precio'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($item['nombre']) ?></td>
                <td><?= htmlspecialchars($item['tipo']) ?></td>
                <td><?= $item['cantidad'] ?></td>
                <td>S/<?= number_format($item['precio'], 2) ?></td>
                <td>S/<?= number_format($subtotal, 2) ?></td>
                <td>
                    <span class="estado <?= $item['estado'] ?>">
                        <?= match($item['estado']) {
                            'servido' => '✅',
                            'cancelado' => '❌',
                            default => '⏳'
                        } ?>
                    </span>
                </td>
                <td>
                    <span class="orden-badge"><?= $item['orden_entrega'] ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-final">
        Total: <strong>S/<?= number_format($total, 2) ?></strong>
    </div>
</div>

<style>
.detalle-orden {
    font-family: 'Segoe UI', sans-serif;
    color: #333;
    animation: fadeIn 0.4s ease;
    max-width: 900px;
    margin: 0 auto;
}
@keyframes fadeIn {
    from { opacity:0; transform: scale(0.95);}
    to { opacity:1; transform: scale(1);}
}
.header-orden {
    position: relative;
    background: linear-gradient(90deg, #28a745, #218838);
    color: white;
    padding: 14px 25px;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.header-orden h3 { margin: 0; font-size: 22px; }
.header-orden .subinfo { font-size: 13px; opacity: 0.95; }
.header-orden .cerrar-modal {
    background: transparent;
    border: none;
    color: white;
    font-size: 28px;
    cursor: pointer;
    transition: color 0.2s;
}
.header-orden .cerrar-modal:hover { color: #f8f9fa; }

.tabla-detalle {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
    margin-bottom: 20px;
    font-size: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.tabla-detalle th, .tabla-detalle td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: center;
}
.tabla-detalle th {
    background: #28a745;
    color: white;
}
.tabla-detalle tbody tr:hover {
    background: #e9f8ef;
}
.estado {
    font-size: 18px;
    display: inline-block;
    width: 32px;
    height: 32px;
    line-height: 30px;
    border-radius: 50%;
    border: 2px solid transparent;
}
.estado.servido { background: #28a745; color: white; border-color: #1e7e34; }
.estado.cancelado { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }
.estado.pendiente { background: #fff3cd; color: #856404; border-color: #ffeeba; }

.orden-badge {
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    line-height: 28px;
    display: inline-block;
    font-size: 14px;
    font-weight: bold;
    border: 2px solid #218838;
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
}

.total-final {
    text-align: right;
    padding: 12px;
    background: #e3f6ec;
    color: #28a745;
    font-size: 19px;
    font-weight: bold;
    border-radius: 0 0 10px 10px;
    box-shadow: inset 0 1px 4px rgba(0,0,0,0.1);
}
</style>

