<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<?php include __DIR__ . '/../layout/encabezado.php'; ?>

<div class="ordenes-wrapper">
    <h2 class="titulo-principal">🧾 Vista de Mesas</h2>

    <div class="tarjetas-container">
        <?php foreach ($mesas as $mesa): ?>
            <?php
                $orden = $ordenes_por_mesa[$mesa['id_mesa']] ?? null;
                $ocupada = $orden !== null;
                $estado_clase = $ocupada ? 'ocupada' : 'disponible';

                $servidos = $cancelados = $pendientes = [];
                $id_pedido = $orden['id_pedido'] ?? null;

                if ($ocupada) {
                    $estados = $estados_platos[$id_pedido] ?? [];
                    $servidos = array_filter($estados, fn($e) => $e === 'servido');
                    $cancelados = array_filter($estados, fn($e) => $e === 'cancelado');
                    $pendientes = array_filter($estados, fn($e) => $e === 'pendiente');
                }

                // determinar el badge
                $badge_text = 'Libre';
                $badge_class = 'badge-libre';
                if ($ocupada) {
                    if (count($pendientes) === 0) {
                        $badge_text = 'Listo';
                        $badge_class = 'badge-listo';
                    } else {
                        $badge_text = 'En prep.';
                        $badge_class = 'badge-prep';
                    }
                }
            ?>
            <div class="tarjeta-orden <?= $estado_clase ?>">
                <div class="badge-estado <?= $badge_class ?>"><?= $badge_text ?></div>
                <div class="mesa-icono">🍽</div>
                <h3 class="mesa-nombre"><?= htmlspecialchars($mesa['nombre']) ?></h3>

                <?php if ($ocupada): ?>
                    <p class="fecha">📅 <?= date('d/m/Y H:i', strtotime($orden['fecha'])) ?></p>
                    <p class="total">💰 Total: S/<?= number_format($orden['total'], 2) ?></p>
                    <p class="estados">
                        ✅ <?= count($servidos) ?> | ⏳ <?= count($pendientes) ?> | ❌ <?= count($cancelados) ?>
                    </p>
                    <div class="acciones">
                        <button class="btn-ver-orden" data-id="<?= $orden['id_pedido'] ?>">👁 Ver</button>
                        <button class="btn-finalizar" onclick="finalizarOrden(<?= $orden['id_pedido'] ?>)">✅ Finalizar</button>
                    </div>
                <?php else: ?>
                    <p class="mesa-libre">🟢 Mesa libre</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal -->
<div id="modal-orden" class="modal-orden">
    <div class="modal-contenido">
        <button class="cerrar-modal" aria-label="Cerrar">&times;</button>
        <div id="modal-body"></div>
    </div>
</div>

<style>
.ordenes-wrapper {
    margin-left: 274px;
    padding: 50px 30px;
    background: #f5f7fa;
    font-family: 'Segoe UI', sans-serif;
    min-height: 100vh;
}
.titulo-principal {
    font-size: 32px;
    color: #104031;
    margin-bottom: 40px;
    text-align: center;
}
.tarjetas-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    max-width: 1400px;
    margin: 0 auto;
}
.tarjeta-orden {
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    border-top: 6px solid;
    position: relative;
    transition: transform 0.3s, box-shadow 0.3s;
}
.tarjeta-orden:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.tarjeta-orden.disponible { border-color: #28a745; }
.tarjeta-orden.ocupada { border-color: #dc3545; }
.mesa-icono { font-size: 48px; }
.mesa-nombre { margin: 12px 0; font-size: 24px; color: #104031; }
.fecha, .total, .estados { font-size: 15px; margin: 6px 0; color: #444; }
.mesa-libre { color: #28a745; font-size: 16px; margin-top: 12px; }
.acciones { margin-top: 18px; }
.btn-ver-orden, .btn-finalizar {
    display: inline-block; margin: 5px 6px; padding: 10px 18px;
    border-radius: 8px; font-weight: bold; color: white;
    border: none; cursor: pointer; font-size: 15px;
    transition: background 0.3s;
}
.btn-ver-orden { background: #007bff; }
.btn-ver-orden:hover { background: #0056b3; }
.btn-finalizar { background: #28a745; }
.btn-finalizar:hover { background: #1e7e34; }

/* badge de estado */
.badge-estado {
    position: absolute; top: -14px; right: -14px;
    padding: 6px 10px;
    border-radius: 50px;
    font-size: 13px;
    color: white;
    font-weight: bold;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.badge-listo { background: #28a745; }
.badge-prep { background: #ffc107; color: #333; }
.badge-libre { background: #17a2b8; }

.modal-orden {
    display: none; position: fixed; z-index: 9999;
    left: 0; top: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    overflow: auto; animation: fadeInModal 0.3s ease forwards;
}
@keyframes fadeInModal {
    from { opacity: 0; transform: scale(0.97); }
    to { opacity: 1; transform: scale(1); }
}
.modal-contenido {
    background: #fff; margin: 60px auto; padding: 25px 35px;
    border-radius: 14px; width: 90%; max-width: 900px;
    box-shadow: 0 12px 25px rgba(0,0,0,0.3);
    position: relative;
}
.cerrar-modal {
    position: absolute; top: 12px; right: 20px;
    color: #888; font-size: 32px; font-weight: bold;
    background: none; border: none; cursor: pointer;
}
.cerrar-modal:hover { color: #000; }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // abrir modal con AJAX
    document.querySelectorAll('.btn-ver-orden').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            try {
                const resp = await fetch("<?= BASE_URL ?>mesero/verOrden/" + id);
                const html = await resp.text();
                document.getElementById('modal-body').innerHTML = html;
                document.getElementById('modal-orden').style.display = 'block';
            } catch (err) {
                alert("Error al cargar la orden");
                console.error(err);
            }
        });
    });

    const cerrarModal = () => document.getElementById('modal-orden').style.display = 'none';
    document.querySelector('.cerrar-modal').addEventListener('click', cerrarModal);
    window.onclick = e => { if (e.target.classList.contains('modal-orden')) cerrarModal(); };
    window.addEventListener('keydown', e => { if (e.key === "Escape") cerrarModal(); });
});

function finalizarOrden(id) {
    if (!confirm("¿Estás seguro de finalizar y liberar la mesa?")) return;

    fetch("<?= BASE_URL ?>mesero/finalizarOrdenAjax", {
        method: "POST",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: "id_pedido=" + id
    })
    .then(r => r.json())
    .then(res => {
        alert(res.message);
        if (res.success) {
            const tarjeta = document.querySelector(`.btn-finalizar[onclick*="${id}"]`).closest('.tarjeta-orden');
            if (tarjeta) tarjeta.remove(); // oculta la tarjeta sin recargar
        }
    })
    .catch(err => {
        alert("Error al finalizar el pedido");
        console.error(err);
    });
}



</script>
