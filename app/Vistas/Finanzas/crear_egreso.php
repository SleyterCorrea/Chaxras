<div class="contenedor-formulario">
    <div class="formulario-card">
        <h2 class="titulo-formulario">Registrar Egreso</h2>
        <form method="post" action="<?= BASE_URL ?>finanzas/guardarEgresoManual">
            <div class="form-group">
                <label for="motivo">Motivo del egreso</label>
                <input type="text" name="motivo" id="motivo" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="monto">Monto</label>
                <input type="number" step="0.01" name="monto" id="monto" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" required>
            </div>
            <button type="submit" class="btn-guardar">Guardar Egreso</button>
        </form>
    </div>
</div>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/crud_finanzas.css">