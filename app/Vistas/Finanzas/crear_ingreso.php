<div class="contenedor-formulario">
    <div class="formulario-card">
        <h2 class="titulo-formulario">Registrar Ingreso</h2>
        <form method="post" action="<?= BASE_URL ?>finanzas/guardarIngresoManual">
            <div class="form-group">
                <label for="fuente">Fuente del ingreso</label>
                <input type="text" name="fuente" id="fuente" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="monto">Monto</label>
                <input type="number" step="0.01" name="monto" id="monto" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" required>
            </div>
            <button type="submit" class="btn-guardar">Guardar Ingreso</button>
        </form>
    </div>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/crud_finanzas.css">